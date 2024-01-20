<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SettingsController;

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
//Route::resource('messages', MessageController::class);
Route::get('/', [MessageController::class, 'index'])->name('home');
Route::get('/prompt', [MessageController::class, 'prompt'])->name('prompt');
Route::get('/get-count', [MessageController::class, 'getCountData'])->name('get-count');

//Message Actions
Route::post('/replymonitor', [ReplyController::class, 'getMessage']); //details for the replied Message]
Route::post('/release', [ReplyController::class, 'release']); //details for the replied Message]

Route::post('/message', [MessageController::class, 'getMessage']); //details for the message]
Route::post('/redirect', [MessageController::class, 'redirect_send'])->name('redirect'); //Redirect the message
Route::post('/reply', [MessageController::class, 'reply_send'])->name('reply'); //Redirect the message
Route::post('/modifi-labels', [MessageController::class, 'modifiLabels'])->name('labels.modify'); //Redirect the message

Route::post('/multiple-action', [MessageController::class, 'multipleAction']); //Redirect the message
Route::post('/delete-all', [MessageController::class, 'deleteAll']); //Redirect the message

Route::get('/message/{id}/info', [MessageController::class, 'info'])->name('info');
Route::get('/message/{id}/spam', [MessageController::class, 'makeSpam'])->name('message.spam');
Route::get('/message/{id}/notspam', [MessageController::class, 'notSpam'])->name('message.notspam');
Route::get('/message/{id}/local', [MessageController::class, 'makelocal'])->name('message.local');
Route::get('/message/{id}/notlocal', [MessageController::class, 'notLocal'])->name('message.notlocal');
Route::get('/message/{id}/redirect', [MessageController::class, 'redirect'])->name('message.redirect');
Route::get('/message/{id}/delete', [MessageController::class, 'destroy'])->name('message.delete');
Route::get('/message/{id}/reply', [MessageController::class, 'reply'])->name('message.reply');


Route::get('/messages/{status?}', [MessageController::class, 'index'])->name('messages.index');
Route::get('/replies/{status?}', [ReplyController::class, 'index'])->name('messages.replies');
// Catch-all route for the root URL
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::get('/auth-logout', [SettingsController::class, 'AuthLogout']);
Route::post('/settings/update', [SettingsController::class, 'UpdateSettings']);

// Routes for the Domain model
Route::resource('domains', DomainController::class);
// Routes for the Setting model
//Route::resource('settings', SettingController::class);
