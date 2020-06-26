<?php


namespace App\Controller;


use App\Annotations\RouteExposed;
use App\Services\FileManipulation\Page;
use App\Services\TreeLoader\TreeLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

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
    public function leftDefaultMenu(TreeLoader $treeLoader, RequestStack $requestStack)
    {
        $webpath = $requestStack->getMasterRequest()->attributes->get("webpath", "/");
        $articlesTree = $treeLoader->listAllFiles();
        $indexedArticles = $treeLoader->indexedFiles;
        return $this->json(["path" => $webpath, "nav" => $articlesTree, "indexedArticles" => $indexedArticles]);
    }
}