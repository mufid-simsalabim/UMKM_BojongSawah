<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category', 'name');
    }

    public static function getAllNames(): array
    {
        $names = static::orderBy('name')->pluck('name')->toArray();
        if (empty($names)) {
            return [
                'Kuliner & Olahan',
                'Pertanian & Peternakan',
                'Kerajinan & Kriya',
                'Fashion & Konveksi',
                'Jasa & Perdagangan',
                'Lainnya',
            ];
        }
        return $names;
    }
}
