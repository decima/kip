<?php


namespace App\Services\FileLoader;



class ExtensionConverter
{
    private $availableExtensions = [
        "css"      => "text/css",
        "txt"      => "text/plain",
        "_default" => "application/octet-stream",
    ];

    function Apply($extension = "txt")
    {
        return $this->availableExtensions[$extension] ?? $this->availableExtensions["_default"];
    }
}