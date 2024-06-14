<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\TelegramChats;
use App\Models\TelegramMessages;
use App\Models\TelegramUsers;

class UserService
{
    public function logUser($userData)
    {
        // Log chat and get its ID
        $chatId = $this->logChat($userData['chat_id'], $userData['chat_type']);
        
        // Update the chat_id in userData with the actual chat ID from the database
        $userData['chat_id'] = $chatId;

        // Log user details
        $this->logUserDetails($userData);

        // Log message details
        $this->logMessage($userData);
    }

    private function logChat($chatId, $chatType)
    {
        // Update or insert the chat record and get the chat instance
        $chat = TelegramChats::updateOrCreate(
            ['chat_id' => $chatId],
            [
                'type' => $chatType,
                'last_update' => now()
            ]
        );

        // Return the chat ID
        return $chat->id;
    }

    private function logUserDetails($userData)
    {
        // First, attempt to find the user by chat_id and user_id
        $user = TelegramUsers::where('chat_id', $userData['chat_id'])
            ->where('user_id', $userData['user_id'])
            ->first();

        // If the user exists, update the user record
        if ($user) {
            $user->update([
                'username' => $userData['username'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'warning_count' => $userData['warning_count'],
                'last_warning_at' => $userData['last_warning_at'],
                'joined_at' => $userData['joined_at'],
                'message_count' => DB::raw('message_count + 1'), // Increment message count
                'is_admin' => $userData['is_admin'],
                'updated_at' => now(),
            ]);
        } else {
            // If the user does not exist, create a new user record
            TelegramUsers::create([
                'chat_id' => $userData['chat_id'],
                'user_id' => $userData['user_id'],
                'username' => $userData['username'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'warning_count' => $userData['warning_count'],
                'last_warning_at' => $userData['last_warning_at'],
                'joined_at' => $userData['joined_at'],
                'message_count' => 1, // Initialize message count
                'is_admin' => $userData['is_admin'],
            ]);
        }
    }

    private function logMessage($userData)
    {
        $message = $userData['message'];

        // Insert the message record
        TelegramMessages::create([
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
            'has_document' => $message->getDocument() ? true : false, // Indicates if the message contains a document
            'has_location' => $message->getLocation() ? true : false, // Indicates if the message contains a location
        ]);
    }
}
