<?php


namespace App\Twig;


class ToSentenceCaseExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('titling', [$this, 'convertSpacesFilter']),
        ];
    }

    public function convertSpacesFilter($str)
    {
        $pattern = '/([a-z0-9])([A-Z])|([A-Z])([A-Z][a-z])/';
        $str     = str_replace("_", " ", $str);
        $str     = preg_replace_callback(
            $pattern,
            function ($matches) {
                $matches = array_values(array_filter($matches));

                dump($matches);
                return $matches[1] . " " . $matches[2];
            },
            $str
        );

        $str = rtrim($str, ".md");

        return $str;
    }

    public function getName()
    {
        return 'titling';
    }

}