<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmkmProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'owner_name',
        'store_name',
        'phone_wa',
        'category',
        'address',
        'description',
        'ktp_image',
        'business_image',
        'status',
        'rejection_reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getKtpImageUrlAttribute(): ?string
    {
        if ($this->ktp_image) {
            if (str_starts_with($this->ktp_image, 'data:')) {
                if (strlen($this->ktp_image) < 300) {
                    return null;
                }
                return $this->ktp_image;
            }
            if (str_starts_with($this->ktp_image, 'http')) {
                return $this->ktp_image;
            }
            return asset('storage/' . $this->ktp_image);
        }
        return null;
    }

    public function getBusinessImageUrlAttribute(): ?string
    {
        if ($this->business_image) {
            if (str_starts_with($this->business_image, 'data:')) {
                if (strlen($this->business_image) < 300) {
                    return null;
                }
                return $this->business_image;
            }
            if (str_starts_with($this->business_image, 'http')) {
                return $this->business_image;
            }
            return asset('storage/' . $this->business_image);
        }
        return null;
    }
}
