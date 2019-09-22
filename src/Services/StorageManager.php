<?php


namespace App\Services;


class StorageManager
{
    private $storagePath;

    /**
     * StorageManipulation constructor.
     * @param $storagePath
     */
    public function __construct($storagePath)
    {
        $this->storagePath = $storagePath;
        $this->makeDir();
    }

    public function listAllFiles()
    {

    }

    public function fileExists($path)
    {
        return file_exists($this->storagePath . "/" . $path);
    }

    public function isFile($path)
    {
        return $this->fileExists($path) && !is_dir($this->storagePath . "/" . $path);
    }

    public function pathIsMd($path)
    {
            return (substr($path, -3) === ".md");
    }

    public function getFilePath($path)
    {
        return $this->storagePath . "/" . $path;
    }

    public function isFolder($path)
    {
        return $this->fileExists($path) && is_dir($this->storagePath . "/" . $path);
    }


    public function getFileContent($fileName)
    {
        $content = @file_get_contents($this->storagePath . "/" . $fileName);
        return $content ? $content : "";
    }

    public function storeFileContent($fileName, $content)
    {

    }


    private function makeDir()
    {
        @mkdir($this->storagePath, 0777, true);
    }


    public function GetStoragePath()
    {
        return $this->storagePath;
    }
}