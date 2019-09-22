<?php


namespace App\Controller\System;


use App\Entity\PageLink;
use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmbeddedController extends AbstractController
{
    public function leftMenu($path = "", StorageManager $manager)
    {
        $homePage = new PageLink();
        $manager->listAllFiles("", $homePage);
        $homePage->name = "Home";
        $homePage->path = "readme";

        return $this->render("embedded/left.html.twig", ["nav" => $homePage]);
    }

}