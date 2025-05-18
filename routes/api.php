<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\StatusController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => "api"], function () {

    Route::group(["prefix" => "auth"], function () {
        Route::post("/login", [AuthController::class, "login"]);
        Route::post("/sign-up", [AuthController::class, "register"]);
        Route::post("/logout", [AuthController::class, "logout"])->middleware(['auth:sanctum']);
    });
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware(['auth:sanctum']);

    Route::group(["middleware" => "auth:sanctum"], function () {
        Route::get("tasks-filter", [TaskController::class, "filter"]);
        Route::apiResource("tasks", TaskController::class);
        Route::apiResource("statuses", StatusController::class);
        Route::apiResource("tasks.comments", CommentController::class);
    });
});
