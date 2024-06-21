<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'image' => 'telegram',
                'caption' => 'join a telegram link => https://t.me/+FYxHz_QI5gAzOGQ8',
            ],
            [
                'image' => 'telegram',
                'caption' => 'join a telegram link => https://t.me/+Tex6Vf4SFvd2O4iC',
            ],
            [
                'image' => 'telegram',
                'caption' => 'join a telegram link => https://t.me/+g-1Af0PS12Y1ZWY0',
            ],
            [
                'image' => 'telegram printshop channel',
                'caption' => 'join a telegram link => https://t.me/+rf-6lLgxG2piYjVk',
            ],
            [
                'image' => 'telegram',
                'caption' => 'join a telegram link => https://t.me/+ag6OwVbdAWM3OWRk',
            ],
            [
                'image' => 'telegram',
                'caption' => 'join a telegram link => https://t.me/+2dHhvt6lkfxjZGRk',
            ],
            [
                'image' => 'whatsapp printshop group',
                'caption' => 'Join a whatsapp group https://chat.whatsapp.com/Ec5atUfSvGeIkbntkY0qMc',
            ],
            [
                'image' => 'whatsapp printship channel',
                'caption' => 'Join a whatsapp channel https://whatsapp.com/channel/0029Va6hxPK4tRro4abTSA3b',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/CNqjlJdHmPsFME8RsPnpIg',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/Il2rD5EJuC1IZ7tJScb6zf',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/BinoMkZRTBv60SIaYxl4Ed',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/JPJAlVhNeHGLsaP4aV8a96',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/Jk66Hf3LNLS8CWPDPphfjd',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/Dfmx2nkjOCI2h32mDFo0X9',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/JkOLGmoakxk1pO31NnF8to',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/IUubWi0MjdeGR2YFBxkKpe',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/ERdnOIFc0CjHqhtPxoxBdq',
            ],
            [
                'image' => 'whatsapp community',
                'caption' => 'Join a whatsapp community => https://chat.whatsapp.com/ECrYOe0s8kO1szWmVgsho2',
            ],
        ]);
    }
}
