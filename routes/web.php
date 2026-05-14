<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxiRequestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [TaxiRequestController::class, 'index'])->name('taxi.index');
    Route::post('/solicitar-taxi', [TaxiRequestController::class, 'store'])->name('taxi.store');
    Route::patch('/taxi/{taxiRequest}/status', [TaxiRequestController::class, 'updateStatus'])->name('taxi.updateStatus');
    Route::get('/historial', [TaxiRequestController::class, 'history'])->name('taxi.history');
    
    // Admin features
    Route::middleware('admin')->group(function () {
        Route::get('/gestor', [TaxiRequestController::class, 'gestorIndex'])->name('taxi.gestor');
        Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
        Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
        Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
