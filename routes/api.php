<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Api\Lab\LabReservationController;
use App\Http\Controllers\Api\Lab\MaterialController;
use App\Http\Controllers\Api\Lab\SpaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AuthController::class, 'me'])->name('me');

        Route::post('/device-tokens', [DeviceTokenController::class, 'store'])->name('device-tokens.store');
        Route::delete('/device-tokens', [DeviceTokenController::class, 'destroy'])->name('device-tokens.destroy');

        Route::get('/dashboard', [LabReservationController::class, 'dashboard'])->name('dashboard');
        Route::get('/spaces', [SpaceController::class, 'index'])->name('spaces.index');
        Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
        Route::get('/calendar', [LabReservationController::class, 'calendarEvents'])->name('calendar');
        Route::get('/spaces/{space}/availability', [LabReservationController::class, 'availability'])->name('spaces.availability');

        Route::middleware('can-coordinate')->group(function () {
            Route::get('/auxiliares', [SpaceController::class, 'auxiliares'])->name('auxiliares');

            Route::post('/spaces', [SpaceController::class, 'store'])->name('spaces.store');
            Route::post('/spaces/{space}/update', [SpaceController::class, 'update'])->name('spaces.update');
            Route::delete('/spaces/{space}', [SpaceController::class, 'destroy'])->name('spaces.destroy');

            Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
            Route::post('/materials/{material}/update', [MaterialController::class, 'update'])->name('materials.update');
            Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy');
        });

        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('/', [LabReservationController::class, 'index'])->name('index');
            Route::post('/', [LabReservationController::class, 'store'])->name('store');
            Route::get('/history', [LabReservationController::class, 'history'])->name('history');
            Route::get('/{reservation}', [LabReservationController::class, 'show'])->name('show');
            Route::post('/{reservation}/start', [LabReservationController::class, 'startClass'])->name('start');
            Route::post('/{reservation}/professor-obs', [LabReservationController::class, 'submitProfessorObs'])->name('professor-obs');
            Route::post('/{reservation}/auxiliar-finalize', [LabReservationController::class, 'auxiliarFinalize'])->name('auxiliar-finalize');
            Route::post('/{reservation}/images', [LabReservationController::class, 'uploadImage'])->name('images');

            Route::middleware('can-coordinate')->group(function () {
                Route::patch('/{reservation}/approve', [LabReservationController::class, 'approve'])->name('approve');
                Route::patch('/{reservation}/reject', [LabReservationController::class, 'reject'])->name('reject');
                Route::patch('/{reservation}/validate', [LabReservationController::class, 'validateActivity'])->name('validate');
                Route::post('/{reservation}/scanned-doc', [LabReservationController::class, 'uploadScannedDoc'])->name('scanned-doc');
            });
        });
    });
});
