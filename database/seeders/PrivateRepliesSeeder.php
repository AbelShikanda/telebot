<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivateRepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $greeting_text = 'Hi?  This is printshopeld.  thank you for reaching out.  would  you like to place an order?';
        $sample = 'What designs would you like to have printed out?  Do you already have some designs in mind  or  would like to see some samples?';
        $more_samples = 'check out our catalog on  whatsapp  for more samples  https://wa.me/c/254728157164';
        $colorSize = 'what colors and sizes  would you like to have printed out?';
        $deliveries = 'Deliveries are made countrywide. 1. Deliveries cost 300/-. 2. Where are you located';
        $procedure = 'Your order will take. 1. 3 business days to be completed. 2. can i send you the payment details?';
        $payment = 'You can pay to. =>. Lipa Na Mpesa. =>. Till No. - 9030355. =>. Name. - JAVAN KUSH ENTERPRISES  send us a screenshot of you payment for confirmation';
        $ok = 'Ok...  thank you for your time,  Looking forward to hearing from you soon.  Look through our shop for more designs:  Whatsapp => https://wa.me/c/254728157164  Whatsapp => https://wa.me/c/254728157164  Instagram => https://www.instagram.com/printshopeld?igsh=NTdmNnhoNWtiMzRv  Website => https://www.printshopeld.com';

        DB::table('replies')->insert([
            [
                'keyword' => 'hello',
                'response' => $greeting_text,
            ],
            [
                'keyword' => 'hi',
                'response' => $greeting_text,
            ],
            [
                'keyword' => 'niaje',
                'response' => $greeting_text,
            ],
            [
                'keyword' => 'sasa',
                'response' => $greeting_text,
            ],
            [
                'keyword' => 'how are you',
                'response' => $greeting_text,
            ],
            [
                'keyword' => 'greetings',
                'response' => $greeting_text,
            ],
            [
                'keyword' => 'location',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'where are you located',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'uko wapi',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'mko wapi',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'iko wapi',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'unapatikana wapi',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'inapatikana wapi',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'zinapatikana wapi',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'how can i get them',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'do you deliver',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'do you do deliveries',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'Where is your shop',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'where can i get you',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'how can i get this',
                'response' => $deliveries,
            ],
            [
                'keyword' => 'more designs',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'designs',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'more samples',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'samples',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'nothing in mind',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'i do not have',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'i don\'t not have',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'i dont not have',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'dont not have',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'don\'t not have',
                'response' => $more_samples,
            ],
            [
                'keyword' => 'yeah',
                'response' => $sample,
            ],
            [
                'keyword' => 'yea',
                'response' => $sample,
            ],
            [
                'keyword' => 'ndio',
                'response' => $sample,
            ],
            [
                'keyword' => 'i want this design',
                'response' => $colorSize,
            ],
            [
                'keyword' => 'i want this',
                'response' => $colorSize,
            ],
            [
                'keyword' => 'this',
                'response' => $colorSize,
            ],
            [
                'keyword' => 'this one',
                'response' => $colorSize,
            ],
            [
                'keyword' => 'i want this one',
                'response' => $colorSize,
            ],
            [
                'keyword' => 'how long will it take',
                'response' => $procedure,
            ],
            [
                'keyword' => 'when can i get it',
                'response' => $procedure,
            ],
            [
                'keyword' => 'when can i get them',
                'response' => $procedure,
            ],
            [
                'keyword' => 'how long',
                'response' => $procedure,
            ],
            [
                'keyword' => 'when can i get it',
                'response' => $procedure,
            ],
            [
                'keyword' => 'When can i get this',
                'response' => $procedure,
            ],
            [
                'keyword' => 'When will you deliver',
                'response' => $procedure,
            ],
            [
                'keyword' => 'When will you be done',
                'response' => $procedure,
            ],
            [
                'keyword' => 'when will i get them',
                'response' => $procedure,
            ],
            [
                'keyword' => 'where can i pay',
                'response' => $payment,
            ],
            [
                'keyword' => 'where do i pay',
                'response' => $payment,
            ],
            [
                'keyword' => 'how do i pay',
                'response' => $payment,
            ],
            [
                'keyword' => 'how do i make payment',
                'response' => $payment,
            ],
            [
                'keyword' => 'payment',
                'response' => $payment,
            ],
            [
                'keyword' => 'payment details',
                'response' => $payment,
            ],
            [
                'keyword' => 'i will pay tomorrow',
                'response' => $payment,
            ],
            [
                'keyword' => 'will pay tomorrow',
                'response' => $payment,
            ],
            [
                'keyword' => 'will pay when i am ready',
                'response' => $payment,
            ],
            [
                'keyword' => 'no',
                'response' => $ok,
            ],
            [
                'keyword' => 'not today',
                'response' => $ok,
            ],
            [
                'keyword' => 'no thanks',
                'response' => $ok,
            ],
            [
                'keyword' => 'no thankyou',
                'response' => $ok,
            ],
            [
                'keyword' => 'will reach out whem i am ready',
                'response' => $ok,
            ],
            [
                'keyword' => 'i will reach out when i am ready',
                'response' => $ok,
            ],
            [
                'keyword' => 'will reach out whem ready',
                'response' => $ok,
            ],
            [
                'keyword' => 'i will reach out when ready',
                'response' => $ok,
            ],
            [
                'keyword' => 'i will reach out soon',
                'response' => $ok,
            ],
            [
                'keyword' => 'will reach out soon',
                'response' => $ok,
            ],
            [
                'keyword' => 'soon',
                'response' => $ok,
            ],
            [
                'keyword' => 'will buy soon',
                'response' => $ok,
            ],
            [
                'keyword' => 'i will buy soon',
                'response' => $ok,
            ],
            [
                'keyword' => 'i will communicate',
                'response' => $ok,
            ],
            [
                'keyword' => 'will communicate',
                'response' => $ok,
            ],
            [
                'keyword' => 'will communicate soon',
                'response' => $ok,
            ],
            [
                'keyword' => 'i will communicate soon',
                'response' => $ok,
            ],
            [
                'keyword' => 'later',
                'response' => $ok,
            ],
            [
                'keyword' => 'maybe later',
                'response' => $ok,
            ],
            [
                'keyword' => 'i will buy later',
                'response' => $ok,
            ],
            [
                'keyword' => 'will buy later',
                'response' => $ok,
            ],
            [
                'keyword' => 'yes',
                'response' => $sample,
            ],
        ]);
    }
}
