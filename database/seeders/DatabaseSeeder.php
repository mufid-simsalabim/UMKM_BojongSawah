<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UmkmProfile;
use App\Models\Product;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Seed Default Product Categories
        $defaultCategories = [
            'Kuliner & Olahan',
            'Pertanian & Peternakan',
            'Kerajinan & Kriya',
            'Fashion & Konveksi',
            'Jasa & Perdagangan',
            'Lainnya',
        ];
        foreach ($defaultCategories as $catName) {
            Category::firstOrCreate([
                'name' => $catName,
            ], [
                'slug' => Str::slug($catName),
                'description' => 'Kategori produk UMKM Desa Bojongsawah',
            ]);
        }

        // 1. Create Admin User
        $admin = User::create([
            'name' => 'Administrator Desa Bojongsawah',
            'email' => 'admin@bojongsawah.desa.id',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        // 2. Create Regular User (Pengguna Biasa / Warga)
        $regularUser = User::create([
            'name' => 'Budi Santoso (Warga Bojongsawah)',
            'email' => 'user@bojongsawah.desa.id',
            'phone' => '081298765432',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        // 3. Create Approved UMKM 1: Keripik Singkong KASEP (also aliased/seeded with umkm@bojongsawah.desa.id)
        $user1 = User::create([
            'name' => 'Ibu Siti Fatimah',
            'email' => 'umkm@bojongsawah.desa.id', // Main test credential for UMKM
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
            'role' => 'umkm',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        $profile1 = UmkmProfile::create([
            'user_id' => $user1->id,
            'nik' => '3202011203890001',
            'owner_name' => 'Ibu Siti Fatimah',
            'store_name' => 'Keripik Singkong KASEP Bojongsawah',
            'phone_wa' => '081234567891',
            'category' => 'Kuliner & Olahan',
            'address' => 'RT 02 / RW 01, Kampung Cikaret, Desa Bojongsawah',
            'description' => 'Produsen keripik singkong gurih khas Desa Bojongsawah dengan bumbu balado rahasia keluarga.',
            'ktp_image' => 'images/logo-bojongsawah.png',
            'business_image' => 'images/kantor-desa.jpg',
            'status' => 'approved',
        ]);

        $prod1 = Product::create([
            'user_id' => $user1->id,
            'name' => 'Keripik Singkong Pedas Manis Balado',
            'slug' => Str::slug('Keripik Singkong Pedas Manis Balado') . '-1',
            'category' => 'Kuliner & Olahan',
            'description' => 'Keripik singkong renyah yang diproses dari singkong pilihan hasil tani warga Bojongsawah, dibalut bumbu balado pedas manis yang bikin ketagihan.',
            'price' => 15000,
            'unit' => 'Bungkus',
            'image' => null,
            'is_active' => true,
        ]);

        $prod2 = Product::create([
            'user_id' => $user1->id,
            'name' => 'Keripik Singkong Original Gurih Renyah',
            'slug' => Str::slug('Keripik Singkong Original Gurih Renyah') . '-2',
            'category' => 'Kuliner & Olahan',
            'description' => 'Varian rasa original dengan gurih asin yang pas. Tanpa pengawet buatan.',
            'price' => 12000,
            'unit' => 'Bungkus',
            'image' => null,
            'is_active' => true,
        ]);

        $post1 = Post::create([
            'user_id' => $user1->id,
            'product_id' => $prod1->id,
            'content' => "Sampurasun warga Desa Bojongsawah! Stok Keripik Singkong Balado KASEP baru matang dari penggorengan nih. Renyah, gurih, dan siap jadi teman bersantai sore bersama keluarga.\n\nYuk langsung klik tombol pesan di bawah ini untuk pemesanan via WhatsApp!",
            'image' => null,
            'likes_count' => 13,
        ]);

        // 4. Create Approved UMKM 2: Beras Organik Sawah KASEP
        $user2 = User::create([
            'name' => 'Pak Haji Ahmad',
            'email' => 'beras@bojongsawah.desa.id',
            'phone' => '081987654321',
            'password' => Hash::make('password123'),
            'role' => 'umkm',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        $profile2 = UmkmProfile::create([
            'user_id' => $user2->id,
            'nik' => '3202011508820002',
            'owner_name' => 'Pak Haji Ahmad',
            'store_name' => 'Beras Organik Sawah KASEP',
            'phone_wa' => '081987654321',
            'category' => 'Pertanian & Peternakan',
            'address' => 'RT 05 / RW 02, Sawah Hilir, Desa Bojongsawah',
            'description' => 'Menyediakan beras hasil panen sawah asli Desa Bojongsawah. Pulen, wangi, dan ditanam secara bebas bahan kimia.',
            'ktp_image' => 'images/logo-bojongsawah.png',
            'business_image' => 'images/sawah-hero.jpg',
            'status' => 'approved',
        ]);

        $prod3 = Product::create([
            'user_id' => $user2->id,
            'name' => 'Beras Pandan Wangi Organik Bojongsawah 5kg',
            'slug' => Str::slug('Beras Pandan Wangi Organik Bojongsawah 5kg') . '-3',
            'category' => 'Pertanian & Peternakan',
            'description' => 'Beras pandan wangi unggulan ditanam langsung di persawahan subur Desa Bojongsawah. Nasi lebih pulen dan beraroma harum alami.',
            'price' => 75000,
            'unit' => 'Karung 5kg',
            'image' => null,
            'is_active' => true,
        ]);

        $post2 = Post::create([
            'user_id' => $user2->id,
            'product_id' => $prod3->id,
            'content' => "Alhamdulillah panen raya di persawahan Bojongsawah melimpah! Beras Pandan Wangi Organik sudah siap dipasarkan. Beras baru giling, nasi dijamin sangat pulen dan harum.\n\nBisa antar langsung ke rumah wilayah Kebonpedes & sekitarnya. Tanya-tanya atau pesan bisa langsung klik WhatsApp di bawah ya!",
            'image' => null,
            'likes_count' => 25,
        ]);

        // 5. Create Approved UMKM 3: Kerajinan Bambu Sunda
        $user3 = User::create([
            'name' => 'Kang Ujang Bambu',
            'email' => 'bambu@bojongsawah.desa.id',
            'phone' => '085712345678',
            'password' => Hash::make('password123'),
            'role' => 'umkm',
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        UmkmProfile::create([
            'user_id' => $user3->id,
            'nik' => '3202012004850003',
            'owner_name' => 'Kang Ujang Bambu',
            'store_name' => 'Kerajinan Bambu Sunda Bojongsawah',
            'phone_wa' => '085712345678',
            'category' => 'Kerajinan & Kriya',
            'address' => 'RT 01 / RW 03, Kampung Babakan, Desa Bojongsawah',
            'description' => 'Pengrajin produk olahan bambu bernilai seni tinggi seperti besek hajatan, tempat lampu hias, dan perabotan bambu tradisional.',
            'ktp_image' => 'images/logo-bojongsawah.png',
            'business_image' => 'images/kantor-desa.jpg',
            'status' => 'approved',
        ]);

        Product::create([
            'user_id' => $user3->id,
            'name' => 'Besek & Anyaman Bambu Tradisional Handmade',
            'slug' => Str::slug('Besek Anyaman Bambu Tradisional') . '-4',
            'category' => 'Kerajinan & Kriya',
            'description' => 'Besek bambu kualitas export buatan tangan warga Bojongsawah. Cocok untuk wadah souvenir, catering, maupun hampers khas desa.',
            'price' => 25000,
            'unit' => 'Set (2 Pcs)',
            'image' => null,
            'is_active' => true,
        ]);

        // 6. Create Pending UMKM applicant for Admin Dashboard verification testing
        $userPending = User::create([
            'name' => 'Ibu Neng Heni',
            'email' => 'warungmakan@bojongsawah.desa.id',
            'phone' => '082123456789',
            'password' => Hash::make('password123'),
            'role' => 'umkm',
            'status' => 'pending',
            'email_verified_at' => now(),
        ]);

        UmkmProfile::create([
            'user_id' => $userPending->id,
            'nik' => '3202012809940004',
            'owner_name' => 'Ibu Neng Heni',
            'store_name' => 'Warung Nasi Liwet Khas Bojongsawah',
            'phone_wa' => '082123456789',
            'category' => 'Kuliner & Olahan',
            'address' => 'RT 03 / RW 02, Desa Bojongsawah',
            'description' => 'Warung makan khas Sunda menyediakan Paket Nasi Liwet Kastrol komplit dengan ikan asin, lalapan segar, dan sambal terasi.',
            'ktp_image' => 'images/logo-bojongsawah.png',
            'business_image' => 'images/kantor-desa.jpg',
            'status' => 'pending',
        ]);

        // 7. Seed sample likes and comments from regular user
        Like::create([
            'post_id' => $post1->id,
            'user_id' => $regularUser->id,
        ]);

        Like::create([
            'post_id' => $post2->id,
            'user_id' => $regularUser->id,
        ]);

        Comment::create([
            'post_id' => $post1->id,
            'user_id' => $regularUser->id,
            'content' => 'Mantap pisan bu, kemaren pesen 5 bungkus langsung abis dimakan keluarga. Bumbu baladonya pas banget!',
        ]);

        Comment::create([
            'post_id' => $post2->id,
            'user_id' => $regularUser->id,
            'content' => 'Berasnya sangat pulen pak Haji, recommended buat konsumsi harian.',
        ]);
    }
}
