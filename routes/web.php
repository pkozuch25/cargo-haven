<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepositsController;
use App\Http\Controllers\DispositionsController;
use App\Http\Controllers\StorageYardsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RegistrationRequestController;

Route::get('/', function () {
    return redirect('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dispositions', [DispositionsController::class, 'index'])->name('dispositions.index');
    Route::get('/deposits', [DepositsController::class, 'index'])->name('deposits.index');
    Route::get('/storage-yards', [StorageYardsController::class, 'index'])->name('storage-yards.index');
    Route::get('/registration-requests', [RegistrationRequestController::class, 'index'])->name('registration-requests.index');

    // SELECT2

    Route::get('/get-operators-to-select2', [RegisteredUserController::class, 'getOperatorsToSelect2'])->name('get-operators-to-select2');
});

require __DIR__.'/auth.php';
