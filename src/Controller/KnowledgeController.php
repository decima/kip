<?php

namespace App\Controller;

use App\Annotations\RouteExposed;
use App\Services\FileLoader\EmptyFile;
use App\Services\FileLoader\File;
use App\Services\FileLoader\FileLoader;
use App\Services\FileLoader\MarkdownFile;
use App\Services\FileLoader\MetadataManager;
use App\Services\InternalSettings;
use App\Services\Permissions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
            return $this->redirectToRoute("knowledge_read_homepage", ["webpath" => $file->fileInfo->getRelativePathname() . "/" . InternalSettings::DEFAULT_INDEX_FILE . ".md"]);
        }
        if (!($file instanceof MarkdownFile) && !($file instanceof EmptyFile)) {

            return new BinaryFileResponse($file->fileInfo->getPathname());
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
     * @RouteExposed()
     */
    public function edit(File $file)
    {
        if (!($file instanceof MarkdownFile)) {
            return $this->json(["file" => $file], 200, [], [AbstractNormalizer::IGNORED_ATTRIBUTES => ['strippedContent', 'fileInfo']]);
        }
        return $this->json(["file" => $file], 200, [], [AbstractNormalizer::IGNORED_ATTRIBUTES => ['strippedContent', 'fileInfo']]);
    }


    /**
     * @Route("/{webpath}/_slides",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_slides")
     * @Route("/_slides", methods={"GET"},name="_slides_home")
     * @RouteExposed()
     */
    public function slides(File $file)
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
    public function update(File $file,
                           Request $request,
                           FileLoader $fileLoader,
                           MetadataManager $metadataManager,
                           Permissions $permissions)
    {
        if (!$permissions->isGranted(Permissions::TYPE_EDIT)) {
            return $this->json("cannot edit", 403);
        }
        if (!$file instanceof MarkdownFile && !$file instanceof EmptyFile) {
            return $this->json("unprocessable", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $directory = dirname($file->fileInfo->getPath());
        @mkdir($directory, 0777, true);
        $content = $request->getContent();
        file_put_contents($file->fileInfo->getPathname(), $content);

        $savedFile = $fileLoader->loadFileByPath($file->fileInfo->getRelativePathname());
        if ($savedFile instanceof MarkdownFile) {

            $metadataManager->setMetadataForDocument($file->fileInfo->getRelativePathname(), $savedFile->metadata);
        }
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{webpath}/_delete",requirements={"webpath"="[^_].*"}, methods={"GET"},name="_delete")
     * @Route("/_delete", methods={"GET"},name="_delete_home")
     * @RouteExposed()
     */
    public function delete(Permissions $permissions, MetadataManager $metadataManager, Request $request, File $file)
    {

        if (!$permissions->isGranted(Permissions::TYPE_DELETE)) {
            return $this->json("cannot delete", 403);
        }
        if (!$file instanceof EmptyFile) {
            @unlink($file->fileInfo->getPathname());
        }
        return new Response(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * @Route("/{webpath}/_upload",requirements={"webpath"="[^_].*"}, methods={"POST"},name="_upload")
     * @Route("/_upload", methods={"POST"},name="_upload_home")
     * @RouteExposed()
     */
    public
    function upload(MetadataManager $metadataManager, Request $request, File $file, ParameterBagInterface $parameterBag)
    {
        $relativePathName = "/" . $file->fileInfo->getRelativePath() . "/";
        $parentFullPath = $file->fileInfo->getPath() . "/";
        $name = time() . "-" . $request->query->get("name");
        $name = preg_replace("/[^a-zA-Z0-9-_.]/", "", $name);
        $fileFullPath = $parentFullPath . $name;

        file_put_contents($fileFullPath, $request->getContent());
        return $this->json([
            "name" => $request->query->get("name"),
            "path" => $relativePathName . $name,
        ]);
    }
}
