<?php


namespace App\Services\FileManipulation;


class FileNotExistsException extends \Exception
{
    public string $filename;

    /**
     * FileNotExistsException constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        parent::__construct("cannot find $filename");
    }

}