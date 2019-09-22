<?php

namespace App\Controller\System;

use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocumentController
 * @package App\Controller
 * @Route("documents",name="documents")
 */
class DocumentController extends AbstractController
{


    /**
     *
     * @Route("/{path}",requirements={"path"=".+"}, methods={"PUT"},name="_store")
     */
    public function store($path, Request $request, StorageManager $storage)
    {
        $storage->storeFileContent($path, $request->getContent());
        return $this->json("ok", 200);
    }

    /**
     *
     * @Route("/{path}",requirements={"path"=".+"}, methods={"DELETE"},name="_delete")
     */
    public function delete($path, Request $request, StorageManager $storage)
    {
        $storage->dropFile($path);
        return $this->json(null, 204);
    }
}
