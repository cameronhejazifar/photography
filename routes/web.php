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
use App\Http\Controllers\FlickrController;
use App\Http\Controllers\GoogleDriveController;
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
Route::post('/photograph/{photo}', [PhotographController::class, 'update'])->middleware('is-owner:photo')->name('photograph.update');
Route::get('/photograph/{photo}', [PhotographController::class, 'showManagePhotographForm'])->middleware('is-owner:photo')->name('photograph.manage');
Route::post('/photograph/{photo}/upload-edit', [PhotographController::class, 'uploadEdit'])->middleware('is-owner:photo')->name('photograph.upload-edit');
Route::post('/photograph/{photo}/upload-other', [PhotographController::class, 'uploadOther'])->middleware('is-owner:photo')->name('photograph.upload-other');
Route::get('/photograph/{photo}/download', [PhotographController::class, 'downloadPhotoEdit'])->middleware('is-owner:photo')->name('photograph.download');
Route::get('/photograph/download-other/{file}', [PhotographController::class, 'downloadPhotoOtherFile'])->middleware('is-owner:file')->name('photograph.download-other');
Route::post('/photograph/update-other/{file}', [PhotographController::class, 'updateOther'])->middleware('is-owner:file')->name('photograph.update-other');
Route::get('/photograph/delete-other/{file}', [PhotographController::class, 'deleteOther'])->middleware('is-owner:file')->name('photograph.delete-other');

// Google Drive
Route::get('/googledrive/oauth', [GoogleDriveController::class, 'tryOAuth'])->name('googledrive');
Route::get('/googledrive/oauth/redirect', [GoogleDriveController::class, 'handleOAuthResponse'])->name('googledrive.redirect');

// Flickr
Route::get('/flickr/authenticate', [FlickrController::class, 'authenticate'])->name('flickr');
Route::get('/flickr/authenticate/callback', [FlickrController::class, 'oauthCallback'])->name('flickr.oauth-callback');
