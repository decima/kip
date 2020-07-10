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
use League\CommonMark\Extension\ExternalLink\ExternalLinkProcessor;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Element\Link;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

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

        $fileContent = YamlFrontMatter::parse($file->markdownContent);

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
        $converter = new CommonMarkConverter([], $environment);


        $file->content = $converter->convertToHtml($fileContent->body());
        $file->tree = $headingWrapper->extractTOC();
        $file->metadata = $this->serializer->deserialize(json_encode($fileContent->matter()), Metadata::class, "json");
        return $file;
    }
}
