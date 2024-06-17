<?php

namespace App\Console\Commands;

use App\Models\Posts;
use Illuminate\Console\Command;
use Telegram\Bot\Api;
use Exception;

class PostToTelegram extends Command
{
    protected $telegram;

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
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
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

            if ($post) {
                // Define the array of chat IDs
                $chatIds = config('telegram.chat_ids', []);
                $caption = $post->caption ?: 'No caption provided';

                // Check if there are chat IDs configured
                if (empty($chatIds)) {
                    throw new Exception('No Telegram chat IDs configured.');
                }

                // Iterate over each chat ID and send the post
                foreach ($chatIds as $chatId) {
                    // Check if the post has an image
                    if ($post->image_url) {
                        // Send the image along with the caption to the Telegram group
                        $this->telegram->sendPhoto([
                            'chat_id' => $chatId,
                            'photo' => $post->image_url,
                            'caption' => $post->caption,
                        ]);
                    } else {
                        // Send the text content if there's no image
                        $this->telegram->sendMessage([
                            'chat_id' => $chatId,
                            'text' => 'No content provided',
                        ]);
                    }
                }

                // Mark the post as sent
                $post->update(['posted_to_telegram' => true]);

                $this->info('Random post sent to Telegram groups successfully!');
            } else {
                $this->warn('No posts available to send.');
            }

        } catch (Exception $e) {
            $this->error('Error sending post to Telegram: ' . $e->getMessage());
        }
    }
}
