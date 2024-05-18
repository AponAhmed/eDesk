<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 99);
            $table->string('email', 256);
            $table->string('subject', 256)->nullable();
            $table->longText('message')->nullable();
            $table->foreignId('sender_id')->constrained('senders')->onDelete('cascade');
            $table->json('header')->nullable(); //
            $table->string('labels', 255)->default("inbox,unread");
            $table->integer('reminder')->default(0);
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
        Schema::dropIfExists('g_messages');
    }
};
