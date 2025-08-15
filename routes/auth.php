<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
    Route::post('logout', 'logout')->middleware('auth')->name('logout');
    Route::get('profile', 'profile')->middleware('auth')->name('profile');
    Route::post('profile/update', 'updateProfile')->middleware('auth')->name('profile.update');
    Route::post('profile/change-password', 'changePassword')->middleware('auth')->name('profile.change-password');
    Route::get('/register', 'register')->name('auth.register');
    Route::post('/register', 'registerAction')->name('signup.action');

});

Route::get('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot.password');
Route::post('forgot-password', [ForgotPasswordController::class,'sendResetLink'])->name('send-rest-link');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::get('/custom-verify/{id}', [AuthController::class, 'verifyemail'])->name('custom.verify');



