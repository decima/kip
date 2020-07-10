<?php


namespace App\Services\FileLoader;


use App\Services\InternalSettings;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\SplFileInfo;

class FileLoader
{
    private $storage;
    private $markdown;

    public function __construct(ParameterBagInterface $bag, MarkdownParser $markdown)
    {
        $this->storage = $bag->get("storage");
        $this->markdown = $markdown;

    }

    public function loadFileByPath($path): File
    {
        $file = new File();
        $fileInfo = $this->getFile($path);
        if ($fileInfo->isDir()) {
            return $this->loadFileByPath($fileInfo->getRelativePathname() . "/" . InternalSettings::DEFAULT_INDEX_FILE . ".md");
        }
        if (!$fileInfo->isFile()) {
            $file = new EmptyFile();
        } elseif (strtolower($fileInfo->getExtension()) === "md") {
            $file = new MarkdownFile();
            $file->fileInfo = $fileInfo;
            $file->markdownContent = $fileInfo->getContents();
            $file = $this->markdown->parse($file);

        }
        $file->webpath = $fileInfo->getRelativePathname();
        $file->path = $fileInfo->getPathname();
        $file->fileInfo = $fileInfo;
        return $file;
    }

    public function getFile($path): SplFileInfo
    {
        $path = trim($path, "/");
        $dirname = dirname($path) == "." ? "" : dirname($path);
        return new SplFileInfo($this->storage . "/" . $path, $dirname, $path);

    }
}
