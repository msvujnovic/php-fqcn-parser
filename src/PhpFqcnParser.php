<?php

namespace PhpFqcnParser;

use Illuminate\Support\Collection;
use PhpFqcnParser\File\File;
use PhpFqcnParser\File\FileFactory;
use PhpFqcnParser\Parser\Parser;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  17.05.2018
 */
class PhpFqcnParser
{
    /**
     * @param string[] $fileNames
     * @param Options  $options
     *
     * @return string[]
     */
    public function getFqcnsFromFiles(array $fileNames, Options $options)
    {
        $files = $this->getFiles($fileNames, $options);
        $onlyExistingFiles = $files->filter(function (File $file) {
            return $file->doesExist();
        });

        $parser = new Parser();
        $fqcns = $onlyExistingFiles->map(function (File $file) use ($parser) {
            return $parser->getFqcns($file->getContents());
        })->flatten();

        return $fqcns->toArray();
    }

    /**
     * @param string[] $filePaths
     * @param Options  $options
     *
     * @return Collection|File[]
     */
    protected function getFiles(array $filePaths, Options $options)
    {
        $factory = new FileFactory();

        if ($options->isPathTypeRelative()) {
            return $factory->createFromRelativeAndBasePath($filePaths, $options->getBasePath());
        }

        return $factory->createFromAbsolutePaths($filePaths);
    }
}
