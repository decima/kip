<?php


namespace App\Services\TreeLoader;


use App\Services\FileLoader\FileLoader;
use App\Services\FileLoader\MarkdownFile;
use App\Services\FileManipulation\IndexedFile;
use App\Services\FileManipulation\MetadataManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class TreeLoader
{
    private $storage;
    public $indexedFiles = [];

    /**
     * @required
     */
    public MetadataManager $metadataManager;
    /**
     * @required
     */
    public FileLoader $fileLoader;

    public function __construct(ParameterBagInterface $bag)
    {
        $this->storage = $bag->get("storage");
    }

    public function listAllFiles()
    {
        $finder = new Finder();
        $finder->sortByType();
        $finder->in($this->storage);
        $refs = [];
        $initial = new Page();
        foreach ($finder as $file) {
            if ($file->getExtension() !== "md" && !$file->isDir()) {
                continue;
            }
            $filePath = $file->getRelativePathname();
            $refs[$filePath] = new Page();
            $refs[$filePath]->path = $filePath;
            $refs[$filePath]->isFolder = $file->isDir();
            $refs[$filePath]->metadata = $this->metadataManager->getMetadataForDocument($file->getRelativePathname());
            $refs[$filePath]->name = $file->getFilenameWithoutExtension();
            if (
                !$file->isDir()
                && $file->getExtension() === "md"
                && ($md = $this->fileLoader->loadFileByPath($file->getRelativePathname())) instanceof MarkdownFile
            ) {
                ;
                $refs[$filePath]->content = $md->content;
                $refs[$filePath]->mime = mime_content_type($file->getPathname());

                $indexedFile = new IndexedFile();
                $indexedFile->title = $refs[$filePath]->name;
                $indexedFile->content = $md->getStrippedContent();
                $indexedFile->webpath = $file->getRelativePathname();
                $this->indexedFiles[] = $indexedFile;
            } elseif ($file->isDir()) {
                $refs[$filePath]->path = $filePath . "/readme.md";

            }
            if ($file->getRelativePath() === "") {
                $refs[$filePath]->webpath = "/readme.md";

                $initial->subLinks[] = $refs[$filePath];
                continue;
            }

            if ($refs[$filePath]->name === "readme") {
                $refs[$file->getRelativePath()]->hasReadme = true;
            } else {
                $refs[$file->getRelativePath()]->subLinks[] = $refs[$filePath];
            }
        }
        return $initial;
    }
}