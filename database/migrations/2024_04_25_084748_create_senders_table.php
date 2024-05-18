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
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('email_address')->unique()->required();
            $table->boolean('auth_login_type')->default(false);
            $table->json('smtp_options')->nullable();
            $table->json('imap_options')->nullable();
            $table->json('auth_token')->nullable();
            $table->json('other_options')->nullable();
            $table->unsignedInteger('daily_limit')->default(0);
            $table->unsignedInteger('daily_send_count')->default(0);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('senders');
    }
};
