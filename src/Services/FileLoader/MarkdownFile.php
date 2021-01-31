<?php


namespace App\Services\FileLoader;


class MarkdownFile extends File
{

    public string $markdownContent = "";

    public ?Metadata $metadata;
    public string $content = "";
    public array $tree = [];

    public function getStrippedContent()
    {
        return strip_tags($this->content);
    }

}