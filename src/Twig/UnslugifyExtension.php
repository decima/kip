<?php


namespace App\Twig;

use App\Services\FileLoader\File;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class UnslugifyExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('unslugify', [new File(), 'unslugify']),
        ];
    }

    public function getName()
    {
        return 'unslugify';
    }

}
