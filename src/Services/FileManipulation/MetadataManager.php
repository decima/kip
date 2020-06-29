<?php


namespace App\Services\FileManipulation;


use App\Services\FileLoader\Metadata;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MetadataManager
{
    const FILE_INDEX_NAME = ".index";

    private $storage;
    private $index = [];
    private SerializerInterface $serializer;

    public function __construct(ParameterBagInterface $parameterBag, SerializerInterface $serializer)
    {
        $this->storage = $parameterBag->get("storage");
        $this->serializer = $serializer;
        $this->loadIndex();

    }


    private function getFilePath()
    {
        return $this->storage . self::FILE_INDEX_NAME;
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