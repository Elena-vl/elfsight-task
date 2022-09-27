<?php

use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\ReviewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/episodes')->name('api.episodes.')->group(function () {
    Route::get('/', [EpisodeController::class, 'index'])->name('index');
    Route::get('/{id}', [EpisodeController::class, 'show'])->name('show');
});

Route::prefix('v1/reviews')->name('api.reviews.')->group(function () {
    Route::get('/', [ReviewsController::class, 'index'])->name('index');
    Route::get('/{id}', [ReviewsController::class, 'show'])->name('show');
    Route::post('/', [ReviewsController::class, 'store'])->name('store');
});