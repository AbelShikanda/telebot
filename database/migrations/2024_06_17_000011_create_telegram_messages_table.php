<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('message_id')->unsigned()->unique();

            $table->foreignId('chat_id')->constrained('telegram_chats')->onDelete('cascade')->onUpdate('cascade')->signed();
            $table->foreignId('user_id')->constrained('telegram_users')->onDelete('cascade')->onUpdate('cascade')->signed();
            
            $table->text('text')->nullable();
            $table->boolean('is_reply')->default(false); // Indicates if the message is a reply
            $table->bigInteger('reply_to_message_id')->unsigned()->nullable(); // Message ID to which this message replies
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_messages');
    }
}
