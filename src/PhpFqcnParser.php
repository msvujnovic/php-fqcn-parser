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
    public function execute(array $filePaths, Options $options)
    {
        $this->validateOptions($options);

        $files = $this->getFiles($filePaths, $options);
        $onlyExistingFiles = $files->filter(function (File $file) {
            return $file->doesExist();
        });

        $parser = new Parser();
        $fqcns = $onlyExistingFiles->map(function (File $file) use ($parser) {
            return $parser->getFqCn($file->getContents());
        });

        return $fqcns->toArray();
    }

    /**
     * @param Options $options
     *
     * @throws InvalidOptionException
     */
    private function validateOptions(Options $options)
    {
        if ($options->getPathType() === Options::PATH_TYPE_RELATIVE && empty($options->getBasePath())) {
            throw new InvalidOptionException("Empty base path passed for path type relative");
        }
    }

    /**
     * @param string[] $filePaths
     * @param Options  $options
     *
     * @return Collection|File[]
     */
    private function getFiles(array $filePaths, Options $options)
    {
        $factory = new FileFactory();

        if ($options->getPathType() === Options::PATH_TYPE_RELATIVE) {
            return $factory->createFromRelativeAndBasePath($filePaths, $options->getBasePath());
        }

        return $factory->createFromAbsolutePaths($filePaths);
    }
}
