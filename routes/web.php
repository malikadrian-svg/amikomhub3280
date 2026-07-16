<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\GoogleController;

// =============================================================================
// Public Routes (no authentication required)
// =============================================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// =============================================================================
// Google OAuth Routes (guests only for login page & redirect)
// =============================================================================

// The "Continue with Google" landing page
Route::get('/auth/google-login', [GoogleController::class, 'showLoginPage'])
    ->name('google.login');

// Initiate the OAuth flow → redirect user to Google
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

// Google sends user back here after authentication
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');

// =============================================================================
// Authenticated User Routes (requires Google SSO login)
// =============================================================================

Route::middleware('auth')->group(function () {

    // Checkout flow (protected — auth required before purchasing tickets)
    Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

    // Payment & success pages protected to prevent order_id enumeration
    Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

    // My Tickets — user's ticket history and individual ticket view
    Route::get('/my-tickets', [TicketController::class, 'index'])->name('my-tickets');
    Route::get('/my-ticket/{order_id}', [TicketController::class, 'show'])->name('ticket');

    // User logout
    Route::post('/auth/logout', [GoogleController::class, 'logout'])->name('user.logout');

});

// =============================================================================
// Midtrans Webhook (public, no CSRF — excluded in bootstrap/app.php)
// =============================================================================

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// =============================================================================
// Admin Area
// =============================================================================

Route::prefix('admin')->group(function () {
    // Admin login (separate from public Google login)
    Route::get('login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Admin protected area
    Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', AdminEventController::class)->except(['show']);
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('partners', PartnerController::class)->except(['show']);
    });
});
