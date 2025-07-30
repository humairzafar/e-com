<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;


Route::get('/', function () {
    return view('welcome');
});



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/category', [CategoryController::class, 'select'])->name('select');
Route::post('/category', [CategoryController::class, 'store'])->name('store');
Route::post('/category/edit', [CategoryController::class, 'editCategory']);


Route::get('/home', function () {
    return view('home');
})->middleware('auth');

Route::get('/contact', function(){
    return view('contact');
});

