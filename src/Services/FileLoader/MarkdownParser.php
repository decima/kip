<?php


namespace App\Services\FileLoader;


use App\Services\FileLoader\CommonMarkExtensions\CustomExternalLinkProcessor;
use App\Services\FileLoader\CommonMarkExtensions\HeadingWrapper;
use App\Services\FileLoader\CommonMarkExtensions\ImageLinkWrapper;
use App\Services\FileLoader\CommonMarkExtensions\ImageSizeParser;
use App\Services\FileLoader\CommonMarkExtensions\LinkWrapper;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Element\Link;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Yaml\Exception\ParseException;

class MarkdownParser
{
    /**
     * @required
     */
    public RequestStack $requestStack;
    /**
     * @required
     */
    public SerializerInterface $serializer;


    public function parse(MarkdownFile &$file)
    {
        $body = $file->markdownContent;
        $metadata = [];
        try {
            $fileContent = YamlFrontMatter::parse($file->markdownContent);
            $body = $fileContent->body();
            $metadata = $fileContent->matter();
        } catch (ParseException $exception) {
            //do nothing
        }

        $environment = Environment::createCommonMarkEnvironment()
            ->addExtension(new GithubFlavoredMarkdownExtension())
            ->addExtension(new AutolinkExtension())
            ->addExtension(new ExternalLinkExtension())
            ->addExtension(new TaskListExtension())
            ->addExtension(new TableExtension())
            ->addInlineRenderer(Image::class, new ImageLinkWrapper(
                $this->requestStack->getCurrentRequest()->get("webpath"),
                $file->fileInfo->getRelativePath()
            ))
            ->addInlineRenderer(Link::class, new LinkWrapper(), -100)
            ->addInlineParser(new ImageSizeParser(), 100)
            ->addBlockRenderer(Heading::class, $headingWrapper = new HeadingWrapper())
            ->addEventListener(DocumentParsedEvent::class, new CustomExternalLinkProcessor(
                $this->requestStack->getCurrentRequest()->getBaseUrl()
            ));
        $environment->addExtension(new \Zoon\CommonMark\Ext\YouTubeIframe\YouTubeIframeExtension());
        $converter = new CommonMarkConverter([
            'youtube_iframe_width'           => 600,
            'youtube_iframe_height'          => 300,
            'youtube_iframe_allowfullscreen' => true,
        ], $environment);


        $file->content = $converter->convertToHtml($body);
        $file->tree = $headingWrapper->extractTOC();
        $file->metadata = $this->serializer->deserialize(json_encode($metadata), Metadata::class, "json");
        return $file;
    }
}
