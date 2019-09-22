<?php


namespace App\Controller\System;


use App\Entity\PageLink;
use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EmbeddedController extends AbstractController
{

    /**
     * @param string $path
     * @param StorageManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/left")
     */
    public function leftMenu($path = "", StorageManager $manager)
    {
        $homePage = new PageLink();
        $manager->listAllFiles("", $homePage);
        $homePage->name = "Home";
        $homePage->path = "readme";

        return $this->render("embedded/left.html.twig", ["nav" => $homePage]);
    }

}