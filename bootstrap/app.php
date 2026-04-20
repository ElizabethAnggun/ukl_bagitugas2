<?php

/**
 * Bootstrap Aplikasi BagiTugas
 * 
 * File ini menginisialisasi aplikasi Laravel.
 * Di sini kita membuat instance aplikasi, mengatur service providers,
 * dan mengembalikan aplikasi untuk dijalankan.
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Buat instance aplikasi Laravel
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Konfigurasi middleware global
        // Middleware auth sudah tersedia dari Laravel
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi exception handling
    })
    ->create();

// Return aplikasi
return $app;
