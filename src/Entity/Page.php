<?php


namespace App\Entity;


class Page
{
    public $isFolder = false;
    public $hasReadme = false;
    public $mime = "";
    public $name;
    public $path;
    public $subLinks = [];
    public $content;

    public function getType(): string
    {
        return explode("/", $this->mime)[0];
    }
}