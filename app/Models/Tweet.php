<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    // 一括代入を許可する属性
    protected $fillable = [
        'content',  // ツイートの内容
        'user_id',  // ユーザーID
    ];

    protected $attributes = [
        'likes' => false,
    ];


    // ツイートのユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Userモデルにいいねのリレーションを追加
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

}
