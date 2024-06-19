<?php

namespace App\Services;

use App\Services\TelegramMessage;

use Illuminate\Http\Request;

class TelegramWebhookHandler

{
    protected $telegramMessage;

    public function __construct()
    {
        $this->telegramMessage = new TelegramMessage;
    }

    public function handleWebhook(Request $request)
    {
        // Decode JSON data from Telegram webhook
        $incomingMessages = json_decode($request->getContent(), true);

        // Set update data to TelegramMessage instance
        $this->telegramMessage->setUpdate($incomingMessages);

        // Return the TelegramMessage instance
        return $this->telegramMessage;
    }
}