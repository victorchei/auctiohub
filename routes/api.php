<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BidApiController;
use App\Http\Controllers\Api\LotApiController;
use App\Http\Controllers\Api\WatchlistApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login'])->middleware('throttle:5,1');

Route::get('/lots', [LotApiController::class, 'index'])->middleware('throttle:60,1');
Route::get('/lots/{lot:slug}', [LotApiController::class, 'show'])->middleware('throttle:60,1');
Route::get('/lots/{lot:slug}/bids', [LotApiController::class, 'bids'])->middleware('throttle:60,1');

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::post('/lots/{lot:slug}/bids', [BidApiController::class, 'store']);

    Route::get('/me/watchlist', [WatchlistApiController::class, 'index']);
    Route::post('/lots/{lot:slug}/watchlist', [WatchlistApiController::class, 'toggle']);
});
