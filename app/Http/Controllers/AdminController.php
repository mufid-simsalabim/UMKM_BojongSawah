<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UmkmProfile;
use App\Models\Product;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $statusFilter = $request->get('status', 'pending');

        $stats = [
            'total_umkm' => UmkmProfile::count(),
            'pending' => UmkmProfile::where('status', 'pending')->count(),
            'approved' => UmkmProfile::where('status', 'approved')->count(),
            'rejected' => UmkmProfile::where('status', 'rejected')->count(),
            'total_posts' => Post::count(),
        ];

        $query = UmkmProfile::with('user')->orderBy('created_at', 'desc');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $umkms = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', compact('stats', 'umkms', 'statusFilter'));
    }

    public function approve($id)
    {
        $umkm = UmkmProfile::findOrFail($id);
        $umkm->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);

        $umkm->user->update([
            'status' => 'approved'
        ]);

        Notification::create([
            'user_id' => $umkm->user_id,
            'title' => 'Pendaftaran UMKM Disetujui!',
            'message' => 'Selamat! Pendaftaran UMKM "' . $umkm->store_name . '" telah disetujui Admin. Anda kini dapat memposting produk & kabar usaha di beranda.',
            'url' => route('umkm.dashboard'),
        ]);

        return back()->with('success', "Akun UMKM \"{$umkm->store_name}\" ({$umkm->owner_name}) BERHASIL DISETUJUI. Pelaku UMKM kini sudah dapat login dan memposting produk.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi untuk memberikan feedback pada pendaftar.'
        ]);

        $umkm = UmkmProfile::findOrFail($id);
        $umkm->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $umkm->user->update([
            'status' => 'rejected'
        ]);

        Notification::create([
            'user_id' => $umkm->user_id,
            'title' => 'Pendaftaran UMKM Ditolak',
            'message' => 'Pendaftaran UMKM "' . $umkm->store_name . '" belum disetujui. Alasan: ' . $request->rejection_reason,
            'url' => route('feed.index'),
        ]);

        return back()->with('info', "Pendaftaran UMKM \"{$umkm->store_name}\" telah DITOLAK.");
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return back()->with('success', 'Postingan beranda berhasil dihapus oleh Admin.');
    }
}
