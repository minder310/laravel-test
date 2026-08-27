<?php

use Illuminate\Support\Facades\Route;

// /代表原始網址
Route::get('/', function () {
    return view('welcome');
});
// /hello 代表原始網址+/hello
Route::get('/hello', function () {
    return view('hello');
});
