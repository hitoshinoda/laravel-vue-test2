<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Tweet;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * いいねを追加または削除
     */
    public function like(Request $request, $tweetId)
    {
        $tweet = Tweet::findOrFail($tweetId);

        // ユーザーがすでにいいねしているか確認
        $like = $tweet->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            // いいねを取り消す
            $like->delete();
            $liked = false;
        } else {
            // いいねを追加
            $tweet->likes()->create([
                'user_id' => auth()->id(),
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $tweet->likes()->count(), // 現在のいいね数を返す
        ]);
    }
}
