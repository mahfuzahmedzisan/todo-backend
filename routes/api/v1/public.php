
<?php

use App\Http\Controllers\API\V1\Auth\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::get('/users', function () {
    $users = \App\Models\User::all();
    return response()->json($users);
});

Route::post('/login', [AuthenticationController::class , 'login'])->name('login');
