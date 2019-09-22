<?php


namespace App\Entity;


class Page
{
    public $isFolder = false;
    public $hasReadme = false;
    public $name;
    public $path;
    public $subLinks = [];
    public $content;
}