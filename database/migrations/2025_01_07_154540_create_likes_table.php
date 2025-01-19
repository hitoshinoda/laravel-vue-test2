<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id(); // プライマリキー

            // 外部キー（user_id）
            $table->foreignId('user_id')
                ->constrained('users') // usersテーブルのidに紐づけ
                ->onDelete('cascade'); // ユーザー削除時に関連する「いいね」も削除

            // 外部キー（tweet_id）
            $table->foreignId('tweet_id')
                ->constrained('tweets') // tweetsテーブルのidに紐づけ
                ->onDelete('cascade'); // ツイート削除時に関連する「いいね」も削除

            $table->timestamps(); // 作成日時と更新日時

            // ユニーク制約（同じユーザーが同じツイートに複数回いいねできないようにする）
            $table->unique(['user_id', 'tweet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
