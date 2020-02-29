<?php


namespace App\Services\FileManipulation;


class Metadata implements \JsonSerializable
{

    private $path;
    private $name = null;
    
    public function setPath($path): self
    {
        $this->path = $path;
        return $this;
    }


    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }


    public function getDocumentName()
    {
        if ($this->name) {
            return $this->name;
        }
        $exploded = explode("/", $this->path);
        return array_pop($exploded);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            "name" => $this->name,
            "path" => $this->path,
        ];
    }

    public static function fromArray($array = [])
    {
        return (new self())
            ->setPath($array["path"] ?? "")
            ->setName($array["name"] ?? null);
    }
}