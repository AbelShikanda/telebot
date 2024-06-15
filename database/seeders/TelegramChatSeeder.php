<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelegramChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('telegram_chats')->insert([
            [
                'id' => 1,
                'chat_id' => 6408858012,
                'user_id' => 1,
                'type' => 'group',
                'last_update' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'chat_id' => 6408858283,
                'user_id' => 2,
                'type' => 'private',
                'last_update' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more chats here
        ]);
    }
}
