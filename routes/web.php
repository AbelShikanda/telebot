<?php

use App\Http\Controllers\GroupLinksController;
use App\Http\Controllers\GroupRepliesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\SpamController;
use App\Http\Controllers\telebotController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

//+++++++++++++++++++++++++++++
// Auth::routes(['verify' => true]);
Auth::routes();
//+++++++++++++++++++++++++++++
Route::get('/home', [HomeController::class, 'index'])->name('home');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::resource('replies', RepliesController::class);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::resource('groupreplies', GroupRepliesController::class);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::resource('grouplinks', GroupLinksController::class);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::resource('posts', PostsController::class);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::resource('spam', SpamController::class);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/messages', [telebotController::class, 'teleMessages'])->name('teleMessages');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/messages/view/{id}', [telebotController::class, 'showTeleMessages'])->name('showTeleMessages');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++










// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('/getMe', [TelegramController::class, 'getMe'])->name('getMe');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('set-webhook', [TelegramController::class, 'setWebHook'])->name('setWebHook');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::post(env('TELEGRAM_BOT_TOKEN') . '/webhook', [TelegramController::class, 'handleWebhook'])->name('handleWebhook');
// Route::post('/webhook', [TelegramController::class, 'handleWebhook'])->name('handleWebhook');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/handle', [TelegramController::class, 'handleRequest'])->name('handleRequest');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::post('/post/telegram', [TelegramController::class, 'postToGroup'])->name('postToGroup');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/show/menu', [TelegramController::class, 'showMenu'])->name('showMenu');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/send/message', [TelegramController::class, 'sendMessage'])->name('sendMessage');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/handle', [TelegramController::class, 'handle'])->name('handle');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/handle/webhook', [TelegramController::class, 'handleWebhook'])->name('handleWebhook');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/private/chat', [TelegramController::class, 'handlePrivateChat'])->name('handlePrivateChat');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/group/chat', [TelegramController::class, 'handleGroupChat'])->name('handleGroupChat');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/channel', [TelegramController::class, 'handleChannel'])->name('handleChannel');
// // //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('/telegram/unknown/chat/type', [TelegramController::class, 'handleUnknownChatType'])->name('handleUnknownChatType');
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('sendPoll', [App\Http\Controllers\TelegramController::class, 'sendPoll']);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Route::get('telegram-message-webhook', [App\Http\Controllers\TelegramController::class, 'telegram_webhook']);
// //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++












