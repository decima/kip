<?php

namespace App\Controller;

use App\Annotations\RouteExposed;
use App\Services\FileLoader\MarkdownFile;
use App\Services\FileManipulation\File;
use App\Services\FileManipulation\FileNotExistsException;
use App\Services\FileManipulation\FileResolver;
use App\Services\FileManipulation\FileWriter;
use App\Services\FileManipulation\Metadata;
use App\Services\FileManipulation\MetadataManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("", name="knowledge")
 */
class KnowledgeController extends AbstractController
{
    /**
     * @Route("/",defaults={"webpath"="/"}, methods={"GET"},name="_read_homepage")
     * @Route("/{webpath}",requirements={"webpath"="[^_](?:(?!(?|_slides|_delete|/_edit|_routing_js)).)*"}, methods={"GET"},name="_read")
     * @RouteExposed()
     */
    public function index(\App\Services\FileLoader\File $file, Request $request)
    {
        if ($file->fileInfo->isDir()) {
            return $this->redirectToRoute("knowledge_read_homepage", ["webpath" => $file->fileInfo->getRelativePathname() . "/readme.md"]);
        }
        if (!($file instanceof MarkdownFile)) {
            try {

                return new BinaryFileResponse($file->fileInfo->getPathname());
            } catch (FileNotExistsException $fileNotExistsException) {
                return new Response(null, Response::HTTP_NOT_FOUND);
            }
        }
        if ($request->getPreferredFormat() === "html") {
            // this behavior is for vue
            return $this->redirectToRoute("home", ["_fragment" => $file->webpath]);
        }

        return $this->json([
            'file'   => $file,
            'exists' => $file->fileInfo->isFile(),
            'isMD'   => true,
        ], 200, [], [AbstractNormalizer::IGNORED_ATTRIBUTES => ['strippedContent', 'fileInfo']]);
    }

    /**
     * @Route("/{webpath}/_edit",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_edit")
     * @Route("/_edit", methods={"GET"},name="_edit_home")
     * @throws FileNotExistsException
     * @RouteExposed()
     */
    public function edit(\App\Services\FileLoader\File $file)
    {
        if (!($file instanceof MarkdownFile)) {
            return $this->json($file, 200, [], [AbstractNormalizer::IGNORED_ATTRIBUTES => ['strippedContent', 'fileInfo']]);
        }
        return $this->json(['file' => $file], 200, [], [AbstractNormalizer::IGNORED_ATTRIBUTES => ['strippedContent', 'fileInfo']]);
    }


    /**
     * @Route("/{webpath}/_slides",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_slides")
     * @Route("/_slides", methods={"GET"},name="_slides_home")
     * @RouteExposed()
     */
    public function slides(\App\Services\FileLoader\File $file)
    {
        if ($file instanceof MarkdownFile) {
            return $this->render("knowledge/slides.html.twig", ["file" => $file]);
        }
        return $this->redirectToRoute("knowledge_read_homepage");
    }

    /**
     * @Route("/{webpath}/_edit",requirements={"webpath"="[^_].*"}, methods={"PUT"},name="_update")
     * @Route("/_edit", methods={"PUT"},name="_update_home")
     * @RouteExposed()
     */
    public function update(\App\Services\FileLoader\File $file, Request $request)
    {
        dd($file);
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

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{webpath}/_delete",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_delete")
     * @Route("/_delete", methods={"GET"},name="_delete_home")
     * @RouteExposed()
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
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
