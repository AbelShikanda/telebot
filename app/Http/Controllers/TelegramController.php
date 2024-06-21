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
        $webhookUrl = url('https://www.print.printshopeld.com');
        $response = $this->telegramService->setWebhook($webhookUrl);
        // curl -X POST "https://api.telegram.org/bot6496982679:AAELMYScxqkTvEFQaBjy5Ml1wULPF2lbYT8/setWebhook" -d "url=https://www.print.printshopeld.com/6496982679:AAELMYScxqkTvEFQaBjy5Ml1wULPF2lbYT8/webhook"

        return response()->json($response);
    }

    public function handleWebhook(Request $request)
    {
        $update = $this->webhookHandler->handleWebhook($request);

        if ($update->getMessage()) {
            $message = $update->getMessage();
            $messageId = $update->getMessageId();
            $chatId = $update->getChatId();
            $chatName = $update->getChatName();
            $text = $update->getText();
            $chatType = $update->getChatType();
            $userId = $update->getUserId();
            $username = $update->getUsername();
            $firstName = $update->getFirstName();
            $lastName = $update->getLastName();
            $isReply = $update->getReplyToMessage();

            // Log chat, user, and message details
            $this->telegramService->logDetails([
                'chat_id' => $chatId,
                'chat_type' => $chatType,
                'title' => $chatName,
                'user_id' => $userId,
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'message' => $message,
                'is_reply' => $isReply,
                'text' => $text,
            ]);

            switch ($chatType) {
                case 'private':
                    $this->chatHandler->handlePrivateChat($chatId, $text);
                    break;
                case 'group':
                case 'supergroup':
                    $this->chatHandler->handleGroupChat($chatId, $userId, $text, $isReply, $messageId, $message);
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
