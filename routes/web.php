<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\telebotController;

Route::get('/', function () {
    return view('welcome');
});

//+++++++++++++++++++++++++++++
//Auth::routes();
//+++++++++++++++++++++++++++++
Route::get('/', [telebotController::class, 'index']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('sendMessage', [telebotController::class, 'sendMessage']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::get('setWebhook', [telebotController::class, 'setWebhook']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
