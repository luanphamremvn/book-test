<?php

namespace App\Services;

use Exception;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Psr\Http\Message\StreamInterface;

class UploadFileService
{
    /**
     * The filesystem disk used for file uploads.
     *
     * @var FilesystemAdapter
     */
    private FilesystemAdapter $disk;

    public function __construct()
    {
        $disk = config('filesystems.default');
        $this->disk = Storage::disk($disk);
    }

    /**
     * Upload a file to the specified path.
     *
     * @param string $path
     * @param string|File|StreamInterface|UploadedFile $contents
     * @return string|bool
     */
    public function uploadFile(string $path, File|StreamInterface|string|UploadedFile $contents): string|bool
    {
        $file = $this->disk->put($path, file_get_contents($contents));

        // Check if the file was upload error
        if ($file === false) {
            // Log the error message
            Log::error("Upload file:" . $path);

            // Throw an exception with a detailed message
            throw new Exception(sprintf("['filesystem'] %s %s", "Error", "Upload file:" . $path));
        }

        return $this->handleFileOperation($file, "Upload file:" . $path);
    }

    /**
     * Delete a file from the specified path.
     *
     * @param string $path
     * @return bool
     * @throws Exception
     */
    public function deleteFile(string $path): bool
    {
        $file = $this->disk->delete($path);

        return $this->handleFileOperation($file, "Delete file:" . $path);
    }

    /**
     * Get the URL of a file from the specified path.
     *
     * @param string $path
     * @return string|null
     * @throws Exception
     */
    public function getFile(string $path): string|null
    {
        $file = $this->disk->url($path);

        if ($file === false) {
            Log::error("Get file:" . $path);

            // Throw an exception with a detailed message
            throw new Exception(sprintf("['filesystem'] %s %s", "Error", "Get file:" . $path));
        }

        // Check if the file exists
        return $this->handleFileOperation($file, "Get file:" . $path);
    }

    /**
     * Get the URL of a no-image placeholder.
     *
     * @return string
     */
    public function getNoImageUrl(): string
    {
        return asset('assets/images/no_image.jpeg');
    }

    /**
     * Handle file operations and log errors if necessary.
     *
     * @param string|bool|null $file
     * @param string $message
     * @return bool|Exception|string
     */
    private function handleFileOperation(string|bool|null $file, string $message): string|bool|null
    {
        if ($file === false) {
            Log::error($message);

            // Throw an exception with a detailed message
            throw new Exception(sprintf("['filesystem'] %s %s", "Error", $message));
        }

        return $file;
    }
}
