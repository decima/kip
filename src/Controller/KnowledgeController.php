<?php

namespace App\Controller;

use App\Entity\Page;
use App\Services\ParseDownImproved;
use App\Services\StorageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", name="knowledge")
 */
class KnowledgeController extends AbstractController
{

    /**
     * @Route("/{path}",requirements={"path"="[^_].*"}, methods={"GET"},name="_read")
     * @Route("", methods={"GET"},name="_read_home")
     */
    public function index($path = "readme", StorageManager $manager, Request $request)
    {

        $path          = trim($path, "/");
        $rawPath       = $path;
        $splittedNames = explode("/", $rawPath);
        $filename      = $splittedNames[count($splittedNames) - 1];
        if ($manager->isFile($path) && !$manager->pathIsMd($path)) {
            return new BinaryFileResponse($manager->getFilePath($path), 200, ["Content-Type" => mime_content_type($manager->getFilePath($path))]);
        } elseif ($manager->isFolder($path . "/")) {
            $path .= "/" . StorageManager::INDEX_FILE_NAME;
        } elseif (!$manager->pathIsMd($path)) {
            $path = $path . ".md";
        }
        $dirName = $manager->getParentDir($path);


        $file           = $manager->getFileContent($path);
        $parseDown      = new ParseDownImproved();
        $body           = $parseDown->text($file);
        $tableOfContent = $parseDown->contentsList();
        $tableOfContent = $this->buildTree($tableOfContent);

        return $this->render('knowledge/index.html.twig', [

            'content'        => $body,
            'tableOfContent' => $tableOfContent,
            "directory"      => $dirName,
            'raw'            => $file,
            'filename'       => $path,
            'rawPath'        => $rawPath,
            'edition'        => $request->query->has("_edit"),
            'title'          => $filename,
        ]);
    }

    private function buildTree($cs)
    {
        $tree        = [];
        $lastOfLevel = [];
        foreach ($cs as &$c) {
            if (!isset($lastOfLevel[$c["level"]])) {
                if (count($lastOfLevel) > 0) {
                    if (min(array_keys($lastOfLevel)) > $c["level"]) {
                        $lastOfLevel = [];
                    }
                }

                if (count($lastOfLevel) < 1) {
                    $tree[] = &$c;
                }
                $lastOfLevel[$c["level"]] = &$c;
                for ($i = $c["level"] - 1; $i > 0; $i--) {
                    if (isset($lastOfLevel[$i])) {
                        if (!isset($lastOfLevel[$i]["children"])) {
                            $lastOfLevel[$i]["children"] = [];
                        }
                        $lastOfLevel[$i]["children"][] = &$c;
                        $lastOfLevel[$c["level"]]      = &$c;
                        break;
                    }
                }

            } else {

                for ($i = $c["level"] - 1; $i > 0; $i--) {
                    if (isset($lastOfLevel[$i])) {
                        $lastOfLevel[$i]["children"][] = &$c;
                        break;
                    }
                }
                for ($i = $c["level"]; $i < 6; $i++) {
                    unset($lastOfLevel[$i]);
                }
                if (count($lastOfLevel) < 1) {
                    $tree[] = &$c;
                }
                $lastOfLevel[$c["level"]] = &$c;

            }
        }
        return $tree;

    }
}
