<?php


namespace App\Controller\System;


use App\Entity\Page;
use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaUploadController
 * @package App\Controller\System
 * @Route("/media", name="media")
 */
class MediaController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("/store", name="_store")
     */
    public function storeImage(Request $request, StorageManager $storageManager)
    {
        $path = $request->request->get("path");
        $file = $request->files->get("media");
        /**
         * @var $file UploadedFile
         */
        $filename = $storageManager->addUploadedFile($file, $path);
        return $this->json(["filename" => "/".$filename]);
    }

    /**
     * @Route("",name="_index")
     */
    public function index(Request $request, StorageManager $manager)
    {
        $path   = $request->query->get("path", "");
        $action = $request->query->get("action", "embedded");
        $path   = str_replace("..", "./", $path);
        return $this->render("media/index.html.twig", [
            "path"        => $path,
            "action"      => $action,
            "parent"      => $manager->getParentDir($path),
            "files"       => $manager->getFiles($path),
            "directories" => $manager->getDirectories($path),
        ]);
    }


}