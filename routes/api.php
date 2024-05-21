<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::resource('quotes', QuoteController::class)->only([
        'index', 'store',
    ]);
});
