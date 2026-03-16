<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolaController;
use App\Http\Controllers\DebugController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/hola', [HolaController::class, 'hola']);

Route::get('/debug', function () {
    return view('debug');
});
