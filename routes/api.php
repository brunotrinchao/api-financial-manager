<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->group(function () {
    // Usuários
    Route::apiResource('users', UserController::class)->only(['show', 'update', 'destroy']);

    // Contas
    Route::apiResource('accounts', AccountController::class);

    // Cartões
    Route::apiResource('credit-cards', CreditCardController::class);

    // Categorias
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // Transações
    Route::apiResource('transactions', TransactionController::class);

    Route::get('/settings', [SettingsController::class, 'index']);

    Route::middleware(['admin'])->group(function () {
        Route::apiResource('banks', BankController::class);
        Route::apiResource('issues', IssueController::class);
    });

});
