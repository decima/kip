<?php


namespace App\Services\FileManipulation;


use Symfony\Component\Finder\Finder;

class FileLister
{
    /**
     * @var FileResolver
     */
    private $fileResolver;

    /**
     * @var MetadataManager
     */
    private $metadataManager;

    /**
     * @var FileReader
     */
    private $fileReader;

    public $indexedFiles = [];

    /**
     * FileLister constructor.
     * @param FileResolver    $fileResolver
     * @param MetadataManager $metadataManager
     */
    public function __construct(FileResolver $fileResolver, MetadataManager $metadataManager, FileReader $fileReader)
    {
        $this->fileResolver = $fileResolver;
        $this->metadataManager = $metadataManager;
        $this->fileReader = $fileReader;
    }

    public function listAllFiles($path = "")
    {
        $finder = new Finder();
        $finder->sortByType();
        $finder->in($this->fileResolver->getBasePath() . $path);
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
            if (!$file->isDir() && $file->getExtension() === "md") {
                $refs[$filePath]->content = $file->getContents();
                $refs[$filePath]->mime = mime_content_type($file->getPathname());
                $fileContentAsPlainText = preg_replace("/<[^>]+>/", " ", $refs[$filePath]->content);
                $indexedFile = new IndexedFile();
                $indexedFile->title = $refs[$filePath]->name;
                $indexedFile->content = $fileContentAsPlainText;
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
