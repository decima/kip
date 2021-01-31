<?php


namespace App\Services\FileLoader;


use Symfony\Component\Finder\SplFileInfo;

class File
{
    public string $webpath = "/";
    public string $name;
    public string $path;
    public SplFileInfo $fileInfo;
}