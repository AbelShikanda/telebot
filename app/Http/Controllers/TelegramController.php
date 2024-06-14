<?php

namespace App\Http\Controllers;

use App\Models\Replies;
use App\Models\Telegram;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Carbon\Carbon;
use coinmarketcap\api\CoinMarketCap;
use Exception;

class TelegramController extends Controller
{

    protected $telegram;
    protected $chat_id;
    protected $username;
    protected $text;
    private $warnings = []; // In-memory storage for warnings; replace with database in production

    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
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

    public function handleRequest(Request $request)
    {
        $this->chat_id = $request['message']['chat']['id'];
        $this->username = $request['message']['from']['username'];
        $this->text = $request['message']['text'];

        switch ($this->text) {
            case '/start':
            case '/menu':
                $this->showMenu();
                break;
            case '/getGlobal':
                $this->showGlobal();
                break;
            case '/getTicker':
                $this->getTicker();
                break;
            case '/getCurrencyTicker':
                $this->getCurrencyTicker();
                break;
            default:
                $this->checkDatabase();
        }
    }

    public function showMenu($info = null)
    {
        $message = '';
        if ($info) {
            $message .= $info . chr(10);
        }
        $message .= '/menu' . chr(10);
        $message .= '/getGlobal' . chr(10);
        $message .= '/getTicker' . chr(10);
        $message .= '/getCurrencyTicker' . chr(10);

        $this->sendMessage($message);
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

    public function handle()
    {
        $update = $this->getUpdate();

        // Handle the command
        $chatId = $update->getMessage()->getChat()->getId();
        $text = "Hello, this is my custom command!";

        $this->replyWithMessage(compact('chatId', 'text'));
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

    // private function handleGroupChat($chatId, $text)
    // {
    //     // Get bot's username
    //     $bot = $this->telegram->getMe();
    //     $botUsername = $bot->getUsername();
    //     $botId = $bot->getId();

    //     // Extract text from the message
    //     $text = isset($message['text']) ? $message['text'] : '';

    //     // Check if the bot is mentioned in the message
    //     $botMentioned = strpos($text, '@' . $botUsername) !== false;

    //     // Check if the message is a reply to a bot's message
    //     $isReplyToBot = isset($message['reply_to_message']) &&
    //         isset($message['reply_to_message']['from']) &&
    //         $message['reply_to_message']['from']['id'] === $botId;

    //     // If bot is mentioned or the message is a reply to a bot's message
    //     if ($botMentioned || $isReplyToBot) {
    //         $reply = $this->generateGroupReply($text);
    //         $this->telegram->sendMessage([
    //             'chat_id' => $chatId,
    //             'text' => $reply
    //         ]);
    //     }

    // // Get bot's username
    // $bot = $this->telegram->getMe();
    // $botUsername = $bot->getUsername();

    // // Check if the bot is mentioned in the message
    // if (strpos($text, '@' . $botUsername) !== false) {
    //     $reply = "This is a group chat. You said: " . $text;
    //     $this->telegram->sendMessage([
    //         'chat_id' => $chatId,
    //         'text' => $reply
    //     ]);
    // }
    // }

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

        // Initialize warnings for the user if not already done
        if (!isset($this->warnings[$chatId])) {
            $this->warnings[$chatId] = [];
        }
        if (!isset($this->warnings[$chatId][$userId])) {
            $this->warnings[$chatId][$userId] = 0;
        }

        // Increment the warning count
        $this->warnings[$chatId][$userId]++;

        // Send a warning message to the user
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Warning: Unwanted content detected. This is warning #" . $this->warnings[$chatId][$userId]
        ]);

        // Delete the unwanted message
        $this->telegram->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $message->getMessageId()
        ]);

        // If the warning count exceeds the threshold, ban the user
        if ($this->warnings[$chatId][$userId] >= $warningThreshold) {
            // Ban the user
            $this->telegram->kickChatMember([
                'chat_id' => $chatId,
                'user_id' => $userId
            ]);

            // Reset the warning count after taking action
            unset($this->warnings[$chatId][$userId]);
        }
    }

    private function generateGroupReply($text)
    {
        $replies = Replies::all();

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
