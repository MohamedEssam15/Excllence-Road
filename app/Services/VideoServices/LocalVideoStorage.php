<?php

namespace App\Services\VideoServices;

use App\Contracts\VideoStorageInterface;
use Illuminate\Support\Facades\Storage;

class LocalVideoStorage implements VideoStorageInterface
{
    public function upload($file, $path, $name)
    {
        return Storage::disk('public')->putFileAs($path, $file, $name);
    }

    public function deletefile($fileName,$lessonId)
    {
        $path = 'lessons/lessons_videos/' . $lessonId . '/' . $fileName;
        return Storage::disk('public')->delete($path);
    }

    public function deleteDirectory($lessonId)
    {
        $path = 'lessons/lessons_videos/' . $lessonId . '/';
        return Storage::disk('public')->deleteDirectory($path);
    }

    public function retrieveVideo($fileName, $lessonId)
    {
        $path = 'lessons/lessons_videos/' . $lessonId . '/' . $fileName;
        return Storage::disk('public')->url($path);
    }
}
