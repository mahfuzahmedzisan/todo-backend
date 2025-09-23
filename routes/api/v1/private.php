<?php

use App\Http\Controllers\API\V1\AuthenticationController;
use App\Http\Controllers\API\V1\TodoManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserManagementController as UserController;

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'users')->name('users');
});

Route::controller(TodoManagementController::class)->group(function () {
    Route::group(['prefix' => '/me', 'as' => 'me.'], function () {
        Route::get('/todos', 'todos')->name('todos');
        Route::get('/todos/{id}', 'todo')->name('todo');
        Route::get('/todos/due', 'dueTodos')->name('dueTodos');
        Route::post('/todos', 'createTodo')->name('createTodo');
        Route::put('/todos/{id}', 'updateTodo')->name('updateTodo');
        Route::delete('/todos/{id}', 'deleteTodo')->name('deleteTodo');
        Route::post('/todos/{id}/complete', 'completeTodo')->name('completeTodo');
        Route::post('/todos/{id}/incomplete', 'incompleteTodo')->name('incompleteTodo');
        Route::post('/todos/{id}/due', 'dueTodo')->name('dueTodo');
    });
});
