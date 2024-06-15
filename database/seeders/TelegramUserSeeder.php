<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelegramUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('telegram_users')->insert([
            [
                'id' => 1,
                'user_id' => 6408858075,
                'username' => 'johndoe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'warning_count' => 0,
                'last_warning_at' => null,
                'joined_at' => now(),
                'message_count' => 5,
                'is_admin' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 6408858076,
                'username' => 'janedoe',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'warning_count' => 1,
                'last_warning_at' => now(),
                'joined_at' => now(),
                'message_count' => 3,
                'is_admin' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users here
        ]);
    }
}
