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
    protected $absolutePath;

    /**
     * File constructor.
     *
     * @param string $absolutePath
     */
    public function __construct($absolutePath)
    {
        $this->absolutePath = $absolutePath;
    }

    /**
     * @return bool
     */
    public function doesExist()
    {
        if (!file_exists($this->absolutePath)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function getContents()
    {
        if (!$this->doesExist()) {
            throw new FileNotFoundException($this->absolutePath);
        }

        return file_get_contents($this->absolutePath);
    }
}
