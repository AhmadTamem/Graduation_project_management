<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectFileController;
use App\Http\Controllers\Api\ProjectUpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function (){
    Route::post('register','Register');
    Route::post('login','login');
    Route::get('profile','profile')->middleware('auth:sanctum');
    Route::get('logout','logout')->middleware('auth:sanctum');
    Route::get('get_supervisor','supervisor');
});
Route::controller(ProjectController::class)->group(function () {
   Route::post('store_project','store')->middleware('auth:sanctum');
   Route::get('projects','index')->middleware('auth:sanctum');
   Route::get('project/{id}','show')->middleware('auth:sanctum');
   Route::put('update_project/{id}','update')->middleware('auth:sanctum'); 
});
Route::controller(ProjectFileController::class)->group(function () {
   Route::post('store_file/{id}','store')->middleware('auth:sanctum');
   Route::delete('delete_file/{id}','destroy')->middleware('auth:sanctum');
});
Route::controller(ProjectUpdateController::class)->group(function () {
   Route::post('store_update/{id}','store')->middleware('auth:sanctum');
});
Route::controller(CommentController::class)->group(function () {
   Route::post('store_comment/{id}','store')->middleware('auth:sanctum');
});
Route::controller(EvaluationController::class)->group(function () {
   Route::post('store_evaluation/{id}','store')->middleware('auth:sanctum');
});

