<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class telegramMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('telegram_messages')->insert([
            [
                'id' => 1,
                'message_id' => 6408858075,
                'chat_id' => 1,
                'user_id' => 1,
                'text' => 'Hello, this is a test message.',
                'caption' => null,
                'is_reply' => false,
                'reply_to_message_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'message_id' => 6408858076,
                'chat_id' => 2,
                'user_id' => 2,
                'text' => 'Another test message.',
                'caption' => null,
                'is_reply' => true,
                'reply_to_message_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more messages here
        ]);
    }
}
