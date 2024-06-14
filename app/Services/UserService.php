<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UserService
{
    public function logUser($userData)
    {
        // Log chat
        $this->logChat($userData['chat_id'], $userData['chat_type']);

        // Log user
        $this->logUserDetails($userData);

        // Log message
        $this->logMessage($userData);
    }

    private function logChat($chatId, $chatType)
    {
        DB::table('telegram_chats')->updateOrInsert(
            ['chat_id' => $chatId],
            [
                'type' => $chatType, 
                'updated_at' => now()
            ]
        );
    }

    private function logUserDetails($userData)
    {
        DB::table('telegram_users')->updateOrInsert(
            [
                'chat_id' => $userData['chat_id'], 
                'user_id' => $userData['user_id'],
            ],
            [
                'username' => $userData['username'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'warning_count' => $userData['warning_count'],
                'last_warning_at' => $userData['last_warning_at'],
                'joined_at' => $userData['joined_at'],
                'message_count' => DB::raw('message_count + 1'), // Increment message count
                'is_admin' => $userData['is_admin'],
                'updated_at' => now()
            ]
        );
    }

    private function logMessage($userData)
    {
        $message = $userData['message'];
        DB::table('telegram_messages')->insert([
            'message_id' => $message->getMessageId(),
            'chat_id' => $userData['chat_id'],
            'user_id' => $userData['user_id'],
            'text' => $userData['text'],
            'caption' => $message->getCaption(), // Caption for media (if applicable)
            'media_type' => $userData['has_media'], // Type of media (photo, video, etc.)
            'file_id' => $userData['file_id'], // Unique identifier for files (if applicable)
            'is_forwarded' => $message->getForwardFrom() ? true : false, // Indicates if the message is forwarded
            'is_reply' => $message->getReplyToMessage() ? true : false, // Indicates if the message is a reply
            'reply_to_message_id' => $message->getReplyToMessage() ? $message->getReplyToMessage()->getMessageId() : null, // Message ID to which this message replies
            'media_type' => $userData['media_type'], // Indicates if the message contains media (photo, video, etc.)
            'has_document' => $message->getDocument() ? true : false, // Indicates if the message contains a document
            'has_location' => $message->getLocation() ? true : false, // Indicates if the message contains a location
        ]);
    }
}

