<?php

namespace App\Services;

use App\Models\TelegramChats;
use App\Models\TelegramMessages;
use App\Models\TelegramUsers;
use Exception;
use Illuminate\Support\Facades\DB;

class TelegramService
{
    protected $telegramToken;
    protected $telegramApiUrl;

    public function __construct()
    {
        $this->telegramToken = env('TELEGRAM_BOT_TOKEN');
        $this->telegramApiUrl = env('API_URL') . $this->telegramToken . '/';
    }

    public function getMe()
    {
        $url = $this->telegramApiUrl . 'getMe';
        return $this->sendRequest($url, []);
        

    }

    public function setWebhook($webhookUrl)
    {
        $url = $this->telegramApiUrl . 'setWebhook';
        return $this->sendRequest($url, ['url' => $webhookUrl]);
    }

    public function sendMessage(array $params)
    {
        $url = $this->telegramApiUrl . 'sendMessage';

        $defaultParams = [
            'chat_id' => '',
            'text' => '',
            'reply_to_message_id' => '',
            'photo' => '',
            'caption' => '',
            // Add more parameters as needed
        ];

        $params = array_merge($defaultParams, $params);

        return $this->sendRequest($url, $params);
    }

    public function deleteMessage(array $params)
    {
        $url = $this->telegramApiUrl . 'deleteMessage';

        $defaultParams = [
            'chat_id' => '',
            'message_id' => '',
            // Add more parameters as needed
        ];

        $params = array_merge($defaultParams, $params);

        return $this->sendRequest($url, $params);
    }

    public function sendPhoto(array $params)
    {
        $url = $this->telegramApiUrl . 'sendPhoto';

        $defaultParams = [
            'chat_id' => '',
            'photo' => '',
            'caption' => '',
            // Add more parameters as needed
        ];

        $params = array_merge($defaultParams, $params);

        return $this->sendRequest($url, $params);
    }

    public function restrictChatMember(array $params)
    {
        $url = $this->telegramApiUrl . 'restrictChatMember';

        $params = [
            'chat_id' => '',
            'user_id' => '',
            'permissions' => [],
            'until_date' => '',
        ];

        return $this->sendRequest($url, $params);
    }

    protected function sendRequest($url, $params = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    // protected function sendRequest($url, $params = [])
    // {
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: multipart/form-data'
    //     ]);

    //     $response = curl_exec($ch);

    //     // if (curl_errno($ch)) {
    //     //     $error_msg = curl_error($ch);
    //     //     curl_close($ch);
    //     //     throw new Exception("cURL Error: $error_msg");
    //     // }

    //     // $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     // if ($http_code >= 400) {
    //     //     curl_close($ch);
    //     //     throw new Exception("HTTP Error: Received status code $http_code. Response: $response");
    //     // }

    //     curl_close($ch);

    //     return $response;
    // }

    public function logDetails(array $params)
    {
        $chatId = $params['chat_id'];
        $chatType = $params['chat_type'];
        $chatName = $params['title'];
        $userId = $params['user_id'];
        $username = $params['username'];
        $firstName = $params['first_name'];
        $lastName = $params['last_name'];
        $message = $params['message'];
        $isReply = $params['is_reply'];
        $text = $params['text'];

        $chat = $this->logChat($chatId, $chatType, $chatName);
        $user = $this->logUser($userId, $username, $firstName, $lastName);
        $this->logMessage($message, $text, $chat->id, $user->id, $isReply);
    }

    public function logChat($chatId, $chatType, $chatName)
    {
        $chat = TelegramChats::where('chat_id', $chatId)->first();
        if (!$chat) {
            $chat = TelegramChats::create([
                'chat_id' => $chatId,
                'type' => $chatType,
                'title' => $chatName,
            ]);
        }
        return $chat;
    }

    public function logUser($userId, $username, $firstName, $lastName)
    {
        $user = TelegramUsers::where('user_id', $userId)->first();
        if ($user) {
            $user->update([
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'message_count' => DB::raw('message_count + 1'),
            ]);
        } else {
            $user = TelegramUsers::create([
                'user_id' => $userId,
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'warning_count' => 0,
                'last_warning_at' => null,
                'joined_at' => now(),
                'message_count' => 1,
            ]);
        }
        return $user;
    }

    public function logMessage($message, $text, $chatId, $userId, $isReply)
    {
        $existingMessage = TelegramMessages::where('message_id', $message['message_id'])->first();
        if ($existingMessage) {
            $existingMessage->update([
                'text' => $text,
            ]);
        } else {
            // $data = [
            //     // 'message_id' => $message['message_id'],
            //     // 'chat_id' => $message['chat']['id'],
            //     // 'user_id' => $userId,
            //     // 'text' => $message['text'],
            //     // 'is_reply' => $message['reply_to_message'],
            //     // 'reply_to_message_id' => $message['message_id'],
            //     'message_id' => $message['message_id'],
            //     'chat_id' => $chatId,
            //     'user_id' => $userId,
            //     'text' => $text,
            //     'is_reply' => isset($message['reply_to_message']) && $message['reply_to_message'] !== null ? true : false,
            //     'reply_to_message_id' => $message['message_id'],
            // ];
            // dd($data);
            TelegramMessages::create([
                'message_id' => $message['message_id'],
                'chat_id' => $chatId,
                'user_id' => $userId,
                'text' => $text,
                'is_reply' => isset($message['reply_to_message']) && $message['reply_to_message'] !== null ? true : false,
                'reply_to_message_id' => $message['message_id'],
            ]);
        }
    }
}
