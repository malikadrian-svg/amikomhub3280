<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PartnerProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Organizer\AnalyticsController as OrganizerAnalyticsController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\GoogleController;

// =============================================================================
// Public Routes (no authentication required)
// =============================================================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Public partner profile page
Route::get('/partners/{partner}', [PartnerProfileController::class, 'show'])->name('partners.show');

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

    // ─── Review Routes ────────────────────────────────────────────────────────
    // throttle:5,1 = max 5 review submissions per minute per user (anti-spam)
    Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store')
        ->middleware('throttle:5,1');

    Route::put('/reviews/{review}', [ReviewController::class, 'update'])
        ->name('reviews.update');

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
        ->name('reviews.destroy');

    // ─── Organizer Registration ──────────────────────────────────────────────
    Route::get('/organizer/register', [\App\Http\Controllers\OrganizerRegistrationController::class, 'create'])
        ->name('organizer.register');
    Route::post('/organizer/register', [\App\Http\Controllers\OrganizerRegistrationController::class, 'store'])
        ->name('organizer.register.store');

});

// =============================================================================
// Organizer Dashboard Area
// =============================================================================

Route::prefix('organizer/{organization:slug}')
    ->middleware(['auth', 'org'])
    ->name('organizer.')
    ->group(function () {
        
        // Organizer Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Organizer\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Analytics Charts
        Route::get('/analytics/revenue-chart', [OrganizerAnalyticsController::class, 'revenueChart'])->name('analytics.revenue-chart');
            
        // Organizer Events & Ticket Types
        Route::resource('events', \App\Http\Controllers\Organizer\EventController::class);
        Route::patch('events/{event}/submit', [\App\Http\Controllers\Organizer\EventController::class, 'submitForApproval'])->name('events.submit');
        
        Route::resource('events.ticket-types', \App\Http\Controllers\Organizer\TicketTypeController::class)->only(['store', 'update', 'destroy']);
        
    });

// =============================================================================
// Midtrans Webhook (public, no CSRF — excluded in bootstrap/app.php)
// =============================================================================

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// =============================================================================
// Authenticated Users
// =============================================================================
Route::middleware('auth')->group(function () {
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});

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
        
        // Analytics Charts
        Route::get('analytics/data', [AdminAnalyticsController::class, 'data'])->name('analytics.data');

        Route::resource('events', AdminEventController::class)->except(['show']);
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::resource('categories', CategoryController::class)->except(['show']);
        
        // Organization Management & Approvals
        Route::get('organizations', [OrganizationController::class, 'index'])->name('organizations.index');
        Route::get('organizations/documents/{document}', [OrganizationController::class, 'downloadDocument'])->name('organizations.document.download');
        Route::patch('organizations/{organization}/approve', [OrganizationController::class, 'approve'])->name('organizations.approve');
        Route::patch('organizations/{organization}/suspend', [OrganizationController::class, 'suspend'])->name('organizations.suspend');

        // Event Approvals
        Route::get('event-approvals', [\App\Http\Controllers\Admin\EventApprovalController::class, 'index'])->name('event-approvals.index');
        Route::patch('event-approvals/{event}/approve', [\App\Http\Controllers\Admin\EventApprovalController::class, 'approve'])->name('event-approvals.approve');
        Route::patch('event-approvals/{event}/reject', [\App\Http\Controllers\Admin\EventApprovalController::class, 'reject'])->name('event-approvals.reject');

        // Review management
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::patch('reviews/{review}/toggle', [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggle');

        // Platform Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        // User Management
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    });
});
