<?php


namespace App\Services\FileLoader\CommonMarkExtensions;


use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;

class HeadingWrapper implements BlockRendererInterface
{
    private $headers = [];

    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        $render = $htmlRenderer->renderInlines($block->children());
        $pureTitle = strip_tags($render);

        if ($block instanceof Heading) {
            $this->headers[] = ["text" => $pureTitle, "level" => $level = $block->getLevel(), "id" => $id = "h$level-" . $this->slugify($pureTitle) . "-" . substr(md5($render), 0, 10)];
        }
        return new HtmlElement('h' . $level, ["id" => $id], [$render]);
    }


    public function extractTOC()
    {

        $tree = [];
        $lastOfLevel = [];
        foreach ($this->headers as &$c) {
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

    private function slugify($string, $delimiter = '-')
    {

        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//IGNORE', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }
}
