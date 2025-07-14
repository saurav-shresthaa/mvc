<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::prefix('/')->controller(PostController::class)->group(function () {
    Route::get('/', 'index')->name('post.index');
    Route::post('/create', 'create')->name('post.create');
    Route::get('/{id}', 'show')->name('post.show');
    Route::put('/edit/{id}', 'edit')->name('post.edit');
    Route::post('/delete/{id}', 'deletePost')->name('post.delete');
});
