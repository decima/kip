<?php

namespace App\Controller;

use App\Services\ParseDownImproved;
use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", name="knowledge")
 */
class KnowledgeController extends AbstractController
{

    /**
     * @Route("/{path}",requirements={"path"="[^_].+"}, methods={"GET"},name="_read")
     * @Route("", methods={"GET"},name="_homepage")
     */
    public function index($path = "readme", StorageManager $manager)
    {

        if ($manager->isFile($path) && !$manager->pathIsMd($path)) {
            return new BinaryFileResponse($manager->getFilePath($path));
        } elseif ($manager->isFolder($path . "/")) {
            $path .= "readme.md";
        } elseif (!$manager->pathIsMd($path)) {
            $path = $path . ".md";
        }



        $file           = $manager->getFileContent($path);
        $parseDown      = new ParseDownImproved();
        $body           = $parseDown->text($file);
        $tableOfContent = $parseDown->contentsList("array");

        return $this->render('knowledge/index.html.twig', [

            'content' => $body,
            'table'   => $tableOfContent,
        ]);
    }
}
