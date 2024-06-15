<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupRepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_replies')->insert([
            [
                'id' => 1,
                'keyword' => 'how much is this',
                'response' => 'please dm for more information',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'keyword' => 'where can i get this',
                'response' => 'Just dm for more information',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'keyword' => 'where are you located',
                'response' => 'We are an online shop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'keyword' => 'this is beautifull',
                'response' => 'thank you would you like more samples?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
