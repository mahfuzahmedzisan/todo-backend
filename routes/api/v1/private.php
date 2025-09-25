<?php

use App\Http\Controllers\API\V1\AuthenticationController;
use App\Http\Controllers\API\V1\TodoManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserManagementController as UserController;

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/refresh', 'refresh')->name('refresh');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'users')->name('users');
});


Route::group(['prefix' => '/me', 'as' => 'me.'], function () {
    Route::controller(TodoManagementController::class)->prefix('/todos')->name('todos.')->group(function () {
        Route::get('/', 'todos')->name('todos');
        Route::get('/{id}', 'todo')->name('todo');
        Route::get('/due', 'dueTodos')->name('dueTodos');
        Route::post('/', 'createTodo')->name('createTodo');
        Route::put('/{id}', 'updateTodo')->name('updateTodo');
        Route::delete('/{id}', 'deleteTodo')->name('deleteTodo');
        Route::delete('/', 'bulkDeleteTodo')->name('bulkDeleteTodo');
        Route::post('/{id}/complete', 'completeTodo')->name('completeTodo');
        Route::post('/{id}/incomplete', 'incompleteTodo')->name('incompleteTodo');
        Route::post('/{id}/due', 'dueTodo')->name('dueTodo');
    });
});
