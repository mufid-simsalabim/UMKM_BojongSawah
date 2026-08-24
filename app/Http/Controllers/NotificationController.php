<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true]);

        if ($notification->url) {
            return redirect($notification->url);
        }

        return back()->with('success', 'Pemberitahuan telah ditandai sebagai dibaca.');
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Semua pemberitahuan telah ditandai sebagai dibaca.');
    }
}
