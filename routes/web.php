<?php

use App\Http\Controllers\AiGenerate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\GMessageController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\SenderController;
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
Route::get('/', [MessageController::class, 'index'])->name('home');
//Ajax routes

Route::post('/ai', [AiGenerate::class, 'generate'])->name('ai'); //AI
//Message Actions
Route::get('/get-count', function () {
    $messageController = new MessageController();
    $messageCount = $messageController->getCountAll();

    $gMessageController = new GMessageController();
    $gMessageCount = $gMessageController->getCountAll();

    return response()->json([
        'edesk' => $messageCount,
        'gdesk' => $gMessageCount
    ]);
});

//Ends Ajax Routes

Route::group(['prefix' => 'edesk'], function () {
    // Routes for the Message model (resource routes first)
    Route::get('/', [MessageController::class, 'index'])->name('home');
    Route::get('/prompt', [MessageController::class, 'prompt'])->name('prompt');

    Route::get('/messages/{status?}', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/replies/{status?}', [ReplyController::class, 'index'])->name('messages.replies');
    // Catch-all route for the root URL

    //Message Actions  
    Route::post('/replymonitor', [ReplyController::class, 'getMessage']); //details for the replied Message]
    Route::post('/message', [MessageController::class, 'getMessage']); //details for the message]

    Route::post('/release', [ReplyController::class, 'release']); //details for the replied Message]
    Route::post('/redirect', [MessageController::class, 'redirect_send'])->name('redirect'); //Redirect the message
    Route::post('/reply', [MessageController::class, 'reply_send'])->name('reply'); //Redirect the message
    Route::post('/modifi-labels', [MessageController::class, 'modifiLabels'])->name('labels.modify'); //Redirect the message

    Route::post('/multiple-action', [MessageController::class, 'multipleAction']); //Redirect the message
    //Delete All
    Route::post('/delete-all', [MessageController::class, 'deleteAll']); //Redirect the message
    //Single Actions
    Route::get('/message/{id}/info', [MessageController::class, 'info'])->name('info');
    Route::get('/message/{id}/spam', [MessageController::class, 'makeSpam'])->name('message.spam');
    Route::get('/message/{id}/notspam', [MessageController::class, 'notSpam'])->name('message.notspam');
    Route::get('/message/{id}/local', [MessageController::class, 'makelocal'])->name('message.local');
    Route::get('/message/{id}/notlocal', [MessageController::class, 'notLocal'])->name('message.notlocal');
    Route::get('/message/{id}/redirect', [MessageController::class, 'redirect'])->name('message.redirect');
    Route::get('/message/{id}/delete', [MessageController::class, 'destroy'])->name('message.delete');
    Route::get('/message/{id}/reply', [MessageController::class, 'reply'])->name('message.reply');
});


Route::group(['prefix' => 'gdesk'], function () {
    //Message Actions
    Route::get('/messages/{status?}', [GMessageController::class, 'index'])->name('gdesk.index');

    Route::post('/message', [GMessageController::class, 'getMessage']); //details for the message]

    Route::post('/release', [ReplyController::class, 'release']); //details for the replied Message]
    Route::post('/redirect', [GMessageController::class, 'redirect_send'])->name('gredirect'); //Redirect the message
    Route::post('/reply', [GMessageController::class, 'reply_send'])->name('greply'); //Redirect the message
    Route::post('/modifi-labels', [GMessageController::class, 'modifiLabels'])->name('labels.modify'); //Redirect the message

    Route::post('/multiple-action', [GMessageController::class, 'multipleAction']); //Redirect the message
    //Delete All
    Route::post('/delete-all', [GMessageController::class, 'deleteAll']); //Redirect the message



    Route::get('/message/{id}/reply', [GMessageController::class, 'reply'])->name('gdesk.reply');
    Route::get('/message/{id}/redirect', [GMessageController::class, 'redirect'])->name('gdesk.redirect');
    Route::get('/message/{id}/delete', [GMessageController::class, 'destroy'])->name('gdesk.delete');
});

Route::group(['prefix' => 'settings'], function () {
    Route::get('/general', [SettingsController::class, 'index'])->name('general');
    Route::get('/auth-logout', [SettingsController::class, 'AuthLogout']);
    Route::post('/settings/update', [SettingsController::class, 'UpdateSettings']);
    // Routes for the Domain model
    Route::resource('domains', DomainController::class);

    //Sender Routes

    // Show all senders
    Route::get('/senders', [SenderController::class, 'index'])->name('senders.index');
    Route::get('/senders/{sender}/check', [SenderController::class, 'connectionCheck'])->name('senders.check');
    // Show the form for creating a new sender
    Route::get('/senders/create', [SenderController::class, 'create'])->name('senders.create');
    // Store a newly created sender in storage
    Route::post('/senders', [SenderController::class, 'store'])->name('senders.store');
    // Show the form for editing the specified sender
    Route::get('/senders/{sender}/edit', [SenderController::class, 'edit'])->name('senders.edit');
    // Update the specified sender in storage
    Route::put('/senders/{sender}', [SenderController::class, 'update'])->name('senders.update');
    // Remove the specified sender from storage
    Route::delete('/senders/{sender}', [SenderController::class, 'destroy'])->name('senders.destroy');
});

// Routes for the Setting model
//Route::resource('settings', SettingController::class);
