<?php

namespace App\Http\Controllers;

use App\Models\Replies;
use App\Models\GroupReplies;
use App\Models\Posts;
use App\Models\TelegramChats;
use App\Models\TelegramMessages;
use App\Models\TelegramUsers;
use Exception;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\FileUpload\InputFile;

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
            $user = $message->getFrom();
            $userId = $user->getId();
            $username = $user->getUsername();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $isAdmin = $this->checkIfAdmin($chatId, $userId);
            $joined_at = now(); // Assuming this is the first interaction
            $warning_count = 0; // Assuming initial message count is 1
            $last_warning_at = null; // Default to null

            // Handle new chat members 
            // consider loggong all new members 
            // if ($message->has('new_chat_members')) {
            //     $this->handleNewChatMembers($chatId, $message->getNewChatMembers());
            //     // Create user record
            //     $user = TelegramUsers::Create([
            //         'user_id' => $userId,
            //         'username' => $user,
            //         'first_name' => $firstName,
            //         'last_name' => $lastName,
            //         'warning_count' => $warning_count,
            //         'last_warning_at' => $last_warning_at,
            //         'joined_at' => $joined_at,
            //         'message_count' => 1, // Initialize message count
            //         'is_admin' => $isAdmin,
            //     ]);
            // }

            $chat = TelegramChats::where('chat_id', $chatId)->first();
            if ($chat) {
                // Do nothing
            } else {
                // Create chat record
                $chat = TelegramChats::create([
                    'chat_id' => $chatId,
                    'type' => $chatType,
                ]);
            }

            $user = TelegramUsers::where('user_id', $userId)->first();
            if ($user) {
                // update some thigs
                $user->Update([
                    'username' => $username,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'message_count' => DB::raw('message_count + 1'), // Increment message count
                    'is_admin' => $isAdmin,
                ]);
            } else {
                // Create user record
                $user = TelegramUsers::Create([
                    'user_id' => $userId,
                    'username' => $user,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'warning_count' => $warning_count,
                    'last_warning_at' => $last_warning_at,
                    'joined_at' => $joined_at,
                    'message_count' => 1, // Initialize message count
                    'is_admin' => $isAdmin,
                ]);
            }


            $existingMessage  = TelegramMessages::where('message_id', $message->getMessageId())->first();
            if ($existingMessage) {
                // update some things
                $existingMessage->update([
                    'text' => $text,
                ]);
            } else {
                // create new user records
                TelegramMessages::create([
                    'message_id' => $message->getMessageId(),
                    'chat_id' => $chat->id,
                    'user_id' => $user->id,
                    'text' => $text,
                    'is_reply' => $message->getReplyToMessage() ? true : false, // Indicates if the message is a reply
                    'reply_to_message_id' => $message->getReplyToMessage() ? $message->getReplyToMessage()->getMessageId() : null, // Message ID to which this message replies
                ]);
            }

            switch ($chatType) {
                case 'private':
                    $this->handlePrivateChat($chatId, $text);
                    break;
                case 'group':
                case 'supergroup':
                    $this->handleGroupChat($chatId, $userId, $text, $message);
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
        // Get chat type
        $chat = $this->telegram->getChat(['chat_id' => $chatId]);
        $chatType = $chat->getType();

        // Only check for admins if it's a group, supergroup, or channel
        if (in_array($chatType, ['group', 'supergroup', 'channel'])) {
            $administrators = $this->telegram->getChatAdministrators(['chat_id' => $chatId]);

            foreach ($administrators as $admin) {
                if ($admin->getUser()->getId() == $userId) {
                    return true;
                }
            }
        }

        // Return false for private chats and if the user is not an admin
        return false;
    }

    private function handleNewChatMembers($chatId, $newMembers)
    {
        foreach ($newMembers as $newMember) {
            $welcomeMessage = "Welcome " . $newMember->getFirstName() . "!";
            $this->sendMessage($chatId, $welcomeMessage);
        }
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

        // Initialize variables for text and userId
        $messageText = null;
        $userId = null;

        // Check if $message is an object (assuming it's from Telegram API)
        if (is_object($message)) {
            // Retrieve text from message object safely
            $messageText = $message->getText();

            // Get user ID from message object
            $userId = $message->getFrom()->getId();

            // Check if the message is a reply to a bot's message
            if ($message->getReplyToMessage()) {
                $isReplyToBot = $message->getReplyToMessage()->getFrom()->getId() === $botId;

                // If bot is mentioned or the message is a reply to a bot's message
                if ($isReplyToBot) {
                    $reply = $this->generateGroupReply($messageText);
                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => $reply,
                        'reply_to_message_id' => $message->getMessageId()
                    ]);
                }
            }
        } else {
            // Handle case where $message is not an object (possibly a string or null)
            $this->handleNonTelegramMessage($chatId, $message);
            return;
        }

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
        // $userId = $message->getFrom()->getId();

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
                'text' => $reply,
                'reply_to_message_id' => $message['message_id']
            ]);
        }
    }

    private function handleNonTelegramMessage($chatId, $message)
    {
        // Fetch a random post from the database
        $post = Posts::inRandomOrder()->first();

        if ($post) {
            // Define the array of chat IDs
            $chatIds = config('telegram.chat_ids', []);

            // Check if there are chat IDs configured
            if (empty($chatIds)) {
                throw new Exception('No Telegram chat IDs configured.');
            }

            // Iterate over each chat ID and send the post
            foreach ($chatIds as $chatId) {
                // Check if the post has an image
                if ($post->image) {
                    $caption = trim($post->caption);
                    $image = trim($post->image);

                    // Ensure $post->caption is not empty
                    $caption = !empty($post->caption) ? $post->caption : 'No caption provided';

                    $this->telegram->sendPhoto([
                        'chat_id' => $chatId,
                        'photo' => new InputFile(asset('storage/app/public/posts/' . $image)),
                        'caption' => $caption ?: 'No caption provided',
                    ]);
                } else {
                    // Send the text content if there's no image
                    $text = !empty($post->caption) ? $post->caption : 'No content provided';
                    $this->telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => $text ?: 'No caption provided',
                    ]);
                }
            }
        } else {
            // $this->warn('No posts available to send.');
        }
    }

    private function handleUnwantedContent($chatId, $userId, $message)
    {
        $warningThreshold = 3; // Number of warnings before action is taken

        $user = TelegramUsers::where('user_id', $userId)->first();

        if ($user) {
            $user->warning_count += 1;
            $user->last_warning_at = now();
        } else {
            $user->warning_count = 1;
        }
        $user->save();

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "Warning: Unwanted content detected. This is warning #" . $user->warning_count
        ]);

        $this->telegram->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $message->getMessageId()
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

    private function generateGroupReply($text)
    {
        // $replies = GroupReplies::all();
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
