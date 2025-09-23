<?php

use App\Http\Controllers\API\V1\AuthenticationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserManagementController as UserController;

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'users')->name('users');
});
