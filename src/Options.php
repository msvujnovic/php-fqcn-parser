<?php

namespace PhpFqcnParser;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  17.05.2018
 */
class Options
{
    const PATH_TYPE_RELATIVE = 'relative';
    const PATH_TYPE_ABSOLUTE = 'absolute';

    /**
     * @var string
     */
    protected $pathType;
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @return bool
     */
    public function isPathTypeRelative()
    {
        return $this->pathType === static::PATH_TYPE_RELATIVE;
    }

    /**
     * @param string $basePath
     *
     * @return $this
     * @throws InvalidOptionException
     */
    public function setPathTypeRelative($basePath)
    {
        if (!is_dir($basePath)) {
            throw new InvalidOptionException("Nonexistent base path passed when setting path type as relative");
        }

        $this->pathType = static::PATH_TYPE_RELATIVE;
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @return $this
     */
    public function setPathTypeAbsolute()
    {
        $this->pathType = static::PATH_TYPE_ABSOLUTE;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
}
