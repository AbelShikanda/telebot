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
        $text = 'hi!... thank you for reaching out. check your Dm for a details';
        $default_price_text = 'hi!... this is printshopeld. thank you for reaching out. this is a price list of our items: 1. caps - 650/- 2. tee-shirts - 850/- 3. polos - 1,000/- 4. sweatshirts - 2,000/- 5. hoodies - 2,500/-';
        $default_location_text = 'hi!... this is printshopeld. thank you for reaching out. We are an online store located in Nairobi: 1. Deliveries are made countrywide 2. Deliveries cost 300/- 3. Where are you located';

        DB::table('group_replies')->insert([
            [
                'keyword' => 'how much is this',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'how much',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'ngapi',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'price',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'how much for this',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'cost',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'ni ngapi',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'ni how much',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'unauza ngapi',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'unauza aje',
                'response' => $text,
                'default_response' => $default_price_text,
            ],
            [
                'keyword' => 'location',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'where are you located',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'uko wapi',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'delivery',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'inapatikana wapi',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'wapi',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'uko located wapi',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'where are you',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'ntapata aje',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'unapatikana wapi',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'inapatikana aje',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
            [
                'keyword' => 'unapatikana aje',
                'response' => $text,
                'default_response' => $default_location_text,
            ],
        ]);
    }
}
