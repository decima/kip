<?php


namespace App\Services\FileManipulation;


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

    public function getBasePath()
    {
        return $this->basepath;
    }

    /**
     * @param $path path of the file to be accessed
     */
    public function getFile(string $path, $throwIfNotExists = true)
    {
        $path = ltrim($path, "/");
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


}
