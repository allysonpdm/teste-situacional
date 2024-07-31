<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmpresaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->name('auth.')->group(function () {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('me', [AuthController::class, 'me'])->name('me');

    // TODO: implementar futuramente
    // Route::prefix('senha/')
    //     ->name('pass.')
    //     ->group(function () {
    //         Route::post('recuperar', [PasswordController::class, 'recuperar'])->name('recovery');
    //         Route::post('verificar-codigo-recuperacao', [PasswordController::class, 'verificarCodigoRecuperacao'])->name('verify-code');
    //         Route::put('resetar', [PasswordController::class, 'resetar'])->name('reset');
    //     });
});

Route::apiResource('empresa', EmpresaController::class);
