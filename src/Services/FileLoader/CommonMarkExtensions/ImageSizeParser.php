<?php


namespace App\Services\FileLoader\CommonMarkExtensions;


use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;

class ImageSizeParser implements InlineParserInterface
{

    public function getCharacters(): array
    {
        return ["!"];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);
        if ($previousChar !== null && $previousChar !== ' ') {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }
        $previousState = $cursor->saveState();
        $cursor->advance();
        $handle = $cursor->match('/^\[(.*)\]\([^)]*\)/');
        if (empty($handle)) {
            $cursor->restoreState($previousState);
            return false;
        }
        $match = [];
        if (!preg_match("/^\[([^]]*)\]\(([^)]+)\)$/mi", $handle, $match)) {
            return false;
        }
        $parts = array_values(array_filter(explode(" ", $match[2])));
        $url = array_shift($parts);
        $title = null;
        $width = null;
        $height = null;
        foreach ($parts as $p) {
            $submatch = [];
            if (strpos($p, '"') === 0) {
                $title = str_replace('"', '', $p);
            } elseif (preg_match("/^(\d*)x(\d*)$/i", $p, $submatch)) {
                [$width, $height] = explode("x", strtolower($p));
            }
        }

        $image = new Image($url, $match[1], $title);
        $image->data["size"] = array_filter(["width" => $width, "height" => $height]);

        $inlineContext->getContainer()->appendChild($image);
        return true;
        // TODO: Implement parse() method.
    }
}