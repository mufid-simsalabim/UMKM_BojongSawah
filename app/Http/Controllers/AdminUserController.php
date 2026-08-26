<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UmkmProfile;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Category;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $roleFilter = $request->get('role', 'all'); // all, warga, umkm, suspended, admin
        $search = $request->get('search');

        $query = User::with('umkmProfile')->orderBy('created_at', 'desc');

        if ($roleFilter === 'warga') {
            $query->where('role', 'user');
        } elseif ($roleFilter === 'umkm') {
            $query->where('role', 'umkm');
        } elseif ($roleFilter === 'admin') {
            $query->where('role', 'admin');
        } elseif ($roleFilter === 'suspended') {
            $query->where('status', 'suspended');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('umkmProfile', function($qu) use ($search) {
                      $qu->where('store_name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        $stats = [
            'total_users' => User::count(),
            'total_warga' => User::where('role', 'user')->count(),
            'total_umkm' => User::where('role', 'umkm')->count(),
            'total_suspended' => User::where('status', 'suspended')->count(),
            'total_pending' => User::where('status', 'pending')->count(),
        ];

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'stats', 'roleFilter', 'search'));
    }

    public function create()
    {
        $categories = Category::getAllNames();
        return view('admin.users.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'role' => ['required', 'in:user,umkm,admin'],
            'status' => ['required', 'in:approved,pending,rejected,suspended'],
            'password' => ['required', 'string', 'min:8'],

            // Optional UMKM Profile Fields
            'store_name' => ['required_if:role,umkm', 'nullable', 'string', 'max:255'],
            'nik' => ['required_if:role,umkm', 'nullable', 'string', 'digits:16', 'unique:umkm_profiles,nik'],
            'category' => ['required_if:role,umkm', 'nullable', 'string'],
            'address' => ['required_if:role,umkm', 'nullable', 'string'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'nik.digits' => 'NIK harus berjumlah 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
            'store_name.required_if' => 'Nama toko wajib diisi untuk role UMKM.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($validated['role'] === 'umkm') {
            UmkmProfile::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'] ?? '0000000000000000',
                'owner_name' => $validated['name'],
                'store_name' => $validated['store_name'],
                'phone_wa' => $validated['phone'],
                'category' => $validated['category'] ?? 'Lainnya',
                'address' => $validated['address'] ?? 'Desa Bojongsawah',
                'status' => $validated['status'] === 'suspended' ? 'suspended' : ($validated['status'] === 'approved' ? 'approved' : 'pending'),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "Akun \"{$user->name}\" ({$user->role}) berhasil dibuat!");
    }

    public function edit($id)
    {
        $user = User::with('umkmProfile')->findOrFail($id);
        $categories = Category::getAllNames();
        return view('admin.users.edit', compact('user', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'role' => ['required', 'in:user,umkm,admin'],
            'status' => ['required', 'in:approved,pending,rejected,suspended'],
            'password' => ['nullable', 'string', 'min:8'],

            // Optional UMKM Profile Fields
            'store_name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($user->umkmProfile) {
            $user->umkmProfile->update([
                'owner_name' => $validated['name'],
                'phone_wa' => $validated['phone'],
                'store_name' => $validated['store_name'] ?? $user->umkmProfile->store_name,
                'category' => $validated['category'] ?? $user->umkmProfile->category,
                'address' => $validated['address'] ?? $user->umkmProfile->address,
                'status' => $validated['status'] === 'suspended' ? 'suspended' : ($validated['status'] === 'approved' ? 'approved' : $user->umkmProfile->status),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "Detail akun \"{$user->name}\" berhasil diperbarui.");
    }

    public function suspend(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menangguhkan akun Anda sendiri!');
        }

        $reason = $request->input('reason', 'Pelanggaran ketentuan penggunaan platform.');

        $user->update([
            'status' => 'suspended'
        ]);

        if ($user->umkmProfile) {
            $user->umkmProfile->update([
                'status' => 'suspended'
            ]);
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Akun Anda Ditangguhkan',
            'message' => 'Akun Anda telah ditangguhkan sementara oleh Admin Desa Bojongsawah. Alasan: ' . $reason,
            'url' => route('feed.index'),
        ]);

        return back()->with('warning', "Akun \"{$user->name}\" BERHASIL DITANGGUHKAN (Suspended). Pengguna ini tidak akan dapat login.");
    }

    public function unsuspend($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'approved'
        ]);

        if ($user->umkmProfile) {
            $user->umkmProfile->update([
                'status' => 'approved'
            ]);
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Penangguhan Akun Dicabut!',
            'message' => 'Selamat! Penangguhan akun Anda telah dicabut oleh Admin. Anda kini dapat login kembali.',
            'url' => route('login'),
        ]);

        return back()->with('success', "Penangguhan akun \"{$user->name}\" BERHASIL DICABUT. Akses login telah dipulihkan.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $name = $user->name;

        DB::transaction(function () use ($user) {
            if ($user->umkmProfile) {
                $user->umkmProfile()->delete();
            }
            $user->products()->delete();
            $user->posts()->delete();
            $user->notifications()->delete();
            $user->delete();
        });

        return back()->with('success', "Akun pengguna \"{$name}\" beserta seluruh datanya berhasil dihapus permanen.");
    }
}
