<?php


namespace App\Services\FileLoader;


use Symfony\Component\Serializer\SerializerInterface;

class MetadataManager
{
    const FILE_INDEX_NAME = "/.index";

    private $index = [];
    private SerializerInterface $serializer;
    private FileLoader $fileLoader;

    public function __construct(SerializerInterface $serializer, FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
        $this->serializer = $serializer;
        $this->loadIndex();

    }

    public function getFilePath()
    {
        return $this->fileLoader->getFile(self::FILE_INDEX_NAME)->getPathname();
    }

    private function loadIndex()
    {

        $handle = @fopen($this->getFilePath(), "c+");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $items = explode(" => ", $buffer);
                if (count($items) == 2) {
                    $metadata = $this->serializer->deserialize($items[1], Metadata::class, "json");
                    $metadata->path = $items[0];
                    $this->index[trim($items[0])] = $metadata;
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
        $handle = fopen($this->getFilePath(), "w");
        if ($handle) {
            foreach ($this->index as $file => $meta) {
                $jsonMeta = $this->serializer->serialize($meta, "json");
                fputs($handle, implode(" => ", [$file, $jsonMeta]) . "\n");

            }
            fclose($handle);
        } else {
            throw new \Exception("POOPY");
        }
    }

    public function unsetMetadata($path)
    {
        unset($this->index[$path]);
        $this->saveIndex();
    }

    public function setMetadataForDocument($path, Metadata $meta)
    {
        $meta->path = $path;
        $this->index[$path] = $meta;
        $this->saveIndex();
    }

    public function getMetadataForDocument($path): Metadata
    {
        if (isset($this->index[$path])) {
            return $this->index[$path];
        } else {
            $meta = new Metadata();
            $meta->path = $path;
            return $meta;
        }
    }


}