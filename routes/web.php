<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\SettingController;
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


Auth::routes(['register' => false]);

// Routes for the Message model (resource routes first)
Route::resource('messages', MessageController::class);
Route::post('/message', [MessageController::class, 'getMessage']);
// Catch-all route for the root URL
Route::get('/', [MessageController::class, 'index'])->name('home');
// Routes for the Domain model
Route::resource('domains', DomainController::class);
// Routes for the Setting model
//Route::resource('settings', SettingController::class);
