<?php

use App\Http\Controllers\GroupRepliesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RepliesController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\telebotController;
use App\Http\Controllers\TelegramController;

Route::get('/', function () {
    return view('welcome');
});

//+++++++++++++++++++++++++++++
//Auth::routes();
//+++++++++++++++++++++++++++++
Route::get('/home', [HomeController::class, 'index'])->name('home');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/get-me', [TelegramController::class, 'getMe'])->name('getMe');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/set/hook', [TelegramController::class, 'setWebHook'])->name('setWebHook');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::post(env('TELEGRAM_BOT_TOKEN') . '/webhook', [TelegramController::class, 'handleWebhook'])->name('handleWebhook');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/handle', [TelegramController::class, 'handleRequest'])->name('handleRequest');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/show/menu', [TelegramController::class, 'showMenu'])->name('showMenu');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/send/message', [TelegramController::class, 'sendMessage'])->name('sendMessage');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/handle', [TelegramController::class, 'handle'])->name('handle');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/handle/webhook', [TelegramController::class, 'handleWebhook'])->name('handleWebhook');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/private/chat', [TelegramController::class, 'handlePrivateChat'])->name('handlePrivateChat');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/group/chat', [TelegramController::class, 'handleGroupChat'])->name('handleGroupChat');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/channel', [TelegramController::class, 'handleChannel'])->name('handleChannel');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/telegram/unknown/chat/type', [TelegramController::class, 'handleUnknownChatType'])->name('handleUnknownChatType');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::resource('replies', RepliesController::class);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::resource('groupreplies', GroupRepliesController::class);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendPoll', [App\Http\Controllers\TelegramController::class, 'sendPoll']);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('telegram-message-webhook', [App\Http\Controllers\TelegramController::class, 'telegram_webhook']);
