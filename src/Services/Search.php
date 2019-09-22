<?php


namespace App\Services;


use App\Entity\Page;

class Search
{
    private $enableElasticsearch = false;

    /**
     * @var Elasticsearch
     */
    private $elasticsearchClient;
    /**
     * @var StorageManager
     */
    private $storageManager;

    /**
     * Search constructor.
     * @param bool $enableElasticsearch
     * @param Elasticsearch $elasticsearchClient
     * @param StorageManager $storageManager
     */
    public function __construct(bool $enableElasticsearch, Elasticsearch $elasticsearchClient, StorageManager $storageManager)
    {
        $this->enableElasticsearch = $enableElasticsearch;
        $this->elasticsearchClient = $elasticsearchClient;
        $this->storageManager      = $storageManager;
    }


    public function searchByKeyword($keyword)
    {
        if ($this->enableElasticsearch) {
            return $this->elasticsearchClient->search($keyword);
        } else {
            $homePage = new Page();
            $this->storageManager->listAllFiles("", $homePage);
            $result = [];
            return $this->searchFromFilename($keyword, $homePage, $result);
        }
    }

    private function searchFromFilename($keyword, Page $page, &$result = [])
    {
        if (stripos($page->path, $keyword) !== false) {
            $result[] = $page;
        }
        foreach ($page->subLinks as $subpage) {
            $this->searchFromFilename($keyword, $subpage, $result);
        }
        return $result;

    }
}