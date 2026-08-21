<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle($postId)
    {
        $post = Post::findOrFail($postId);
        $userId = Auth::id();

        $existingLike = Like::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');
            $message = 'Batal menyukai postingan.';
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
            $post->increment('likes_count');
            $message = 'Postingan disukai!';
        }

        return back()->with('info', $message);
    }
}
