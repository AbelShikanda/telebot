<?php

namespace App\Http\Controllers;

use App\Models\Replies;
use App\Models\GroupReplies;
use App\Services\UserService;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Illuminate\Support\Facades\DB;

class TelegramController extends Controller
{

    protected $telegram;
    protected $chat_id;
    protected $userService;

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->userService = new UserService();
    }

    public function getMe()
    {
        $response = $this->telegram->getMe();
        return $response;
    }

    public function setWebHook()
    {
        $url = 'https://www.print.printshopeld.com/' . env('TELEGRAM_BOT_TOKEN') . '/webhook';
        $response = $this->telegram->setWebhook(['url' => $url]);

        return $response == true ? redirect()->back() : dd($response);
    }

    protected function sendMessage($message, $parse_html = false)
    {
        $data = [
            'chat_id' => $this->chat_id,
            'text' => $message,
        ];

        if ($parse_html) $data['parse_mode'] = 'HTML';

        $this->telegram->sendMessage($data);
    }

    public function handleWebhook(Request $request)
    {
        $update = $this->telegram->getWebhookUpdates();

        // in the eveno of above beind depreciated
        // if ($request->isMethod('post')) {
        //     $update = json_decode($request->getContent(), true);

        if ($update->isType('message')) {
            $message = $update->getMessage();
            $chat = $message->getChat();
            $chatId = $chat->getId();
            $chatType = $chat->getType();
            $text = $message->getText();
            $user = $message->getFrom();
            $userId = $user->getId();
            $username = $user->getUsername();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $isAdmin = $this->checkIfAdmin($chatId, $userId);
            $media = $this->hasMedia($message); // Indicates if the message contains media (photo, video, etc.)
            $file = $this->getFileId($message); // Unique identifier for files (if applicable)
            $mediType = $this->getMediaType($message); // Type of media (photo, video, etc.)


            $userData = [
                'message' => $message,
                'message_id' => $message->getMessageId(),
                'chat' => $chat,
                'chat_id' => $chatId,
                'chat_type' => $chatType,
                'text' => $text,
                'user' => $user,
                'user_id' => $userId,
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'joined_at' => now(), // Assuming this is the first interaction
                'message_count' => 1, // Assuming initial message count is 1
                'warning_count' => 0, // Assuming initial message count is 1
                'is_admin' => $isAdmin, // Modify as needed
                'has_media' => $media, // Indicates if the message contains media (photo, video, etc.)
                'file_id' => $file, // Unique identifier for files (if applicable)
                'media_type' => $mediType, // Type of media (photo, video, etc.)
            ];

            $this->userService->logUser($userData);

            switch ($chatType) {
                case 'private':
                    $this->handlePrivateChat($chatId, $text);
                    break;
                case 'group':
                case 'supergroup':
                    $this->handleGroupChat($chatId, $text, $message);
                    break;
                case 'channel':
                    $this->handleChannel($chatId, $text);
                    break;
                default:
                    $this->handleUnknownChatType($chatId);
            }
        } else if ($update->isType('inline_query')) {
            $inlineQuery = $update->getInlineQuery();
            $queryId = $inlineQuery->getId();
            $queryText = $inlineQuery->getQuery();

            $results = [
                [
                    'type' => 'article',
                    'id' => 'unique-id-1',
                    'title' => 'Sample Result',
                    'input_message_content' => [
                        'message_text' => 'This is a sample response to the inline query'
                    ]
                ]
            ];

            // Telegram::answerInlineQuery([
            $this->telegram->answerInlineQuery([
                'inline_query_id' => $queryId,
                'results' => json_encode($results)
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    private function checkIfAdmin($chatId, $userId)
    {
        $admins = $this->telegram->getChatAdministrators(['chat_id' => $chatId]);
        foreach ($admins as $admin) {
            if ($admin->getUser()->getId() == $userId) {
                return true;
            }
        }
        return false;
    }

    private function getMediaType($message)
    {
        if ($message->getPhoto()) {
            return 'photo';
        } elseif ($message->getVideo()) {
            return 'video';
        } elseif ($message->getAudio()) {
            return 'audio';
        } elseif ($message->getVoice()) {
            return 'voice';
        } elseif ($message->getDocument()) {
            return 'document';
        } elseif ($message->getAnimation()) {
            return 'animation';
        } elseif ($message->getSticker()) {
            return 'sticker';
        } else {
            return null;
        }
    }

    private function getFileId($message)
    {
        if ($message->getPhoto()) {
            return $message->getPhoto()[0]->getFileId();
        } elseif ($message->getVideo()) {
            return $message->getVideo()->getFileId();
        } elseif ($message->getAudio()) {
            return $message->getAudio()->getFileId();
        } elseif ($message->getVoice()) {
            return $message->getVoice()->getFileId();
        } elseif ($message->getDocument()) {
            return $message->getDocument()->getFileId();
        } elseif ($message->getAnimation()) {
            return $message->getAnimation()->getFileId();
        } elseif ($message->getSticker()) {
            return $message->getSticker()->getFileId();
        } else {
            return null;
        }
    }

    private function hasMedia($message)
    {
        return $message->getPhoto() || $message->getVideo() || $message->getAudio() || $message->getVoice() || $message->getDocument() || $message->getAnimation() || $message->getSticker();
    }

    private function handlePrivateChat($chatId, $text)
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

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }

    private function handleGroupChat($chatId, $text, $message)
    {
        /// Get bot's username and ID
        $bot = $this->telegram->getMe();
        $botUsername = $bot->getUsername();
        $botId = $bot->getId();

        // Extract text from the message
        $text = $message->getText();

        // Define unwanted content keywords
        $unwantedKeywords = ['spam', 'unwanted', 'badword']; // Add your own keywords

        // Check if the message contains unwanted content
        $containsUnwantedContent = false;
        foreach ($unwantedKeywords as $keyword) {
            if (strpos(strtolower($text), $keyword) !== false) {
                $containsUnwantedContent = true;
                break;
            }
        }

        // Get user ID
        $userId = $message->getFrom()->getId();

        // If the message contains unwanted content, issue a warning
        if ($containsUnwantedContent) {
            $this->handleUnwantedContent($chatId, $userId, $message);
            return; // Exit after handling unwanted content
        }

        // Check if the bot is mentioned in the message
        $botMentioned = strpos($text, '@' . $botUsername) !== false;

        // Check if the message is a reply to a bot's message
        $replyToMessage = $message->getReplyToMessage();
        $isReplyToBot = $replyToMessage && $replyToMessage->getFrom()->getId() === $botId;

        // If bot is mentioned or the message is a reply to a bot's message
        if ($botMentioned || $isReplyToBot) {
            $reply = $this->generateGroupReply($text);
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $reply
            ]);
        }
    }

    private function handleUnwantedContent($chatId, $userId, $message)
    {
        $warningThreshold = 3; // Number of warnings before action is taken

        $warning = DB::table('telegram_users')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->first();

        if ($warning) {
            // Increment the warning count
            $warningCount = $warning->warning_count + 1;
            DB::table('telegram_users')
                ->where('chat_id', $chatId)
                ->where('user_id', $userId)
                ->update([
                    'warning_count' => $warningCount,
                    'last_warning_at' => now()
                ]);
        } else {
            // Initialize the warning count for the user
            $warningCount = 1;
            DB::table('telegram_users')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->update([
                'warning_count' => $warningCount,
                'last_warning_at' => now()
            ]);
        }

        // Send a warning message to the user
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Warning: Unwanted content detected. This is warning #" . $warningCount
        ]);

        // Delete the unwanted message
        $this->telegram->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $message->getMessageId()
        ]);

        // If the warning count exceeds the threshold, ban the user
        if ($warningCount >= $warningThreshold) {
            // Restrict the user from sending messages
            $this->restrictUser($chatId, $userId);

            // Reset the warning count after taking action
            DB::table('telegram_users')
                ->where('chat_id', $chatId)
                ->where('user_id', $userId)
                ->update(['warning_count' => 0]);
        }
    }

    private function generateGroupReply($text)
    {
        $replies = GroupReplies::all();

        $normalizedText = strtolower(trim($text));

        foreach ($replies as $dbReply) {
            $keyword = strtolower($dbReply->keyword);
            $response = $dbReply->response;

            if (strpos($normalizedText, $keyword) !== false) {
                return $response;
            }
        }

        // Default reply if no keyword is matched
        $defaultReply = "I didn't understand that. Can you please be more specific?";

        // Return default reply if no keywords match
        return $defaultReply;
    }

    private function restrictUser($chatId, $userId, $untilDate = null)
    {
        // If no untilDate is provided, set it to one month from now
        if (is_null($untilDate)) {
            $untilDate = now()->addMonth()->timestamp; // Add one month to the current date
        }

        $this->telegram->restrictChatMember([
            'chat_id' => $chatId,
            'user_id' => $userId,
            'permissions' => [
                'can_send_messages' => false,
                'can_send_media_messages' => false,
                'can_send_polls' => false,
                'can_send_other_messages' => false,
                'can_add_web_page_previews' => false,
                'can_change_info' => false,
                'can_invite_users' => false,
                'can_pin_messages' => false
            ],
            'until_date' => $untilDate // Optional: specify a date until the restriction should be applied
        ]);
    }

    private function handleChannel($chatId, $text)
    {
        $reply = "This is a channel. You said: " . $text;
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }

    private function handleUnknownChatType($chatId)
    {
        $reply = "This is an unknown type of chat.";
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }
}
