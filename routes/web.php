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
use App\Http\Controllers\GoogleDriveOAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhotographController;
use App\Http\Controllers\ProfileController;

// Basic
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [BrowseController::class, 'index'])->name('browse');
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
Route::post('/profile/icon', [ProfileController::class, 'uploadProfileIcon'])->name('profile.upload-icon');
Route::post('/profile/picture', [ProfileController::class, 'uploadProfilePicture'])->name('profile.upload-picture');
Route::post('/profile/googledrive', [ProfileController::class, 'updateGoogleDrive'])->name('profile.update-googledrive');

// Photo Management
Route::get('/photograph/new', [PhotographController::class, 'showNewPhotographForm'])->name('photograph.new');
Route::post('/photograph', [PhotographController::class, 'create'])->name('photograph.create');
Route::get('/photograph/{photo}', [PhotographController::class, 'showManagePhotographForm'])->middleware('is-owner:photo')->name('photograph.manage');
Route::post('/photograph/{photo}/upload-edit', [PhotographController::class, 'uploadEdit'])->middleware('is-owner:photo')->name('photograph.upload-edit');
Route::get('/photograph/{photo}/download', [PhotographController::class, 'downloadPhotoEdit'])->middleware('is-owner:photo')->name('photograph.download');

// Google Drive
Route::get('/googledrive/oauth', [GoogleDriveOAuthController::class, 'tryOAuth'])->name('googledrive');
Route::get('/googledrive/oauth/redirect', [GoogleDriveOAuthController::class, 'handleOAuthResponse'])->name('googledrive.redirect');
