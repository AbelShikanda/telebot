<?php

namespace App\Console\Commands;

use App\Models\Posts;
use App\Services\TelegramService;
use CURLFile;
use Exception;
use Illuminate\Console\Command;

class PostToTelegram extends Command
{
    protected $telegramService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post to Telegram group periodically from the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->telegramService = new TelegramService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Fetch a random post from the database
            $post = Posts::inRandomOrder()->first();
            if ($post->image) {
                // Define the array of chat IDs
                $chatIds = explode(',', env('CHAT_ID', ''));
                // Iterate over each chat ID and send the post
                foreach ($chatIds as $chatId) {
                    $chatId = trim($chatId); // Trim any whitespace
                    if ($post->image) {
                        $caption = trim($post->caption);
                        $image = trim($post->image);
                        $imageUrl = asset('storage/app/public/posts/' . $image);
                        // Ensure $post->caption is not empty
                        $caption = trim($post->caption) ?: 'No caption provided';
                        $this->telegramService->sendPhoto([
                            'chat_id' => $chatId,
                            'photo' => new CURLFile($imageUrl),
                            'caption' => $caption,
                        ]);
                    } else {
                        
                        $text = trim($post->caption) ?: 'No content provided';
                        $this->telegramService->sendMessage([
                            'chat_id' => $chatId,
                            'text' => $text,
                        ]);
                    }
                }
                return 0;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            $this->error('There was an error sending post to Telegram: ' . $e->getMessage());
            return 1;
        }
    }
}
