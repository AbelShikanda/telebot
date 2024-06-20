<?php

namespace App\Services;

use App\Models\GroupReplies;
use App\Models\Replies;
use App\Models\Spam;
use App\Models\TelegramUsers;

class TelegramChatHandler
{
    protected $telegramService;

    public function __construct()
    {
        $this->telegramService = new TelegramService;
    }

    public function handlePrivateChat($chatId, $text)
    {
        $replies = Replies::all();

        $normalizedText = strtolower(trim($text));

        $reply = "I did not understand your message please try asking one question at a time :)";

        foreach ($replies as $dbReply) {
            $keyword = strtolower($dbReply->keyword);
            $response = $dbReply->response;

            if (strpos($normalizedText, $keyword) !== false) {
                $reply = $response;
                break;
            }
        }

        $this->telegramService->sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }

    public function handleGroupChat($chatId, $userId, $text, $isReply, $messageId, $message)
    {
        /// Get bot's username and ID
        $botUsername = env('TELEGRAM_BOT_USERNAME');
        $botId = env('TELEGRAM_BOT_ID');

        // Extract text from the message
        // $text = $message->getText();

        // Define unwanted content keywords
        $unwantedKeywords = Spam::pluck('keywords')->toArray();

        // Check if the message contains unwanted content
        $containsUnwantedContent = false;
        foreach ($unwantedKeywords as $keyword) {
            if (strpos(strtolower($text), $keyword) !== false) {
                $containsUnwantedContent = true;
                break;
            }
        }

        // // Get user ID
        // $userId = $message->getFrom()->getId();

        // If the message contains unwanted content, issue a warning
        if ($containsUnwantedContent) {
            $this->handleUnwantedContent($chatId, $userId, $messageId);
            return; // Exit after handling unwanted content
        }

        // Check if the bot is mentioned in the message
        $botMentioned = strpos($text, '@' . $botUsername) !== false;

        if ($isReply !== null) {
            // Process the reply_to_message
            $replyToMessageId = $isReply['message_id'];
            // Other processing based on the reply_to_message

            // Check if the message is a reply to a bot's message
            $isReplyToBot = $replyToMessageId === $botId;
            $reply = $this->generateGroupReply($text);
            $groupreply = $reply['response'];
            $defaultreply = $reply['defaultResponse'];
            $this->telegramService->sendMessage([
                'chat_id' => $chatId,
                'text' => $groupreply,
                'reply_to_message_id' => $message['reply_to_message']['message_id'] ?? '',
            ]);
            $this->telegramService->sendMessage([
                'chat_id' => $userId,
                'text' => $defaultreply,
            ]);
        }
        // If bot is mentioned or the message is a reply to a bot's message
        if ($botMentioned) {
            $reply = $this->generateGroupReply($text);

            // dd($userId);
            $groupreply = $reply['response'];
            $defaultreply = $reply['defaultResponse'];
            $this->telegramService->sendMessage([
                'chat_id' => $chatId,
                'text' => $groupreply,
                'reply_to_message_id' => $message['reply_to_message']['message_id'] ?? '',
            ]);
            $this->telegramService->sendMessage([
                'chat_id' => $userId,
                'text' => $defaultreply,
                'reply_to_message_id' => $message['reply_to_message']['message_id'] ?? '',
            ]);
        }
    }

    private function handleUnwantedContent($chatId, $userId, $messageId)
    {
        $warningThreshold = 30; // Number of warnings before action is taken

        $user = TelegramUsers::where('user_id', $userId)->first();

        if ($user) {
            $user->warning_count += 1;
            $user->last_warning_at = now();
        } else {
            $user->warning_count = 1;
        }
        $user->save();

        $warningText = "Warning: Unwanted content detected. This is warning #" . $user->warning_count;

        $this->telegramService->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $messageId
        ]);

        $this->telegramService->sendMessage([
            'chat_id' => $chatId,
            'text' => $warningText,
        ]);

        // If the warning count exceeds the threshold, ban the user
        if ($user && $user->warning_count >= $warningThreshold) {
            // Restrict the user from sending messages
            $this->restrictUser($chatId, $userId);

            // Reset the warning count after taking action
            // Initialize the warning count for the user
            $user->warning_count = 0;
            $user->save();
        }
    }

    private function restrictUser($chatId, $userId, $untilDate = null)
    {
        // If no untilDate is provided, set it to one month from now
        if (is_null($untilDate)) {
            $untilDate = now()->addMonth()->timestamp; // Add one month to the current date
        }

        $this->telegramService->restrictChatMember([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'permissions' => [
                'can_send_messages' => false,
                'can_send_media_messages' => false,
                'can_send_other_messages' => false,
                'can_add_web_page_previews' => false,
                'can_change_info' => false,
                'can_invite_users' => false,
                'can_pin_messages' => false
            ],
            'until_date' => $untilDate // Optional: specify a date until the restriction should be applied
        ]);
    }

    private function generateGroupReply($text)
    {
        // $replies = GroupReplies::all();
        $replies = GroupReplies::all();

        $normalizedText = strtolower(trim($text));

        foreach ($replies as $dbReply) {
            $keyword = strtolower($dbReply->keyword);
            $response = $dbReply->response;
            $defaultResponse = $dbReply->default_response;

            if (strpos($normalizedText, $keyword) !== false) {
                return [
                    'response' => $response,
                    'defaultResponse' => $defaultResponse
                ];
            }
        }

        // Default reply if no keyword is matched
        $defaultReply = "I didn't understand that. Can you please be more specific?";
        $defaultPrivateReply = "I am realy sorry, so you mind repeating the quw=estion?";

        // Always return an array
        return [
            'response' => $defaultReply,
            'defaultResponse' => $defaultPrivateReply
        ];
    }

    public function handleChannel($chatId, $text)
    {
        // Implement your logic for handling channel messages
    }

    public function handleUnknownChatType($chatId)
    {
        // Implement your logic for handling unknown chat types
    }
}
