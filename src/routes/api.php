<?php

use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', [UserController::class, 'index']);

Route::post('/register', [UserController::class, 'store']);

Route::group(["prefix" => "topics"], function () {
    Route::get('/', [TopicController::class, 'index']);
    Route::get('/{topic}', [TopicController::class, 'show']);
    Route::post('/', [TopicController::class, 'store'])->middleware('auth:api');
});
