<?php

use App\Http\Controllers\Api\V1\AnswerController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Questions
    Route::get('questions/random', [QuestionController::class, 'random'])->name('questions.random');
    Route::apiResource('questions', QuestionController::class);

    // Answers
    Route::get('questions/{question}/answers', [AnswerController::class, 'index'])->name('answers.index');
    Route::apiResource('answers', AnswerController::class)->except(['index']);
});
