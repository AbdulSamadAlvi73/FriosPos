<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CorporateAdminControllers\CorporateAdminController;
use App\Http\Controllers\FranchiseAdminControllers\FranchiseAdminController;
use App\Http\Controllers\FranchiseManagerControllers\FranchiseManagerController;
use App\Http\Controllers\FranchiseStaffController\FranchiseStaffController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:corporate_admin'])->group(function () {
    Route::get('/corporate/dashboard', [CorporateAdminController::class, 'dashboard']);
});

Route::middleware(['auth', 'role:franchise_admin'])->group(function () {
    Route::get('/franchise/dashboard', [FranchiseAdminController::class, 'dashboard']);
});

Route::middleware(['auth', 'role:franchise_manager'])->group(function () {
    Route::get('/manager/dashboard', [FranchiseManagerController::class, 'dashboard']);
});

Route::middleware(['auth', 'role:franchise_staff'])->group(function () {
    Route::get('/staff/dashboard', [FranchiseStaffController::class, 'dashboard']);
});

require __DIR__.'/auth.php';
