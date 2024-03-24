<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MentorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/wellcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('accounts')->group(function () {
        Route::get('/', [AccountsController::class, 'index'])->name('accounts');
        Route::get('/sync/{account}', [AccountsController::class, 'sync'])->name('accounts.sync');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/admin', function () {
            return ["sapo" => "sapo"];
        });
    });

    Route::middleware(MentorMiddleware::class)->get('/mentor', function () {
        // return json
        return json_encode(["sapo" => "sapo"]);
    });
});

require __DIR__ . '/auth.php';
