<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController;

Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('api.search.autocomplete');
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
