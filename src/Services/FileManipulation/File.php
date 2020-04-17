<?php


namespace App\Services\FileManipulation;


class File
{
    public string $webpath = "/";
    public string $name;
    public string $path;
    public string $markdownContent = "";

    public array $metadata = [];
    public string $content = "";
    public array $tree = [];

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function getTitle()
    {
        if (isset($this->metadata["title"])) {
            return $this->metadata["title"];
        }

        if (strtolower($this->name) === "readme.md") {
            return $this->unslugify(basename(dirname($this->path)));
        }
        return $this->unslugify($this->name);


    }

    public static function unslugify($str)
    {
        $pattern = '/([a-z0-9])([A-Z])|([A-Z])([A-Z][a-z])/';
        $str = str_replace("_", " ", $str);

        $str = preg_replace_callback(
            $pattern,
            function ($matches) {
                $matches = array_values(array_filter($matches));
                return $matches[1] . " " . $matches[2];
            },
            $str
        );

        $str = basename($str, ".md");

        return $str;
    }

    public function getPlainText(){
        return preg_replace("/<[^>]+>/", " ", $this->content);
    }
}