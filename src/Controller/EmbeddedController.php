<?php


namespace App\Controller;


use App\Services\FileManipulation\FileLister;
use App\Services\FileManipulation\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use App\Annotations\RouteExposed;

/**
 * Class EmbeddedController
 * @package App\Controller
 */
class EmbeddedController extends AbstractController
{
    /**
     * @Route("/_/left", name="articles_tree")
     * @RouteExposed()
     */
    public function leftDefaultMenu(FileLister $fileLister, RequestStack $requestStack)
    {
        $webpath = $requestStack->getMasterRequest()->attributes->get("webpath", "/");
        $page = new Page();
        $articlesTree = $fileLister->listAllFiles("/", $page);
        $indexedArticles = $fileLister->indexedFiles;
        return $this->json(["path" => $webpath, "nav" => $articlesTree, "indexedArticles" => $indexedArticles]);
    }
}