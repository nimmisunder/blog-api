<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\TagController;

Route::middleware('auth:sanctum')->post('/posts', [PostController::class, 'store']);
Route::middleware('auth:sanctum')->get('/posts', [PostController::class, 'index']);
Route::middleware('auth:sanctum')->post('/comments', [CommentController::class, 'store']);
Route::middleware('auth:sanctum')->get('/posts/{id}/comments', [CommentController::class, 'index']);
Route::middleware('auth:sanctum')->post('/tags', [TagController::class, 'store']);
Route::middleware('auth:sanctum')->post('/posts/{id}/tags', [PostController::class, 'assignTags']);
Route::middleware('auth:sanctum')->get('/posts/{id}/tags', [PostController::class, 'getTags']);


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
