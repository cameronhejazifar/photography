<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [BrowseController::class, 'index'])->name('browse');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
Route::post('/profile/icon', [ProfileController::class, 'uploadProfileIcon'])->name('profile.upload-icon');
Route::get('/profile/{user}/icon', [ProfileController::class, 'renderProfileIcon'])->name('profile.icon');
Route::post('/profile/picture', [ProfileController::class, 'uploadProfilePicture'])->name('profile.upload-picture');
Route::get('/profile/{user}/picture', [ProfileController::class, 'renderProfilePicture'])->name('profile.picture');
