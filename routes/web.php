<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolaController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/hola', [HolaController::class, 'hola']);
