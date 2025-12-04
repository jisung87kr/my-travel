<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    private ImageManager $manager;
    private int $maxWidth = 1200;
    private int $quality = 80;
    private int $thumbWidth = 300;
    private int $thumbHeight = 200;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Store and optimize an uploaded image
     */
    public function store(UploadedFile $file, string $directory = 'images'): string
    {
        // Generate unique filename
        $filename = Str::uuid() . '.jpg';
        $path = $directory . '/' . $filename;

        // Read and process image
        $image = $this->manager->read($file);

        // Resize if too large (maintain aspect ratio)
        if ($image->width() > $this->maxWidth) {
            $image->scale(width: $this->maxWidth);
        }

        // Encode as JPEG with quality
        $encoded = $image->toJpeg($this->quality);

        // Store the processed image
        Storage::disk('public')->put($path, $encoded);

        return $path;
    }

    /**
     * Generate a thumbnail for an image
     */
    public function generateThumbnail(string $sourcePath): string
    {
        $thumbPath = $this->getThumbnailPath($sourcePath);

        if (Storage::disk('public')->exists($thumbPath)) {
            return $thumbPath;
        }

        $image = $this->manager->read(Storage::disk('public')->get($sourcePath));

        // Fit to thumbnail size (crop to fill)
        $image->cover($this->thumbWidth, $this->thumbHeight);

        $encoded = $image->toJpeg($this->quality);

        Storage::disk('public')->put($thumbPath, $encoded);

        return $thumbPath;
    }

    /**
     * Store image with thumbnail generation
     */
    public function storeWithThumbnail(UploadedFile $file, string $directory = 'images'): array
    {
        $path = $this->store($file, $directory);
        $thumbPath = $this->generateThumbnail($path);

        return [
            'path' => $path,
            'thumb_path' => $thumbPath,
        ];
    }

    /**
     * Delete an image and its thumbnail
     */
    public function delete(string $path): bool
    {
        $deleted = Storage::disk('public')->delete($path);

        $thumbPath = $this->getThumbnailPath($path);
        if (Storage::disk('public')->exists($thumbPath)) {
            Storage::disk('public')->delete($thumbPath);
        }

        return $deleted;
    }

    /**
     * Get URL for an image path
     */
    public function getUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Get thumbnail URL for an image path
     */
    public function getThumbnailUrl(string $path): string
    {
        $thumbPath = $this->getThumbnailPath($path);

        // Generate thumbnail if it doesn't exist
        if (!Storage::disk('public')->exists($thumbPath) && Storage::disk('public')->exists($path)) {
            $this->generateThumbnail($path);
        }

        return Storage::disk('public')->url($thumbPath);
    }

    /**
     * Get thumbnail path from original path
     */
    private function getThumbnailPath(string $path): string
    {
        $pathInfo = pathinfo($path);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . ($pathInfo['extension'] ?? 'jpg');
    }

    /**
     * Set max width for image resizing
     */
    public function setMaxWidth(int $width): self
    {
        $this->maxWidth = $width;
        return $this;
    }

    /**
     * Set quality for JPEG encoding
     */
    public function setQuality(int $quality): self
    {
        $this->quality = max(1, min(100, $quality));
        return $this;
    }

    /**
     * Set thumbnail dimensions
     */
    public function setThumbnailSize(int $width, int $height): self
    {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;
        return $this;
    }

    /**
     * Validate image file
     */
    public function validate(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        return in_array($file->getMimeType(), $allowedMimes)
            && $file->getSize() <= $maxSize;
    }

    /**
     * Get image dimensions
     */
    public function getDimensions(string $path): array
    {
        $image = $this->manager->read(Storage::disk('public')->get($path));

        return [
            'width' => $image->width(),
            'height' => $image->height(),
        ];
    }
}
