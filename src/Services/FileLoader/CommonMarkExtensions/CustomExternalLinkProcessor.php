<?php


namespace App\Services\FileLoader\CommonMarkExtensions;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Inline\Element\Link;

class CustomExternalLinkProcessor
{
    private $internalHost;

    public function __construct($internalHost)
    {
        $this->internalHost = $internalHost;
    }

    /**
     * @param DocumentParsedEvent $e
     *
     * @return void
     */
    public function __invoke(DocumentParsedEvent $e)
    {
        $walker = $e->getDocument()->walker();
        while ($event = $walker->next()) {
            if ($event->isEntering() && $event->getNode() instanceof Link) {
                /** @var Link $link */
                $link = $event->getNode();

                $host = parse_url($link->getUrl(), PHP_URL_HOST);
                if (empty($host)) {
                    $link->data['external'] = false;
                    $link->setUrl("#" . $link->getUrl());
                    continue;
                }
                if (self::hostMatches($host, [$this->internalHost])) {
                    $link->data['external'] = false;
                    continue;
                }

                // Host does not match our list
                $this->markLinkAsExternal($link, true, "external-link");
            }
        }
    }

    private function markLinkAsExternal(Link $link, bool $openInNewWindow, string $classes): void
    {
        $link->data['external'] = true;
        $link->data['attributes'] = $link->getData('attributes', []);

        if ($openInNewWindow) {
            $link->data['attributes']['target'] = '_blank';
        }

        if (!empty($classes)) {
            $link->data['attributes']['class'] = trim(($link->data['attributes']['class'] ?? '') . ' ' . $classes);
        }
    }


    /**
     * @param string $host
     * @param mixed $compareTo
     *
     * @return bool
     *
     * @internal This method is only public so we can easily test it. DO NOT USE THIS OUTSIDE OF THIS EXTENSION!
     */
    public static function hostMatches(string $host, $compareTo)
    {
        foreach ((array)$compareTo as $c) {
            if (strpos($c, '/') === 0) {
                if (preg_match($c, $host)) {
                    return true;
                }
            } elseif ($c === $host) {
                return true;
            }
        }

        return false;
    }
}
