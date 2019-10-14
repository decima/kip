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
        $pattern = '/(([A-Z]{1}))/';
        $str     = str_replace("_", " ", $str);
        $str     = preg_replace_callback(
            $pattern,
            function ($matches) {
                return " " . $matches[0];
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