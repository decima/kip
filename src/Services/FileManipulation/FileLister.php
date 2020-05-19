<?php


namespace App\Services\FileManipulation;


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
     * @param FileResolver $fileResolver
     * @param MetadataManager $metadataManager
     */
    public function __construct(FileResolver $fileResolver, MetadataManager $metadataManager, FileReader $fileReader)
    {
        $this->fileResolver = $fileResolver;
        $this->metadataManager = $metadataManager;
        $this->fileReader = $fileReader;
    }


    public function listAllFiles($path = "", Page &$parent = null)
    {
        $fullPath = $this->fileResolver->getBasePath() . $path;
        $files = scandir($fullPath);
        $parent->isFolder = true;
        $parent->path = $path;
        foreach ($files as $key => $value) {
            $fullFolderPath = realpath($fullPath . DIRECTORY_SEPARATOR . $value);
            $newPath = str_replace($this->fileResolver->getBasePath(), "", $fullFolderPath);
            $item = new Page();
            $item->path = $newPath;
            $item->metadata = $this->metadataManager->getMetadataForDocument($item->path);
            $item->name = $item->metadata->getDocumentName();
            if (!is_dir($fullFolderPath)) {
                if ($this->fileResolver->isMarkdownFile($newPath)) {
                    $item->mime = "text/markdown";
                    $item->name = str_replace(".md", "", $item->name);
                    if ($item->name !== "readme") {
                        $item->isFolder = false;
                        $parent->subLinks[] = $item;
                    } else {
                        $parent->hasReadme = true;
                    }

                    $file = $this->fileReader->readFile($newPath, $fullFolderPath);
                    $this->fileReader->parseMarkdown($file);

                    $fileContentAsPlainText = preg_replace("/<[^>]+>/", " ", $file->content);
                    $indexedFile = new IndexedFile();
                    $indexedFile->title = $item->name;
                    $indexedFile->content = $fileContentAsPlainText;
                    $indexedFile->webpath = $newPath;
                    $this->indexedFiles[] = $indexedFile;
                } else {
                    if (strpos($item->name, ".") !== 0) {
                        $item->mime = mime_content_type($fullFolderPath);
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
