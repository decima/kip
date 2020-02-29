<?php

namespace App\Controller;

use App\Services\FileManipulation\File;
use App\Services\FileManipulation\FileNotExistsException;
use App\Services\FileManipulation\FileReader;
use App\Services\FileManipulation\FileResolver;
use App\Services\FileManipulation\FileWriter;
use App\Services\FileManipulation\Metadata;
use App\Services\FileManipulation\MetadataManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", name="knowledge")
 */
class KnowledgeController extends AbstractController
{
    /**
     * @Route("/{webpath}",requirements={"webpath"="[^_](?:(?!(?|_slides|_delete|/_edit)).)*"}, methods={"GET"},name="_read")
     * @Route("", methods={"GET"},name="_read_home")
     */
    public function index(FileResolver $fileResolver, FileReader $fileReader, $webpath = "/")
    {
        $fileExists = false;
        try {
            $path = $fileResolver->getFile($webpath);
            $fileExists = true;
            if (!$fileResolver->isMarkdownFile($path)) {
                return new BinaryFileResponse($path);
            }
        } catch (FileNotExistsException $fileNotExistsException) {
            $path = $fileNotExistsException->filename;
            $notfoundResponse = new Response("", Response::HTTP_NOT_FOUND);
            $file = $fileReader->readFile($webpath, $path);
            return $this->render("knowledge/notfound.html.twig", ["file" => $file], $notfoundResponse);
        }

        $file = $fileReader->readFile($webpath, $path);
        $fileReader->extractMetaData($file);
        $fileReader->parseMarkdown($file);
        return $this->render("knowledge/index.html.twig", [
            'file' => $file,
            'exists' => $fileExists,
            'isMD' => $fileResolver->isMarkdownFile($path),
        ]);
    }

    /**
     * @Route("/{webpath}/_edit",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_edit")
     * @Route("/_edit", methods={"GET"},name="_edit_home")
     * @throws FileNotExistsException
     */
    public function edit(FileResolver $fileResolver, FileReader $fileReader, $webpath = "/")
    {
        $path = $fileResolver->getFile($webpath, false);
        if (!$fileResolver->isMarkdownFile($path)) {
            return $this->redirectToRoute("knowledge_read", ["path" => $path]);
        }
        $file = $fileReader->readFile($webpath, $path);

        return $this->render("knowledge/edit.html.twig", [
            'file' => $file,
        ]);
    }


    /**
     * @Route("/{webpath}/_slides",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_slides")
     * @Route("/_slides", methods={"GET"},name="_slides_home")
     */
    public function slides(FileResolver $fileResolver, FileReader $fileReader, $webpath = "/")
    {

        try {
            $path = $fileResolver->getFile($webpath);
            if (!$fileResolver->isMarkdownFile($path)) {
                return $this->redirectToRoute("knowledge_read", ["webpath" => $webpath]);
            }
        } catch (FileNotExistsException $fileNotExistsException) {
            return $this->redirectToRoute("knowledge_read", ["webpath" => $webpath]);
        }
        $file = $fileReader->readFile($webpath, $path);
        return $this->render("knowledge/slides.html.twig", ["file" => $file]);
    }

    /**
     * @Route("/{webpath}/_edit",requirements={"webpath"="[^_].*"}, methods={"PUT"},name="_update")
     * @Route("/_edit", methods={"PUT"},name="_update_home")
     */
    public function update(FileResolver $fileResolver, FileWriter $fileWriter, FileReader $fileReader, MetadataManager $metadataManager, Request $request, $webpath = "/")
    {

        try {
            $path = $fileResolver->getFile($webpath);
            if (!$fileResolver->isMarkdownFile($path)) {
                return $this->json("unprocessable", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (FileNotExistsException $fileNotExistsException) {
            $path = $fileNotExistsException->filename;
            $fileWriter->createParentFolder($path);
        }
        $content = $request->getContent();
        $fileWriter->writeFile($path, $content);
        $file = new File();
        $file->webpath = $webpath;
        $file->markdownContent = $content;
        $fileReader->extractMetaData($file);
        $fileDeclaredMetadata = $file->getMetadata();
        $metadata = new Metadata();
        if (isset($fileDeclaredMetadata["title"])) {
            $metadata->setName($fileDeclaredMetadata["title"]);
        }
        $metadataManager->setMetadataForDocument($webpath, $metadata);

        return $this->json("saved");

    }

    /**
     * @Route("/{webpath}/_delete",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_delete")
     * @Route("/_delete", methods={"GET"},name="_delete_home")
     */
    public function delete(FileResolver $fileResolver, FileWriter $fileWriter, MetadataManager $metadataManager, Request $request, $webpath = "/")
    {

        try {
            $path = $fileResolver->getFile($webpath);
            if ($fileResolver->isMarkdownFile($path)) {
                $metadataManager->unsetMetadata($webpath);
                unlink($path);
            }
        } catch (FileNotExistsException $fileNotExistsException) {
        }
        return $this->redirectToRoute("knowledge_read", ["webpath" => $webpath]);

    }
}
