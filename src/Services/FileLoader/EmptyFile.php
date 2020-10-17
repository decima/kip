<?php


namespace App\Services\FileLoader;


class EmptyFile extends File
{
    public string $markdownContent = "";
    public $metadata=[];
    public string $content = "";
    public array $tree = [];
}