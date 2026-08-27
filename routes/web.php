<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// 網站首頁：保留 Laravel 原本的 welcome 頁面。
Route::get('/', function () {
    // 對應 resources/views/welcome.blade.php。
    return view('welcome');
});

// 練習頁面：http://localhost:8080/hello。
Route::get('/hello', function () {
    // 對應 resources/views/hello.blade.php。
    return view('hello');
});

// guest 中介層只讓「尚未登入」的訪客使用以下路由。
Route::middleware('guest')->group(function () {
    // GET /login：呼叫 create 方法顯示登入表單，路由名稱為 login。
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    // POST /login：呼叫 store 方法處理表單，路由名稱為 login.store。
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// auth 中介層要求使用者必須先成功登入。
Route::middleware('auth')->group(function () {
    // GET /dashboard：顯示登入後才能看到的管理頁面。
    Route::get('/dashboard', function () {
        // 對應 resources/views/dashboard.blade.php。
        return view('dashboard');
    })->name('dashboard');

    // POST /logout：呼叫 destroy 方法登出，路由名稱為 logout。
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
