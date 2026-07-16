<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleController extends Controller
{
    /**
     * Show the "Continue with Google" landing page.
     *
     * Only accessible to guests. Authenticated users are redirected away.
     */
    public function showLoginPage(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }

        return view('auth.google-login');
    }

    /**
     * Redirect the browser to Google's OAuth consent screen.
     *
     * Scopes requested: openid, email, profile (default Socialite scopes).
     * These are the minimum required to identify a user and pre-fill checkout.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the OAuth callback from Google.
     *
     * Security measures applied:
     * - InvalidStateException caught (prevents CSRF / replay attacks)
     * - Admin emails are blocked from OAuth login (privilege separation)
     * - Duplicate prevention via email uniqueness lookup before creation
     * - Session is regenerated after login (prevents session fixation)
     * - redirect()->intended() restores the original checkout URL from session
     */
    public function callback(Request $request): RedirectResponse
    {
        // 1. Catch user cancellation or state mismatch (CSRF protection)
        if ($request->has('error')) {
            return redirect()->route('google.login')
                ->with('error', 'Login dibatalkan. Silakan coba lagi.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            // State mismatch — likely a CSRF attempt or expired OAuth session
            Log::warning('Google OAuth InvalidStateException: ' . $e->getMessage(), [
                'ip' => $request->ip(),
            ]);
            return redirect()->route('google.login')
                ->with('error', 'Sesi OAuth tidak valid atau sudah kedaluwarsa. Silakan coba login lagi.');
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage(), [
                'ip' => $request->ip(),
            ]);
            return redirect()->route('google.login')
                ->with('error', 'Terjadi kesalahan saat menghubungi Google. Silakan coba beberapa saat lagi.');
        }

        // 2. Validate that Google returned an email address
        if (empty($googleUser->getEmail())) {
            return redirect()->route('google.login')
                ->with('error', 'Akun Google Anda tidak memiliki alamat email yang dapat diakses. Silakan gunakan akun Google lain.');
        }

        $email    = $googleUser->getEmail();
        $googleId = $googleUser->getId();

        // 3. Block admin accounts from OAuth login (privilege separation)
        $existingUser = User::where('email', $email)->first();

        if ($existingUser && $existingUser->isAdmin()) {
            return redirect()->route('google.login')
                ->with('error', 'Email ini terdaftar sebagai akun admin. Gunakan halaman login admin untuk masuk.');
        }

        // 4. Find existing regular user or create a new one
        $user = $this->findOrCreateUser($googleUser, $existingUser);

        // 5. Login and regenerate session (prevents session fixation)
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        // 6. Redirect to the originally requested URL (e.g., /checkout/15)
        //    Falls back to homepage if no intended URL was stored.
        return redirect()->intended(route('home'));
    }

    /**
     * Log out the currently authenticated public user.
     *
     * Does NOT affect admin sessions. Invalidates the session entirely
     * and regenerates the CSRF token.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil keluar.');
    }

    // =========================================================================
    // Private Helpers
    // =========================================================================

    /**
     * Find an existing user by Google ID or email, or create a new user.
     *
     * Logic:
     *  1. If $existingUser was found by email: update their google_id if not set.
     *  2. Otherwise: try finding by google_id (account was renamed / re-lookup).
     *  3. Still no user: create a brand new account.
     *
     * Email_verified_at is set to now() because Google already verified the email.
     * The user role is always 'user' — never 'admin'.
     *
     * @param  \Laravel\Socialite\Two\User  $googleUser
     * @param  User|null  $existingUser  User found by email lookup (or null)
     */
    private function findOrCreateUser($googleUser, ?User $existingUser): User
    {
        $email    = $googleUser->getEmail();
        $googleId = $googleUser->getId();
        $name     = $googleUser->getName() ?? $email;
        $avatar   = $googleUser->getAvatar();

        // Case 1: User already exists by email
        if ($existingUser) {
            // Link their Google ID if they've never logged in via Google before
            if (empty($existingUser->google_id)) {
                $existingUser->update([
                    'google_id'   => $googleId,
                    'avatar'      => $avatar,
                    'provider'    => 'google',
                    'provider_id' => $googleId,
                ]);
            }
            return $existingUser;
        }

        // Case 2: User exists by google_id (email might have changed on Google)
        $byGoogleId = User::where('google_id', $googleId)->first();
        if ($byGoogleId) {
            $byGoogleId->update([
                'name'   => $name,
                'email'  => $email,
                'avatar' => $avatar,
            ]);
            return $byGoogleId;
        }

        // Case 3: Brand new user — create account
        return User::create([
            'name'              => $name,
            'email'             => $email,
            'password'          => null,   // OAuth users have no password
            'role'              => 'user',
            'google_id'         => $googleId,
            'avatar'            => $avatar,
            'provider'          => 'google',
            'provider_id'       => $googleId,
            'email_verified_at' => now(),  // Google has already verified the email
        ]);
    }
}
