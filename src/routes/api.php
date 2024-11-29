<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', [UserController::class, 'index']);

Route::post('/register', [UserController::class, 'store']);

Route::group(["prefix" => "topics"], function () {
    Route::get('/', [TopicController::class, 'index']);
    Route::get('/{topic}', [TopicController::class, 'show']);
    Route::post('/', [TopicController::class, 'store'])->middleware('auth:api');
    Route::patch('/{topic}', [TopicController::class, 'update'])->middleware('auth:api');
    Route::delete('/{topic}', [TopicController::class, 'destroy'])->middleware('auth:api');

    Route::group(["prefix" => "/{topic}/posts"], function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/{post}', [PostController::class, 'show']);
        Route::post('/', [PostController::class, 'store'])->middleware('auth:api');
        Route::patch('/{post}', [PostController::class, 'update'])->middleware('auth:api');
        Route::delete('/{post}', [PostController::class, 'destroy'])->middleware('auth:api');
    });
});
