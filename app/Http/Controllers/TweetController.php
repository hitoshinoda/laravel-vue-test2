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
}
