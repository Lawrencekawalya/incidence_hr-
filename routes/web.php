<?php

use App\Http\Controllers\HrRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HrRecordController::class, 'index'])->name('index');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('index');
})->name('logout');

Route::resource('hr', HrRecordController::class);