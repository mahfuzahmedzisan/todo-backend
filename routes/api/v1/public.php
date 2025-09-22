
<?php

use App\Http\Controllers\API\V1\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});
