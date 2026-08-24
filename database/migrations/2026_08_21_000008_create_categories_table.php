<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert initial base categories
        $initialCategories = [
            'Kuliner & Olahan' => 'Sajian makanan, minuman, dan olahan kuliner khas warga Desa Bojongsawah.',
            'Pertanian & Peternakan' => 'Hasil bumi, beras organik, sayuran segar, ternak, dan produk pertanian lokal.',
            'Kerajinan & Kriya' => 'Karya seni, ukiran bambu/kayu, anyaman, dan produk kriya buatan warga.',
            'Jasa & Perdagangan' => 'Layanan jasa profesi, servis pertukangan, dan perdagangan umum lokal.',
            'Lainnya' => 'Kategori produk dan layanan usaha lainnya.'
        ];

        foreach ($initialCategories as $name => $desc) {
            DB::table('categories')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $desc,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
