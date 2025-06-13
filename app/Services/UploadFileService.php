<?php
namespace App\Services;

use Exception;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Psr\Http\Message\StreamInterface;

class UploadFileService {

    /**
     *
     * @var FileSystem
     */
    private FileSystem $disk;

    public function __construct() {
        $disk = env("FILESYSTEM_DISK");
        $this->disk = Storage::disk($disk);
    }

    /**
     * Summary of uploadFile
     * @param string $path
     * @param string|File|StreamInterface|UploadedFile $contents
     * @return string|bool
     */
    public function uploadFile(string $path, File|StreamInterface|string|UploadedFile $contents):string|bool {
        $file = $this->disk->put($path, file_get_contents($contents));
        // Check if the file was upload error
        if ($file === false) {
            // Log the error message
            Log::error("Upload file:".$path);
            return new Exception(sprintf("['filesystem'] %s %s","Error", "Upload file:".$path));
        }
        return $this->handleFileOperation($file, "Upload file:".$path);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path):bool {

        $file = $this->disk->delete($path);
        return $this->handleFileOperation($file, "Delete file:".$path);
    }

    /**
     * @param string $path
     * @return string|null
     */
    public function getFile(string $path):string|null {

        $file = $this->disk?->url($path);
        return $this->handleFileOperation($file, "Get file:".$path);
    }

    /**
     * @return string
     */
    public function getNoImageUrl():string {

        return asset('assets/images/no_image.jpeg');
    }

    /**
     * Summary of handleFileOperation
     * @param string|bool|null $file
     * @param string $message
     * @return bool|Exception|string
     */
    private function handleFileOperation(string|bool|null $file, string $message):string|bool|null {

        if($file === false){
            Log::error($message);
            return new Exception(sprintf("['filesystem'] %s %s","Error", $message));
        }
        return $file;
    }
}
