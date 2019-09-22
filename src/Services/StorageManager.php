<?php


namespace App\Services;


use App\Entity\Page;

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

    public function listAllFiles($path = "", Page &$parent)
    {
        $fullPath         = $this->storagePath . $path;
        $files            = scandir($fullPath);
        $parent->isFolder = true;
        $parent->path     = $path;
        foreach ($files as $key => $value) {
            $fullFolderPath = realpath($fullPath . DIRECTORY_SEPARATOR . $value);
            $newPath        = str_replace($this->storagePath, "", $fullFolderPath);
            $item           = new Page();
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
            } else if ($value != "." && $value != ".." && strpos($value, ".") !== 0) {
                $this->listAllFiles($newPath, $item);
                $parent->subLinks[] = $item;

            }
        }
        usort($parent->subLinks, function (Page $page, Page $page2) {
            if ($page->isFolder && !$page2->isFolder) {
                return true;
            } elseif ($page2->isFolder && !$page->isFolder) {
                return false;

            } else {
                return $page->name > $page2->name;
            }

        });


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

    private static function delTree($dir, $dept = 5)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            if ($dept > 0) {
                (is_dir("$dir/$file")) ? self::delTree("$dir/$file", $dept - 1) : unlink("$dir/$file");
            }
        }
        return rmdir($dir);
    }

    public function dropFile($fileName, $uncount = 3)
    {

        if ($this->isFolder($fileName)) {
            self::delTree($this->storagePath . $fileName);
        } elseif ($this->fileExists($fileName)) {
            unlink($this->storagePath . $fileName);
        } else {
            if ($uncount > 0) {
                $this->dropFile(dirname($this->storagePath . $fileName));
            }
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