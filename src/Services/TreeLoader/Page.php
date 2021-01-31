<?php


namespace App\Services\TreeLoader;


class Page
{
    public $isFolder = false;
    public $hasReadme = false;
    public $mime = "";
    public $name;
    public $path;
    /**
     * @var Metadata
     */
    public $metadata;
    /**
     * @var Page[]
     */
    public $subLinks = [];
    public $content;

    public function getType(): string
    {
        return explode("/", $this->mime)[0];
    }

    public function getScopedSlots(){
        return ["title" => "title", "icon" => "icon"];
    }
}
