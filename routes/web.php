<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdvertisesController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MentorMiddleware;
use App\Http\Middleware\UserMiddleware;
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

Route::get('/test', [DashboardController::class, 'test']);

Route::middleware('auth')->group(function () {

    Route::middleware(UserMiddleware::class)->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('advertise')->group(function () {
            Route::get('/{amazonAdvertise}', [AdvertisesController::class, 'show'])->name('advertise.show');
            Route::get('/{amazonAdvertise}/sync', [AdvertisesController::class, 'sync'])->name('advertise.sync');
        });

        Route::prefix('accounts')->group(function () {
            Route::get('/', [AccountsController::class, 'index'])->name('accounts');
            Route::get('/sync/{account}', [AccountsController::class, 'sync'])->name('accounts.sync');
        });
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware(MentorMiddleware::class)->prefix('mentor')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/create', [RegisteredUserController::class, 'create'])->name('users.create');
            Route::get('/view/{user}', [RegisteredUserController::class, 'show'])->name('users.show');
            Route::post('/', [RegisteredUserController::class, 'store'])->name('users.store');

            Route::prefix('/{user}')->group(function () {
                Route::get('/', [DashboardController::class, 'index'])->name('mentor.user.dashboard');

                Route::prefix('advertise')->group(function () {
                    Route::get('/{amazonAdvertise}', [AdvertisesController::class, 'show'])->name('mentor.user.advertise.show');
                    Route::get('/{amazonAdvertise}/sync', [AdvertisesController::class, 'sync'])->name('mentor.user.advertise.sync');
                });

                Route::prefix('accounts')->group(function () {
                    Route::get('/', [AccountsController::class, 'index'])->name('mentor.user.accounts');
                    Route::get('/sync/{account}', [AccountsController::class, 'sync'])->name('mentor.user.accounts.sync');
                });
            });
        });

        Route::get('/', [MentorController::class, 'index'])->name('mentor.dashboard');
        Route::get('/users', [MentorController::class, 'users'])->name('mentor.users');
        Route::get('/{user}', [MentorController::class, 'show'])->name('mentor.show');
    });

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/admin', function () {
            return ["sapo" => "sapo"];
        })->name('admin.dashboard');
    });
});

require __DIR__ . '/auth.php';
