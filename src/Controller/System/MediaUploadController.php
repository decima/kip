<?php


namespace App\Controller\System;


use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaUploadController
 * @package App\Controller\System
 * @Route("/media", name="media")
 */
class MediaUploadController extends AbstractController
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
        return $this->json(["filename" => $filename]);
    }

}