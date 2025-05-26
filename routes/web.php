<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VideoController::class, 'index'])->name('home');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/feed', [VideoController::class, 'feed'])->name('feed');
    Route::get('/upload', [VideoController::class, 'showUploadForm'])->name('upload.form');
    Route::post('/upload', [VideoController::class, 'upload'])->name('videos.upload');
    Route::post('/videos/{videoId}/purchase', [VideoController::class, 'purchase'])->name('videos.purchase');
    Route::post('/videos/{videoId}/comment', [VideoController::class, 'comment'])->name('videos.comment');
    Route::post('/videos/{videoId}/gift', [VideoController::class, 'gift'])->name('videos.gift');
    Route::post('/videos/{videoId}/like', [VideoController::class, 'like'])->name('videos.like');
    Route::get('/videos/{videoId}', [VideoController::class, 'show'])->name('videos.show');
});