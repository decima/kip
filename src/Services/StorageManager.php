<?php


namespace App\Services;


use App\Entity\PageLink;

class StorageManager
{
    private $storagePath;

    /**
     * StorageManipulation constructor.
     * @param $storagePath
     */
    public function __construct($storagePath)
    {
        $this->storagePath = realpath($storagePath) . "/";
        $this->makeDir();
    }

    public function listAllFiles($path = "", PageLink &$parent)
    {
        $fullPath         = $this->storagePath . $path;
        $files            = scandir($fullPath);
        $parent->isFolder = true;
        $parent->path     = $path;
        foreach ($files as $key => $value) {
            $fullFolderPath = realpath($fullPath . DIRECTORY_SEPARATOR . $value);
            $newPath        = str_replace($this->storagePath, "", $fullFolderPath);
            $item           = new PageLink();
            $item->path     = $newPath;
            $item->name     = $value;


            if (!is_dir($fullFolderPath)) {
                if ($this->pathIsMd($newPath)) {

                    $item->name = str_replace(".md", "", $item->name);
                    if ($item->name !== "readme") {
                        $item->isFolder     = false;
                        $parent->subLinks[] = $item;
                    } else {
                        $parent->hasReadme = true;
                    }
                }
            } else if ($value != "." && $value != "..") {
                $this->listAllFiles($newPath, $item);
                $parent->subLinks[] = $item;

            } else {
                continue;
            }


        }


        return $parent;
    }

    public function fileExists($path)
    {
        return file_exists($this->storagePath . $path);
    }

    public function isFile($path)
    {
        return $this->fileExists($path) && !is_dir($this->storagePath . $path);
    }

    public function pathIsMd($path)
    {
        return (substr($path, -3) === ".md");
    }

    public function getFilePath($path)
    {
        return $this->storagePath . $path;
    }

    public function isFolder($path)
    {
        return $this->fileExists($path) && is_dir($this->storagePath . $path);
    }


    public function getFileContent($fileName)
    {
        $content = @file_get_contents($this->storagePath . $fileName);
        return $content ? $content : "";
    }


    public function storeFileContent($fileName, $content)
    {
        if ($this->isFolder($fileName)) {
            $fileName .= "/readme.md";
        }
        $folder = dirname($this->storagePath . $fileName);
        @mkdir($folder, 0777, true);
        return file_put_contents($this->storagePath . $fileName, $content);
    }

    private static function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function dropFile($fileName)
    {
        if ($this->isFolder($fileName)) {
            self::delTree($this->storagePath . $fileName);
        } else {
            unlink($this->storagePath . $fileName);
        }

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