<?php

namespace App\Console\Commands;

use App\Models\GroupLinks;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class PostLinksToTelegram extends Command
{
    protected $telegramService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'post group links to telegram';

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
            // Fetch a random link from the database
            $post = GroupLinks::inRandomOrder()->first();
            if ($post) {
                // Define the array of chat IDs
                $chatIds = explode(',', env('CHAT_ID', ''));
                // Iterate over each chat ID and send the post
                foreach ($chatIds as $chatId) {
                    $chatId = trim($chatId); // Trim any whitespace
                    $platform = trim($post->platform) ?: 'No platform provided';
                    $link = trim($post->link) ?: 'No link provided';
                    $this->telegramService->sendMessage([
                        'chat_id' => $chatId,
                        'text' => $link,
                    ]);
                }
                return 0;
            } else {
                return 0;
            }
        } catch (\Throwable $th) {
            $this->error('There was an error sending post to Telegram: ' . $th->getMessage());
            return 1;
        }
    }
}
