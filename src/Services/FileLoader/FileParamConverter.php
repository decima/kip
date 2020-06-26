<?php


namespace App\Services\FileLoader;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class FileParamConverter implements ParamConverterInterface
{
    /**
     * @required
     */
    public FileLoader $fileLoader;

    public function apply(Request $request, ParamConverter $configuration)
    {
        $path = $request->attributes->get("webpath", $request->attributes->get("path", ""));
        $file = $this->fileLoader->loadFileByPath($path);
        $request->attributes->set($configuration->getName(), $file);

    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === File::class;
    }
}