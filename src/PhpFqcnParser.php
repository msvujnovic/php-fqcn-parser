<?php

namespace PhpFqcnParser;

use Illuminate\Support\Collection;
use PhpFqcnParser\File\File;
use PhpFqcnParser\File\FileFactory;

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
        $onlyExistingFiles = $this->filterOnlyExistingFiles($files);

        $fqcns = $onlyExistingFiles->map(function (File $file) {
            return $this->getFqCnFromFile($file);
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
            throw new InvalidOptionException("Empty base path passed for path type absolute");
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

    /**
     * @param Collection $files
     *
     * @return Collection
     */
    private function filterOnlyExistingFiles(Collection $files)
    {
        return $files->filter(function (File $file) {
            try {
                $file->getContents();
            } catch (\Exception $e) {
                return false;
            }

            return true;
        });
    }

    private function getFqCnFromFile(File $file)
    {
        $contents = $file->getContents();

        $namespace = $class = "";
        $readNamespace = $readClass = false;

        foreach (token_get_all($contents) as $token) {
            if ($this->isTokenNamespaceKeyword($token)) {
                $readNamespace = true;
                continue;
            }

            if ($this->isTokenClassKeyword($token)) {
                $readClass = true;
                continue;
            }

            if ($readNamespace === true) {
                if ($this->isTokenString($token) || $this->isTokenNsSeparator($token)) {
                    $namespace .= $this->getTokenValue($token);
                    continue;
                } elseif ($this->isTokenSemicolon($token)) {
                    $readNamespace = false;
                    continue;
                }
            }

            if ($readClass === true && $this->isTokenString($token)) {
                $class = $this->getTokenValue($token);
                break;
            }
        }

        //Build the fully-qualified class name and return it
        return $namespace ? $namespace . '\\' . $class : $class;
    }

    /**
     * @param $token
     *
     * @return bool
     */
    private function isTokenNamespaceKeyword($token)
    {
        return is_array($token) && $token[0] == T_NAMESPACE;
    }

    /**
     * @param $token
     *
     * @return bool
     */
    private function isTokenClassKeyword($token)
    {
        return is_array($token) && $token[0] == T_CLASS;
    }

    /**
     * @param $token
     *
     * @return bool
     */
    private function isTokenString($token)
    {
        return is_array($token) && $token[0] == T_STRING;
    }

    /**
     * @param $token
     *
     * @return bool
     */
    private function isTokenNsSeparator($token)
    {
        return is_array($token) && $token[0] == T_NS_SEPARATOR;
    }

    /**
     * @param $token
     *
     * @return string
     */
    private function getTokenValue($token)
    {
        return is_array($token) ? $token[1] : '';
    }

    /**
     * @param $token
     *
     * @return bool
     */
    private function isTokenSemicolon($token)
    {
        return $token === ';';
    }
}
