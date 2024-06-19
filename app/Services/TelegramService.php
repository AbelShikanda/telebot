<?php

namespace App\Services;

use Exception;

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

    protected function sendRequest($url, $params = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
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
}
