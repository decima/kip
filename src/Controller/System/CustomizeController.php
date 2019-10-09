<?php


namespace App\Controller\System;


use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomizeController
 * @package App\Controller\System
 * @Route("/public/custom",name="custom")
 */
class CustomizeController extends AbstractController
{

    /**
     * @Route("/style.css", name="_style")
     */
    public function style(StorageManager $storageManager)
    {
        $content = $storageManager->getFileContent(".custom/style.css");
        return new Response($content, 200, ["Content-Type"=>"text/css"]);
    }

}