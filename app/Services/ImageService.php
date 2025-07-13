<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImageService
{
    /**
     * Store and optimize an uploaded image
     *
     * @param UploadedFile $image
     * @param string $directory
     * @param int $width
     * @param int $height
     * @param bool $maintainAspectRatio
     * @return string|null
     */
    public function storeImage(
        UploadedFile $image, 
        string $directory = 'images', 
        int $width = 800, 
        int $height = 800,
        bool $maintainAspectRatio = true
    ): ?string {
        try {
            // Generate a unique filename
            $filename = time() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Create the full path
            $path = $directory . '/' . $filename;
            
            // Process and optimize the image using PHP's GD library
            $sourceImage = $this->createImageFromFile($image);
            if (!$sourceImage) {
                throw new \Exception('Unable to create image from uploaded file');
            }
            
            // Get original dimensions
            $srcWidth = imagesx($sourceImage);
            $srcHeight = imagesy($sourceImage);
            
            // Calculate new dimensions
            $newWidth = $width;
            $newHeight = $height;
            
            if ($maintainAspectRatio) {
                if ($srcWidth > $srcHeight) {
                    $newHeight = ($srcHeight / $srcWidth) * $width;
                } else {
                    $newWidth = ($srcWidth / $srcHeight) * $height;
                }
            }
            
            // Create a new image with the calculated dimensions
            $newImage = imagecreatetruecolor((int)$newWidth, (int)$newHeight);
            
            // Preserve transparency for PNG images
            if ($image->getClientOriginalExtension() === 'png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
            }
            
            // Resize the image
            imagecopyresampled(
                $newImage, 
                $sourceImage, 
                0, 0, 0, 0, 
                (int)$newWidth, 
                (int)$newHeight, 
                $srcWidth, 
                $srcHeight
            );
            
            // Save the image to a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'img');
            $this->saveImageToFile($newImage, $tempFile, $image->getClientOriginalExtension());
            
            // Save the image to storage
            Storage::disk('public')->put($path, file_get_contents($tempFile));
            
            // Clean up
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            unlink($tempFile);
            
            return $path;
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create an image resource from an uploaded file
     * 
     * @param UploadedFile $file
     * @return \GdImage|false
     */
    private function createImageFromFile(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getPathname();
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($path);
            case 'png':
                return imagecreatefrompng($path);
            case 'gif':
                return imagecreatefromgif($path);
            case 'webp':
                return imagecreatefromwebp($path);
            default:
                return false;
        }
    }
    
    /**
     * Save an image resource to a file
     * 
     * @param \GdImage $image
     * @param string $path
     * @param string $extension
     * @return bool
     */
    private function saveImageToFile(\GdImage $image, string $path, string $extension): bool
    {
        $extension = strtolower($extension);
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagejpeg($image, $path, 80); // 80% quality
            case 'png':
                return imagepng($image, $path, 8); // Compression level 8
            case 'gif':
                return imagegif($image, $path);
            case 'webp':
                return imagewebp($image, $path, 80); // 80% quality
            default:
                return false;
        }
    }
    
    /**
     * Delete an image from storage
     *
     * @param string|null $path
     * @return bool
     */
    public function deleteImage(?string $path): bool
    {
        if (!$path) {
            return false;
        }
        
        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Get the full URL for an image
     *
     * @param string|null $path
     * @param string $default
     * @return string
     */
    public function getImageUrl(?string $path, string $default = 'images/placeholder.jpg'): string
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        
        return asset($default);
    }
} 