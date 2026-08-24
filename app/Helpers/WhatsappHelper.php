<?php

namespace App\Helpers;

class WhatsappHelper
{
    /**
     * Normalize Indonesian phone number to international format without + or leading zeroes.
     * e.g. 081234567890 -> 6281234567890
     */
    public static function formatPhone(?string $phone): string
    {
        if (!$phone) {
            return '628000000000';
        }

        // Strip non-numeric chars
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '62')) {
            return $cleaned;
        }

        return '62' . $cleaned;
    }

    /**
     * Generate WhatsApp Click-to-Chat URL for Product Order
     */
    public static function makeProductOrderUrl(string $phone, string $storeName, string $productName, int|float $price): string
    {
        $phoneFormatted = self::formatPhone($phone);
        $formattedPrice = 'Rp ' . number_format($price, 0, ',', '.');
        $message = "Halo {$storeName},\n\nSaya tertarik dan bermaksud memesan produk *\"{$productName}\"* ({$formattedPrice}) yang saya lihat di website *Social Commerce UMKM Desa Bojongsawah*.\n\nApakah produk ini masih tersedia dan bagaimana proses pemesanannya?";

        return 'https://wa.me/' . $phoneFormatted . '?text=' . urlencode($message);
    }

    /**
     * Generate WhatsApp Click-to-Chat URL for Social Feed Post
     */
    public static function makePostInquiryUrl(string $phone, string $storeName, string $postTitleOrContent): string
    {
        $phoneFormatted = self::formatPhone($phone);
        $snippet = mb_strimwidth(strip_tags($postTitleOrContent), 0, 80, '...');
        $message = "Halo {$storeName},\n\nSaya tertarik dengan postingan Anda di website *Social Commerce UMKM Desa Bojongsawah*:\n\"{$snippet}\"\n\nBisakah dibantu informasi lebih lanjut mengenai produk/layanan ini?";

        return 'https://wa.me/' . $phoneFormatted . '?text=' . urlencode($message);
    }

    /**
     * Get formatted admin phone number dynamically from database
     */
    public static function getAdminPhone(): string
    {
        $adminPhone = \App\Models\User::where('role', 'admin')->whereNotNull('phone')->where('phone', '!=', '')->value('phone');
        return self::formatPhone($adminPhone ?: '081234567890');
    }
}
