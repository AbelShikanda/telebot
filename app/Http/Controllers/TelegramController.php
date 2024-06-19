<?php

namespace App\Http\Controllers;

use App\Services\TelegramChatHandler;
use App\Services\TelegramService;
use App\Services\TelegramWebhookHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TelegramController extends Controller
{
    protected $telegramService;
    protected $webhookHandler;
    protected $chatHandler;

    public function __construct()
    {
        $this->telegramService = new TelegramService;
        $this->webhookHandler = new TelegramWebhookHandler;
        $this->chatHandler = new TelegramChatHandler;
    }

    public function getMe()
    {
        $response = $this->telegramService->getMe();
        return response()->json($response);
    }

    public function setWebhook()
    {
        $webhookUrl = url('https://4784-197-232-61-205.ngrok-free.app/webhook');
        $response = $this->telegramService->setWebhook($webhookUrl);

        return response()->json($response);
    }

    public function handleWebhook(Request $request)
    {
        $update = $this->webhookHandler->handleWebhook($request);

        if ($update->getMessage()) {
            $message = $update->getMessage();
            $messageId = $update->getMessageId();
            $chatId = $update->getChatId();
            $text = $update->getText();
            $chatType = $update->getChatType();
            $userId = $update->getUserId();
            $username = $update->getUsername();
            $firstName = $update->getFirstName();
            $lastName = $update->getLastName();
            $isReply = $update->getReplyToMessage();
            $joined_at = now();
            $warning_count = 0;
            $last_warning_at = null;

            switch ($chatType) {
                case 'private':
                    $this->chatHandler->handlePrivateChat($chatId, $text);
                    break;
                case 'group':
                case 'supergroup':
                    $this->chatHandler->handleGroupChat($chatId, $userId, $text, $isReply, $messageId);
                    break;
                case 'channel':
                    $this->chatHandler->handleChannel($chatId, $text);
                    break;
                default:
                    $this->chatHandler->handleUnknownChatType($chatId);
            }
        }
        return response('OK', 200);
    }
}
