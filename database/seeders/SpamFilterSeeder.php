<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpamFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('spam')->insert([
            [
                'keywords' => 'free money',
            ],
            [
                'keywords' => 'betting',
            ],
            [
                'keywords' => 'betika',
            ],
            [
                'keywords' => 'fuliza',
            ],
            [
                'keywords' => 'sportspesa',
            ],
            [
                'keywords' => 'm-shwari',
            ],
            [
                'keywords' => 'm-kopa unlock',
            ],
            [
                'keywords' => 'aviator',
            ],
            [
                'keywords' => 'bundles mwitu',
            ],
            [
                'keywords' => 'data bundles', 
            ],
        ]);
    }
}
