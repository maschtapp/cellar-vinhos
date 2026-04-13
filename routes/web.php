<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/categories', function () {
    return view('categories.index');
});

Route::get('/tickets', function () {
    return view('tickets.index');
});