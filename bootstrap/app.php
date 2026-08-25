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
        // 1. កំណត់ឱ្យទុកចិត្ត Proxy ទាំងអស់របស់ Render (កែបញ្ហា 302 Loop ពេល Login/Register)
        $middleware->trustProxies(at: '*');

        // 2. Middleware Alias របស់អ្នកដែលមានស្រាប់
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();