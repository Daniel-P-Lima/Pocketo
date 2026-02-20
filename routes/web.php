<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StashController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Transactions
Route::resource('transactions', TransactionController::class);

// Stash
Route::resource('stash', StashController::class);

// Budgets
Route::resource('budgets', BudgetController::class);

// Categories
Route::resource('categories', CategoryController::class);

// More page
Route::get('/more', fn () => Inertia::render('More/Index'))->name('more');

// API endpoints for charts
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');
    Route::get('/dashboard/trend', [DashboardController::class, 'spendingTrend'])->name('dashboard.trend');
    Route::get('/dashboard/categories', [DashboardController::class, 'categoryBreakdown'])->name('dashboard.categories');
});
