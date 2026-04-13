<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TicketController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('tickets', TicketController::class);