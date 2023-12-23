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
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->string('to'); //json from array
            $table->string('subject'); //json from array
            $table->text('options'); //json from array
            $table->text('replyBody');
            $table->unsignedBigInteger('message_id');
            $table->string('labels', 255)->default("pending");
            $table->timestamps();
            // Define foreign key constraint for 'message_id'
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
};
