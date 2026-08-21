<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

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
