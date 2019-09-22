<?php


namespace App\Entity;


class PageLink
{
    public $isFolder = false;
    public $hasReadme = false;
    public $name;
    public $path;
    public $subLinks = [];
}