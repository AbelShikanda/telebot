<?php

namespace App\Http\Controllers;

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
 
    public function showGlobal()
    {
        $data = CoinMarketCap::getGlobalData();
 
        $this->sendMessage($this->formatArray($data), true);
    }
 
    public function getTicker()
    {
        $data = CoinMarketCap::getTicker();
        $formatted_data = "";
 
        foreach ($data as $datum) {
            $formatted_data .= $this->formatArray($datum);
            $formatted_data .= "-----------\n";
        }
 
        $this->sendMessage($formatted_data, true);
    }
 
    public function getCurrencyTicker()
    {
        $message = "Please enter the name of the Cryptocurrency";
 
        Telegram::create([
            'username' => $this->username,
            'command' => __FUNCTION__
        ]);
 
        $this->sendMessage($message);
    }
 
    public function checkDatabase()
    {
        try {
            $telegram = Telegram::where('username', $this->username)->latest()->firstOrFail();
 
            if ($telegram->command == 'getCurrencyTicker') {
                $response = CoinMarketCap::getCurrencyTicker($this->text);
 
                if (isset($response['error'])) {
                    $message = 'Sorry no such cryptocurrency found';
                } else {
                    $message = $this->formatArray($response[0]);
                }
 
                Telegram::where('username', $this->username)->delete();
 
                $this->sendMessage($message, true);
            }
        } catch (Exception $exception) {
            $error = "Sorry, no such cryptocurrency found.\n";
            $error .= "Please select one of the following options";
            $this->showMenu($error);
        }
    }
 
    protected function formatArray($data)
    {
        $formatted_data = "";
        foreach ($data as $item => $value) {
            $item = str_replace("_", " ", $item);
            if ($item == 'last updated') {
                $value = Carbon::createFromTimestampUTC($value)->diffForHumans();
            }
            $formatted_data .= "<b>{$item}</b>\n";
            $formatted_data .= "\t{$value}\n";
        }
        return $formatted_data;
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

    public function webhook(Request $request)
    {
        $update = Telegram::getWebhookUpdates();

        if ($update->isType('inline_query')) {
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

            Telegram::answerInlineQuery([
                'inline_query_id' => $queryId,
                'results' => json_encode($results)
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function handleWebhook(Request $request)
    {
        $update = Telegram::getWebhookUpdates();
        
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
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePrivateChat($chatId, $text)
    {
        $reply = "This is a private chat. You said: " . $text;
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }

    private function handleGroupChat($chatId, $text)
    {
        $reply = "This is a group chat. You said: " . $text;
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }

    private function handleChannel($chatId, $text)
    {
        $reply = "This is a channel. You said: " . $text;
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }

    private function handleUnknownChatType($chatId)
    {
        $reply = "This is an unknown type of chat.";
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $reply
        ]);
    }
}
