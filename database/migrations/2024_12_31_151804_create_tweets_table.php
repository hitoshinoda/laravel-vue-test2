<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーとのリレーション
            $table->text('content'); // 投稿内容
            $table->boolean('likes')->nullable();
            $table->timestamps();
            $table->integer('reply_to')->nullable();
            $table->integer('quote_tweet_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tweets');
    }
};
