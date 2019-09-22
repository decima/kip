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
     * Elasticsearch constructor.
     * @param $elasticConfig
     */
    public function __construct($elasticConfig)
    {
        $this->url     = $elasticConfig["url"];
        $this->index   = $elasticConfig["index"];
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