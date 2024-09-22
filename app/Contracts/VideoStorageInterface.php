<?php

namespace App\Contracts;

interface VideoStorageInterface
{
    public function upload($file, $path, $name);

    public function deletefile($fileName,$lessonId);

    public function deleteDirectory($lessonId);

    public function retrieveVideo($fileName, $lessonId);
}
