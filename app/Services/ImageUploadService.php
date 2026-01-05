<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Upload image to public/uploads/{year}/{month} directory
     * 
     * @param UploadedFile $file
     * @param string $prefix Prefix for filename (e.g., mod title, username)
     * @return string Relative path from public directory
     */
    public function uploadImage(UploadedFile $file, string $prefix = 'image'): string
    {
        $year = date('Y');
        $month = date('m');
        $uniqueId = Str::random(10);
        $extension = $file->getClientOriginalExtension();
        
        // Sanitize prefix
        $sanitizedPrefix = Str::slug($prefix);
        
        // Generate filename: prefix_uniqueid.extension
        $filename = "{$sanitizedPrefix}_{$uniqueId}.{$extension}";
        
        // Path: uploads/{year}/{month}/filename
        $path = "uploads/{$year}/{$month}/{$filename}";
        
        // Ensure directory exists
        $directory = public_path("uploads/{$year}/{$month}");
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Store in public disk
        $file->move($directory, $filename);
        
        return $path;
    }
    
    /**
     * Upload avatar to public/uploads/avatars directory
     * 
     * @param UploadedFile $file
     * @param string $username
     * @return string Relative path from public directory
     */
    public function uploadAvatar(UploadedFile $file, string $username): string
    {
        $uniqueId = Str::random(10);
        $extension = $file->getClientOriginalExtension();
        
        // Sanitize username
        $sanitizedUsername = Str::slug($username);
        
        // Generate filename: username_uniqueid.extension
        $filename = "{$sanitizedUsername}_{$uniqueId}.{$extension}";
        
        // Path: uploads/avatars/filename
        $path = "uploads/avatars/{$filename}";
        
        // Ensure directory exists
        $directory = public_path('uploads/avatars');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Store file
        $file->move($directory, $filename);
        
        return $path;
    }
    
    /**
     * Delete image file
     * 
     * @param string $path Relative path from public directory
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        $fullPath = public_path($path);
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
    
    /**
     * Get URL for image
     * 
     * @param string $path Relative path from public directory
     * @return string
     */
    public function getImageUrl(string $path): string
    {
        return asset($path);
    }

    /**
     * Upload logo or favicon to public/uploads/logo directory
     * 
     * @param UploadedFile $file
     * @return string Relative path from public directory
     */
    public function uploadLogo(UploadedFile $file): string
    {
        $uniqueId = uniqid();
        $extension = $file->getClientOriginalExtension();
        
        // Generate filename: unique_id.extension
        $filename = "{$uniqueId}.{$extension}";
        
        // Path: uploads/logo/filename
        $path = "uploads/logo/{$filename}";
        
        // Ensure directory exists
        $directory = public_path('uploads/logo');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Store file
        $file->move($directory, $filename);
        
        return $path;
    }

    /**
     * Upload favicon to public/uploads/logo directory
     * 
     * @param UploadedFile $file
     * @return string Relative path from public directory
     */
    public function uploadFavicon(UploadedFile $file): string
    {
        // Re-use logo upload logic as user requested both in same folder with same format
        return $this->uploadLogo($file);
    }
}

