<?php


namespace App\Services;


use App\Entity\Page;

class Elasticsearch
{

    /**
     * @var string $elasticsearch url with credentials for connection
     */
    private $url;

    /**
     * @var string Index for elasticsearch
     */
    private $index;

    /**
     * @var bool set false to disable elasticsearch indexation
     */
    private $enabled = true;

    /**
     * Elasticsearch constructor.
     * @param string $url
     * @param string $index
     * @param bool $enabled
     */
    public function __construct($elasticConfig)
    {
        $this->url     = $elasticConfig["url"];
        $this->index   = $elasticConfig["index"];
        $this->enabled = $elasticConfig["enabled"];
    }


    public function index($document)
    {

    }

    public function drop($documentName)
    {

    }

    public function initialIndex($documents)
    {

    }

    /**
     * @param $keyword
     * @return Page[]
     */
    public function search($keyword): array
    {
//todo implement search
    }

}