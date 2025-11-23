<?php

use App\Http\Controllers\Auth\RequestOtpController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Panel\Deductions\DeductionController;
use App\Http\Controllers\Panel\Requests\OrganizationRequestController;
use App\Http\Controllers\Panel\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    \Illuminate\Support\Facades\Auth::loginUsingId(30);
    return redirect('/organization-panel');
});

Route::post('/send-otp', RequestOtpController::class)->name('send-otp');


Route::prefix('/organization-panel')->middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::post('/import', [UserController::class, 'import'])->name('users.import');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'delete'])->name('users.destroy');
    });

    Route::prefix('/requests')->group(function () {
        Route::get('/charge-accounts', [OrganizationRequestController::class, 'getChargeAccountsList'])->name('requests.charge-account');
        Route::post('/charge-accounts/update-status', [OrganizationRequestController::class, 'updateChargeAccountRequestStatus'])->name('requests.charge-accounts.update-status');
        Route::get('/reissue-card-requests', [OrganizationRequestController::class, 'getReissueCardRequestsList'])->name('requests.reissue-card-requests');
        Route::post('/reissue-card-requests/update-status', [OrganizationRequestController::class, 'updateReissueCardRequestStatus'])->name('requests.reissue-card-requests.update-status');
    });

    Route::prefix('deductions')->group(function () {
        Route::get('/', [DeductionController::class, 'index'])->name('deductions.files.index');
        Route::get('/create', [DeductionController::class, 'create'])->name('deductions.files.create');
        Route::post('/', [DeductionController::class, 'store'])->name('deductions.files.store');
        Route::get('/{file}', [DeductionController::class, 'show'])->name('deductions.files.show');
        Route::get('/{file}/export', [DeductionController::class, 'export'])->name('deductions.files.export');
    });
});
