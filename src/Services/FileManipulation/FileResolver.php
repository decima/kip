<?php


namespace App\Services\FileManipulation;


use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use App\Services\FileManipulation\Page;

class FileResolver
{

    private $basepath = "/";

    /**
     * FileResolver constructor.
     * @param string $basepath
     */
    public function __construct($basepath)
    {
        $this->basepath = $this->addTrailingSlash($basepath);
    }

    /**
     * @param $path path of the file to be accessed
     */
    public function getFile(string $path, $throwIfNotExists = true)
    {
        $fullPath = $this->removeTrailingSlash($this->basepath . $path);
        if (file_exists($fullPath) && !is_dir($fullPath)) {
            return $fullPath;
        }
        $resolved = $this->resolveFullPathFile($fullPath);
        if ($throwIfNotExists && !file_exists($resolved)) {
            throw new FileNotExistsException($resolved);
        }
        return $resolved;
    }

    public function isMarkdownFile($path)
    {
        return substr($path, -3) === ".md";
    }

    private function addTrailingSlash($str)
    {
        return rtrim($str, "/") . "/";
    }

    private function removeTrailingSlash($str)
    {
        return rtrim($str, "/");
    }

    private function resolveFullPathFile($path): ?string
    {
        if ($this->isMarkdownFile($path)) {
            return $path;
        }
        if (is_dir($path)) {
            return $this->resolveFullPathFile($path . $this->resolveReadmeExistance($path));
        }
        if (!file_exists($path)) {
            return $path . $this->resolveReadmeExistance($path);

        }
        return $path;
    }

    private function resolveReadmeExistance($path): ?string
    {
        $files = glob($path . "/*");
        $extracts = array_values(preg_grep('/readme\.md$/i', $files));
        rsort($extracts);
        if (count($extracts) > 0) {
            return "/" . basename($extracts[0]);
        }
        return "/readme.md";

    }

    public function listAllFiles($path = "", Page &$parent = null)
    {
        $fullPath = $this->basepath . $path;
        $files = scandir($fullPath);
        $parent->isFolder = true;
        $parent->path = $path;
        foreach ($files as $key => $value) {
            $fullFolderPath = realpath($fullPath . DIRECTORY_SEPARATOR . $value);
            $newPath = str_replace($this->basepath, "", $fullFolderPath);
            $item = new Page();
            $item->path = $newPath;
            $item->name = $value;
            if (!is_dir($fullFolderPath)) {
                if ($this->isMarkdownFile($newPath)) {
                    $item->mime = "text/markdown";
                    $item->name = str_replace(".md", "", $item->name);
                    if ($item->name !== "readme") {
                        $item->isFolder = false;
                        $parent->subLinks[] = $item;
                    } else {
                        $parent->hasReadme = true;
                    }
                } else {
                    if (strpos($item->name, ".") !== 0) {
                        $item->mime = mime_content_type($fullFolderPath);
                        // $parent->subLinks[] = $item;
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
            } else if ($page2->isFolder && !$page->isFolder) {
                return false;

            } else {
                return $page->name > $page2->name;
            }

        });


        return $parent;

    }
}