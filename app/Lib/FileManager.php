<?php

namespace App\Lib;

use App\Constants\FileInfo;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FileManager {
    /*
    |--------------------------------------------------------------------------
    | File Manager
    |--------------------------------------------------------------------------
    |
    | FileManager class is using to manage edit, update, remove files. Developer
    | can manage any kind of files from here. But some limitations is here for image.
    | This class using a trait to manage the file paths and sizes. Developer can also
    | use this class as a helper function.
    |
     */

    protected $file;
    public $path;
    public $size;
    protected $isImage;
    public $thumb;
    public $old;
    public $filename;

    public function __construct($file = null) {
        $this->file = $file;
        if ($file) {
            $imageExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            if (in_array($file->getClientOriginalExtension(), $imageExtensions)) {
                $this->isImage = true;
            } else {
                $this->isImage = false;
            }
        }
    }

    public function upload() {
        $path = $this->makeDirectory();
        if (!$path) {
            throw new \Exception('File could not been created.');
        }
        if ($this->old) {
            $this->removeFile();
        }
        if (!$this->filename) {
            $this->filename = $this->getFileName();
        }
        if ($this->isImage == true) {
            $this->uploadImage();
        } else {
            $this->uploadFile();
        }
    }

    protected function uploadImage() {
        $manager = new ImageManager(new Driver());
        $image   = $manager->read($this->file);
        if ($this->size) {
            $size = explode('x', strtolower($this->size));
            $image->resize($size[0], $size[1]);
        }

        $fullPath = rtrim($this->path, '/') . '/' . $this->filename;

        if (gs('is_storage')) {
            try {
                $content = $image->encode()->toString();
                Storage::disk('r2')->put($fullPath, $content);
            } catch (\Exception $e) {
                Log::error("R2 Image Upload Failed: " . $e->getMessage());
                // Fallback to local if R2 fails
                $image->save($this->path . '/' . $this->filename);
            }
        } else {
            $image->save($this->path . '/' . $this->filename);
        }

        if ($this->thumb) {
            if ($this->old) {
                $this->removeFile($this->path . '/thumb_' . $this->old);
            }
            $thumb = explode('x', $this->thumb);
            $thumbImage = $manager->read($this->file)->resize($thumb[0], $thumb[1]);
            $thumbPath = rtrim($this->path, '/') . '/thumb_' . $this->filename;

            if (gs('is_storage')) {
                try {
                    Storage::disk('r2')->put($thumbPath, $thumbImage->encode()->toString());
                } catch (\Exception $e) {
                    $thumbImage->save($this->path . '/thumb_' . $this->filename);
                }
            } else {
                $thumbImage->save($this->path . '/thumb_' . $this->filename);
            }
        }
    }

    protected function uploadFile() {
        $fullPath = rtrim($this->path, '/') . '/' . $this->filename;
        if (gs('is_storage')) {
            try {
                Storage::disk('r2')->put($fullPath, file_get_contents($this->file));
            } catch (\Exception $e) {
                Log::error("R2 File Upload Failed: " . $e->getMessage());
                $this->file->move($this->path, $this->filename);
            }
        } else {
            $this->file->move($this->path, $this->filename);
        }
    }

    public function makeDirectory($location = null) {
        if (!$location) {
            $location = $this->path;
        }
        if (file_exists($location)) {
            return true;
        }
        return mkdir($location, 0755, true);
    }

    public function removeDirectory($location = null) {
        if (!$location) {
            $location = $this->path;
        }
        if (!is_dir($location)) {
            throw new \InvalidArgumentException("$location must be a directory");
        }
        if (substr($location, strlen($location) - 1, 1) != '/') {
            $location .= '/';
        }
        $files = glob($location . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                static::removeDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($location);
    }

    public function removeFile($path = null) {
        if (!$path) {
            $path = $this->path . '/' . $this->old;
        }

        if (gs('is_storage')) {
            try {
                Storage::disk('r2')->delete($path);
                if ($this->thumb) {
                    Storage::disk('r2')->delete($this->path . '/thumb_' . $this->old);
                }
            } catch (\Exception $e) {
                Log::error("R2 File Deletion Failed: " . $e->getMessage());
            }
        }

        // Always try to remove local as well in case it's there
        if (file_exists($path) && is_file($path)) {
            @unlink($path);
        }
        
        if ($this->thumb) {
            $thumbPath = $this->path . '/thumb_' . $this->old;
            if (file_exists($thumbPath) && is_file($thumbPath)) {
                @unlink($thumbPath);
            }
        }
    }

    protected function getFileName() {
        return uniqid() . time() . '.' . $this->file->getClientOriginalExtension();
    }

    public function __call($method, $args) {
        $fileInfo  = new FileInfo;
        $filePaths = $fileInfo->fileInfo();
        if (array_key_exists($method, $filePaths)) {
            $path = json_decode(json_encode($filePaths[$method]));
            return $path;
        } else {
            if (method_exists($this, $method)) {
                $this->$method(...$args);
            } else {
                throw new \Exception("File key or method: $method doesn't exists.");
            }
        }
    }

    public static function __callStatic($method, $args) {
        $selfClass = new self;
        $selfClass->$method(...$args);
    }

}
