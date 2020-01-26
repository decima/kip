<?php

namespace App\Controller;

use App\Services\FileManipulation\FileNotExistsException;
use App\Services\FileManipulation\FileReader;
use App\Services\FileManipulation\FileResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", name="knowledge")
 */
class KnowledgeController extends AbstractController
{
    /**
     * @Route("/{path}",requirements={"path"="[^_](?:(?!(?|_media|/_edit)).)*"}, methods={"GET"},name="_read")
     * @Route("", methods={"GET"},name="_read_home")
     */
    public function index(FileResolver $fileResolver, FileReader $fileReader, $path = "/")
    {
        $fileExists = false;
        try {
            $path = $fileResolver->getFile($path);
            $fileExists = true;
            if (!$fileResolver->isMarkdownFile($path)) {
                return new BinaryFileResponse($path);
            }
        } catch (FileNotExistsException $fileNotExistsException) {
            $path = $fileNotExistsException->filename;
        }

        $file = $fileReader->readFile($path);
        $fileReader->extractMetaData($file);
        $fileReader->parseMarkdown($file);
        return $this->render("knowledge/index.html.twig", [
            'file' => $file,
            'exists' => $fileExists,
            'isMD' => $fileResolver->isMarkdownFile($path),
        ]);
    }

    /**
     * @Route("/{path}/_edit",requirements={"path"="[^_].*"}, methods={"GET"},name="_edit")
     * @Route("/_edit", methods={"GET"},name="_edit_home")
     */
    public function edit(FileResolver $fileResolver, $path = "/")
    {

    }
}
