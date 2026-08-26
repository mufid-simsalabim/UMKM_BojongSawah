<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\User;
use App\Models\UmkmProfile;
use App\Models\Category;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::user()->isUmkm()) {
                return redirect()->route('umkm.dashboard');
            }
            return redirect()->route('feed.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->isSuspended()) {
                Auth::logout();
                return back()->with('error', 'Akun Anda saat ini DITANGGUHKAN oleh Admin Desa Bojongsawah. Silakan hubungi kantor desa jika ini adalah kekeliruan.');
            }

            if ($user->isUmkm() && !$user->isApproved()) {
                Auth::logout();
                if ($user->status === 'pending') {
                    return back()->with('warning', 'Akun Pelaku UMKM Anda masih dalam proses verifikasi oleh Admin Desa Bojongsawah. Mohon tunggu persetujuan.');
                }
                if ($user->status === 'rejected') {
                    $reason = optional($user->umkmProfile)->rejection_reason ?? 'Persyaratan belum memenuhi syarat.';
                    return back()->with('error', "Pendaftaran UMKM Anda telah ditolak oleh Admin. Alasan: {$reason}");
                }
            }

            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang di Dashboard Admin Desa Bojongsawah!');
            }

            if ($user->isUmkm()) {
                return redirect()->intended(route('umkm.dashboard'))->with('success', 'Selamat datang Pelaku UMKM Bojongsawah!');
            }

            return redirect()->intended(route('feed.index'))->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.',
            'phone.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'status' => 'approved',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', 'Pendaftaran akun berhasil! Silakan periksa notifikasi email Anda untuk melakukan verifikasi.');
    }

    public function showRegisterUmkmForm()
    {
        try {
            $categories = Category::getAllNames();
        } catch (\Throwable $e) {
            $categories = [
                'Kuliner & Olahan',
                'Pertanian & Peternakan',
                'Kerajinan & Kriya',
                'Fashion & Konveksi',
                'Jasa & Perdagangan',
                'Lainnya',
            ];
        }
        return view('auth.register-umkm', compact('categories'));
    }

    public function registerUmkm(Request $request)
    {
        // Pre-cleanup: Purge any unapproved (pending/rejected/orphan) user or profile with matching email or NIK
        if ($request->filled('email')) {
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser && $existingUser->status !== 'approved') {
                DB::transaction(function () use ($existingUser) {
                    if ($existingUser->umkmProfile) {
                        $existingUser->umkmProfile()->delete();
                    }
                    $existingUser->delete();
                });
            }
        }

        if ($request->filled('nik')) {
            $existingProfile = UmkmProfile::where('nik', $request->nik)->first();
            if ($existingProfile && $existingProfile->status !== 'approved') {
                DB::transaction(function () use ($existingProfile) {
                    if ($existingProfile->user) {
                        $existingProfile->user()->delete();
                    }
                    $existingProfile->delete();
                });
            }
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'digits:16', 'unique:umkm_profiles,nik'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:10', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'store_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'ktp_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'business_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ], [
            'nik.digits' => 'NIK harus berjumlah 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        try {
            DB::transaction(function () use ($request, $validated) {
                // Upload files into persistent Base64 Data URIs if provided
                $ktpPath = $request->hasFile('ktp_image') ? ImageHelper::store($request->file('ktp_image')) : 'ktp_documents/default.jpg';
                $businessPath = $request->hasFile('business_image') ? ImageHelper::store($request->file('business_image')) : 'business_documents/default.jpg';

                // Create User
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'umkm',
                    'status' => 'pending',
                ]);

                // Create UMKM Profile
                UmkmProfile::create([
                    'user_id' => $user->id,
                    'nik' => $validated['nik'],
                    'owner_name' => $validated['name'],
                    'store_name' => $validated['store_name'],
                    'phone_wa' => $validated['phone'],
                    'category' => $validated['category'],
                    'address' => $validated['address'],
                    'description' => $validated['description'] ?? null,
                    'ktp_image' => $ktpPath ?: 'ktp_documents/default.jpg',
                    'business_image' => $businessPath ?: 'business_documents/default.jpg',
                    'status' => 'pending',
                ]);
            });

            return redirect()->route('login')->with('success', 'Pendaftaran UMKM berhasil dikirim! Akun Anda saat ini berstatus PENDING dan sedang diverifikasi oleh Admin Desa Bojongsawah.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal mendaftar UMKM: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('feed.index')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
