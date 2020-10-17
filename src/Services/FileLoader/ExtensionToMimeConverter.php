<?php


namespace App\Services\FileLoader;


class ExtensionToMimeConverter
{
    private $availableExtensions = [
        "css"      => "text/css",
        "_default" => "application/octet-stream",
    ];

    public function __invoke($extension = "txt")
    {
        return $this->availableExtensions[$extension] ?? $this->availableExtensions["_default"];
    }
}