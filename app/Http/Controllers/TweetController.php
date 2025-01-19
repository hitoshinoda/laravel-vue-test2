<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    // タイムラインを取得
    public function index()
    {
        $tweets = Tweet::with('user')->latest()->get();

        // プロフィール画像のURLを設定
        $tweets = $tweets->map(function ($tweet) {
            $tweet->user->profile_image_url = $tweet->user->profile_image
                ? asset('storage/' . $tweet->user->profile_image)
                : asset('images/default-profile.png'); // デフォルト画像
            return $tweet;
        });

        return response()->json($tweets);
    }

    // ツイートを投稿
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|max:280',
        ]);

        $tweet = Tweet::create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        // 関連ユーザー情報を含めたツイートを返す
        $tweet->load('user');

        $tweet->user->profile_image_url = $tweet->user->profile_image
            ? asset('storage/' . $tweet->user->profile_image)
            : asset('images/default-profile.png');

        return response()->json($tweet);
    }

    // ツイートを削除
    public function destroy(Tweet $tweet)
    {
        if ($tweet->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $tweet->delete();
        return response()->json(['message' => 'Tweet deleted successfully']);
    }

    //プロフィールに表示する自分のツイート
    public function userTweets()
    {
        $tweets = Tweet::where('user_id', auth()->id())
            ->latest()
            ->with('user')
            ->get();

        //自分のプロフィール画像呼び出し
        $tweets = $tweets->map(function ($tweet) {
            $tweet->user->profile_image_url = $tweet->user->profile_image
                ? asset('storage/' . $tweet->user->profile_image)
                : asset('images/default-profile.png'); // デフォルト画像
            return $tweet;
        });

        return response()->json([
            'tweets' => $tweets,
        ]);
    }

    //プロフィールに表示する自分のリプライ
    public function userReplies()
    {
        $replies = Tweet::where('type', 'reply')
            ->where('user_id', auth()->id())
            ->latest()
            ->with('user')
            ->get();

        return response()->json([
            'tweets' => $replies,
        ]);
    }
}
