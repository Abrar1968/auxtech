<?php

use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

/*
| Browser installer. These routes run without sessions or cookies because
| no application key exists before installation.
*/
Route::get('install', [InstallController::class, 'show'])->name('install.show');
Route::post('install', [InstallController::class, 'store'])->name('install.store');
