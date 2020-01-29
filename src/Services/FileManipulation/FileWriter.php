<?php


namespace App\Services\FileManipulation;


class FileWriter
{

    public function createParentFolder($path)
    {
        $directory = dirname($path);
        @mkdir($directory, 0777, true);
    }

    public function writeFile($path, $content)
    {
        file_put_contents($path, $content);
    }

    public function deleteFile($delete){
        //@todo implement this

    }
    public function deleteFolder($path){
        //@todo implement this

    }
}