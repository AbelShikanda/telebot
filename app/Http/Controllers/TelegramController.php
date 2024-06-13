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
                    $this->handleGroupChat($chatId, $text);
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

    private function handleGroupChat($chatId, $text)
    {
        // Get bot's username
        $bot = $this->telegram->getMe();
        $botUsername = $bot->getUsername();

        // Ensure $text is an object and extract text and entities// Ensure $message is an object and extract text and entities
        $text = isset($message['text']) ? $message['text'] : '';
        $entities = isset($message['entities']) ? $message['entities'] : [];

        // Check if the bot is mentioned in the text entities
        $botMentioned = false;
        if ($entities) {
            foreach ($entities as $entity) {
                if ($entity['type'] === 'mention' && substr($text, $entity['offset'], $entity['length']) === '@' . $botUsername) {
                    $botMentioned = true;
                    break;
                }
            }
        }

        // Check if the text is a reply to a bot's text
        $isReplyToBot = $text->getReplyToMessage() && $text->getReplyToMessage()->getFrom()->getUsername() === $botUsername;

        // If bot is mentioned or the message is a reply to a bot's message
        if ($botMentioned || $isReplyToBot) {
            $reply = $this->generateGroupReply($text);
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $reply
            ]);
        }
    }

    private function generateGroupReply($text)
    {
        // Define keywords and responses
        $keywords = [
            'hello' => 'Hello! How can I help you today?',
            'help' => 'Sure! What do you need help with?',
            'bye' => 'Goodbye! Have a nice day!',
            // Add more keywords and responses as needed
        ];

        // Default reply if no keyword is matched
        $defaultReply = "I didn't understand that. Can you please be more specific?";

        // Check for keywords in the text
        foreach ($keywords as $keyword => $response) {
            if (strpos(strtolower($text), $keyword) !== false) {
                return $response;
            }
        }

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
