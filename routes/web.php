<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepositsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\DispositionsController;
use App\Http\Controllers\RegistrationRequestController;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/deposits', [DepositsController::class, 'index'])->name('deposits.index');
    Route::get('/dispositions', [DispositionsController::class, 'index'])->name('dispositions.index');
    Route::get('/permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::get('/registration-requests', [RegistrationRequestController::class, 'index'])->name('registration-requests.index');

    // SELECT2

    Route::get('/get-operators-to-select2', [RegisteredUserController::class, 'getOperatorsToSelect2'])->name('get-operators-to-select2');
});

require __DIR__.'/auth.php';
