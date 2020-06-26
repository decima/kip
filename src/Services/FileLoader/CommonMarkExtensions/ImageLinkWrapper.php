<?php


namespace App\Services\FileLoader\CommonMarkExtensions;


use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class ImageLinkWrapper implements InlineRendererInterface
{

    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof Image)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
        }
        $style = implode("", array_map(fn($k, $v) => "$k: {$v}px;", array_keys($inline->getData("size", [])), array_values($inline->getData("size", []))));
        $attrs = [
            "class"  => "image-container",
            "href"   => $inline->getUrl(),
            "target" => "_blank",
            "style"  => $style,
        ];
        return new HtmlElement("div", ["class" => "image-row"], new HtmlElement('a', $attrs, new HtmlElement("img", ["src" => $inline->getUrl(), "style" => $style])));
    }
}