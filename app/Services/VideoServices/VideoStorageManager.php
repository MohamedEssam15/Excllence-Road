<?php

namespace App\Services\VideoServices;



class VideoStorageManager
{
    protected $storage;

    public function __construct()
    {
        $storage = match (config('videostorage.video_storage')) {
            // 'google' => new GoogleCloudVideoStorage(),
            // 'vimeo' => new VimeoVideoStorage(),
            'local' => new LocalVideoStorage()
        };
        $this->storage = $storage;
    }

    public function upload($file, $path, $name)
    {
        return $this->storage->upload($file, $path, $name);
    }

    public function deletefile($fileName,$lessonId)
    {
        return $this->storage->deletefile($fileName,$lessonId);
    }
    public function deleteDirectory($lessonId)
    {
        return $this->storage->deleteDirectory($lessonId);
    }
    public function retrieveVideo($fileName, $lessonId)
    {
        return $this->storage->retrieveVideo($fileName, $lessonId);
    }
}
