<?php

use App\Http\Controllers\ApplicationResetController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::prefix('applications')->name('applications.')->controller(ApplicationsController::class)->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('', 'store')->name('store');
    Route::delete('reset', ApplicationResetController::class)->name('reset');
    Route::get('{application}', 'show')->name('show');
    Route::put('{application}', 'update')->name('update');
    Route::delete('{application}', 'destroy')->name('destroy');
});
