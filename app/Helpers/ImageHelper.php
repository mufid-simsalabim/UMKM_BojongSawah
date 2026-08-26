<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Convert an uploaded image file into a compressed Base64 Data URI string.
     *
     * @param UploadedFile|string|null $file
     * @param int $maxWidth
     * @param int $quality
     * @return string|null
     */
    public static function store($file, int $maxWidth = 600, int $quality = 70): ?string
    {
        if (!$file) {
            return null;
        }

        if (is_string($file)) {
            // Already a data URI or URL string
            return $file;
        }

        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return null;
        }

        $filePath = $file->getRealPath();
        $mime = $file->getMimeType() ?: 'image/jpeg';

        // Check if GD extension is available for image resizing/compression
        if (function_exists('imagecreatefromstring') && function_exists('imagejpeg')) {
            try {
                $rawContent = file_get_contents($filePath);
                $srcImage = @imagecreatefromstring($rawContent);

                if ($srcImage !== false) {
                    $origWidth = imagesx($srcImage);
                    $origHeight = imagesy($srcImage);

                    if ($origWidth > $maxWidth) {
                        $newWidth = $maxWidth;
                        $newHeight = (int) floor($origHeight * ($maxWidth / $origWidth));

                        $destImage = imagecreatetruecolor($newWidth, $newHeight);

                        // Preserve transparency for PNG/WEBP
                        if ($mime === 'image/png' || $mime === 'image/webp') {
                            imagealphablending($destImage, false);
                            imagesavealpha($destImage, true);
                            $transparent = imagecolorallocatealpha($destImage, 255, 255, 255, 127);
                            imagefilledrectangle($destImage, 0, 0, $newWidth, $newHeight, $transparent);
                        }

                        imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                        imagedestroy($srcImage);
                        $srcImage = $destImage;
                    }

                    ob_start();
                    if ($mime === 'image/png' && function_exists('imagepng')) {
                        imagepng($srcImage, null, 6);
                    } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
                        imagewebp($srcImage, null, $quality);
                    } else {
                        imagejpeg($srcImage, null, $quality);
                        $mime = 'image/jpeg';
                    }
                    $imageData = ob_get_clean();
                    imagedestroy($srcImage);

                    return 'data:' . $mime . ';base64,' . base64_encode($imageData);
                }
            } catch (\Throwable $e) {
                // Fallback if GD fails
            }
        }

        // Fallback without GD: encode raw file directly
        $rawContent = file_get_contents($filePath);
        return 'data:' . $mime . ';base64,' . base64_encode($rawContent);
    }
}
