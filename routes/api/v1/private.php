<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserManagementController as UserController;

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'users')->name('users');
});
