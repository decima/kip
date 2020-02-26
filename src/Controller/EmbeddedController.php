<?php


namespace App\Controller;


use App\Services\FileManipulation\FileResolver;
use App\Services\FileManipulation\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EmbeddedController
 * @package App\Controller
 */
class EmbeddedController extends AbstractController
{
    /**
     * @Route("/_/left")
     */
    public function leftDefaultMenu(FileResolver $fileResolver, RequestStack $requestStack)
    {
        $webpath = $requestStack->getMasterRequest()->attributes->get("webpath", "/");
        $page = new Page();
        return $this->render("embedded/left-default-menu.html.twig", ["path" => $webpath, "nav" => $fileResolver->listAllFiles("/", $page)]);
    }
}