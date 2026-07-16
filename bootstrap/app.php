<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // Redirect unauthenticated users to the Google login page.
        // This replaces the default redirect to /login (which is our admin login).
        // Laravel automatically stores the intended URL before redirecting here,
        // so redirect()->intended() in GoogleController restores the checkout URL.
        $middleware->redirectGuestsTo(fn () => route('google.login'));

        // Exclude the Midtrans webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
        ]);

        // Trust all proxies so HTTPS is detected correctly through tunnels
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
    
    
