<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ], [
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.max' => 'Komentar maksimal 1000 karakter.',
            'parent_id.exists' => 'Komentar yang dibalas tidak ditemukan.',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        // Send notifications
        if (!empty($validated['parent_id'])) {
            $parentComment = Comment::find($validated['parent_id']);
            if ($parentComment && $parentComment->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $parentComment->user_id,
                    'title' => 'Balasan Komentar Baru',
                    'message' => Auth::user()->name . ' membalas komentar Anda: "' . Str::limit($validated['content'], 60) . '"',
                    'url' => route('feed.index'),
                ]);
            }
        } elseif ($post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'title' => 'Komentar Baru pada Postingan Anda',
                'message' => Auth::user()->name . ' mengomentari postingan Anda: "' . Str::limit($validated['content'], 60) . '"',
                'url' => route('feed.index'),
            ]);
        }

        return back()->with('success', 'Komentar Anda berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Allow author or admin to delete comment
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk menghapus komentar ini.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
