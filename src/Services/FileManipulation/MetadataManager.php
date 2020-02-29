<?php


namespace App\Services\FileManipulation;


class MetadataManager
{
    const FILE_INDEX_NAME = ".index";
    /**
     * @var FileResolver
     */
    private $fileResolver;

    private $index = [];

    /**
     * MetadataManager constructor.
     * @param FileResolver $fileResolver
     */
    public function __construct(FileResolver $fileResolver)
    {
        $this->fileResolver = $fileResolver;
        $this->loadIndex();

    }


    private function getFilePath()
    {
        return $this->fileResolver->getBasePath() . "/" . self::FILE_INDEX_NAME;
    }

    private function loadIndex()
    {

        $handle = @fopen($this->getFilePath(), "c+");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $items = explode(" => ", $buffer);
                if (count($items) == 2) {
                    $content = json_decode(trim($items[1]), true);
                    $this->index[trim($items[0])] = Metadata::fromArray($content);
                }
            }
            if (!feof($handle)) {
                throw  new \Exception("cannot read full file");
            }
            fclose($handle);
        }
    }

    public function saveIndex()
    {
        $handle = fopen($this->getFilePath(), "w+");
        if ($handle) {
            foreach ($this->index as $file => $meta) {
                fputs($handle, implode(" => ", [$file, json_encode($meta)]) . "\n");

            }
            fclose($handle);
        }
    }

    public function unsetMetadata($path)
    {
        unset($this->index[$path]);
        $this->saveIndex();
    }

    public function setMetadataForDocument($path, Metadata $meta)
    {
        $meta->setPath($path);
        $this->index[$path] = $meta;
        $this->saveIndex();
    }

    public function getMetadataForDocument($path): Metadata
    {
        if (isset($this->index[$path])) {
            return $this->index[$path];
        } else {
            return (new Metadata())->setPath($path);
        }
    }



}