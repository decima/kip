<?php


namespace App\Controller\System;

use App\Services\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller\System
 * @Route("/search", name="search")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("",name="_action")
     */
    public function index(Request $request, Search $search)
    {
        $keyword = $request->get("q");

        $result = $search->searchByKeyword($keyword);
        return $this->render("search/result.html.twig", ["results"=>$result]);
    }

}