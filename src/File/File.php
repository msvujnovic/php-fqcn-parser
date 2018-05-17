<?php

namespace PhpFqcnParser\File;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  17.05.2018
 */
class File
{
    /**
     * @var string
     */
    private $originalPath;

    /**
     * @var string
     */
    private $absolutePath;

    /**
     * @var string
     */
    private $contents;

    private $contentsLoaded = false;

    /**
     * File constructor.
     *
     * @param string $originalPath
     * @param string $absolutePath
     */
    public function __construct($originalPath, $absolutePath = '')
    {
        $this->originalPath = $originalPath;
        $this->absolutePath = $absolutePath;
    }

    /**
     * @return mixed
     */
    public function getOriginalPath()
    {
        return $this->originalPath;
    }

    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        $this->readContents();
        return $this->contents;
    }

    /**
     * @throws AbsolutePathNotSetException
     * @throws FileNotFoundException
     */
    private function readContents()
    {
        if ($this->contentsLoaded) {
            return;
        }

        if (empty($this->absolutePath)) {
            throw new AbsolutePathNotSetException($this->originalPath);
        }

        if (!file_exists($this->absolutePath)) {
            throw new FileNotFoundException($this->absolutePath);
        }

        $this->contents = file_get_contents($this->absolutePath);
        $this->contentsLoaded = true;
    }
}
