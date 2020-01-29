<?php


namespace App\Services\FileManipulation;


use App\Services\ParseDownImproved;
use Symfony\Component\Yaml\Yaml;

class FileReader
{

    public function readFile($webpath, $path): File
    {
        $fileExtracted = new File();
        $fileExtracted->webpath = $webpath;
        $fileExtracted->markdownContent = @file_get_contents($path);
        $fileExtracted->name = basename($path);
        $fileExtracted->path = $path;
        return $fileExtracted;
    }

    public function extractMetaData(File &$file)
    {
        $file->markdownContent = preg_replace_callback(
            "/^\n*(?:\-\-\-)(.*?)(?:\-\-\-|\.\.\.)/s",
            function ($matches) use ($file) {
                $file->metadata = Yaml::parse($matches[1]);
                return "";
            },
            $file->markdownContent,
            1
        );
    }

    private function buildTree($cs)
    {
        $tree = [];
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
                        $lastOfLevel[$c["level"]] = &$c;
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

    public function parseMarkdown(File $file)
    {
        $parseDown = new ParseDownImproved();
        $file->content = $parseDown->text($file->markdownContent);
        $tableOfContent = $parseDown->contentsList();
        $file->tree = $this->buildTree($tableOfContent);
    }
}