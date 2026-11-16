<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Environment check page. Replaced by the real home page in Phase 2.
Route::get('/', fn () => Inertia::render('Site/Home', [
    'seo' => ['title' => 'Auxtech | Website, App Development & Digital Marketing in Bangladesh'],
    'company' => config('auxtech.company'),
    'whatsappUrl' => config('auxtech.whatsapp.base_url').config('auxtech.whatsapp.number'),
    'stack' => [
        'laravel' => app()->version(),
        'php' => PHP_VERSION,
        'database' => config('database.default'),
    ],
]))->name('home');
