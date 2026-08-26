<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'min:10', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'avatar.image' => 'File foto profil harus berupa gambar.',
            'avatar.max' => 'Ukuran foto profil maksimal 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = ImageHelper::store($request->file('avatar'));
        }

        // If email changed, require re-verification
        if ($validated['email'] !== $user->email) {
            $user->email_verified_at = null;
            $user->fill($validated);
            $user->save();
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')->with('warning', 'Alamat email Anda berhasil diperbarui. Silakan periksa inbox/spam Anda untuk melakukan verifikasi ulang.');
        }

        $user->update($validated);

        // Sync phone number to umkmProfile if present
        if (!empty($validated['phone']) && $user->umkmProfile) {
            $user->umkmProfile->update([
                'phone_wa' => $validated['phone']
            ]);
        }

        return back()->with('success', 'Detail profil Anda berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui!');
    }
}
