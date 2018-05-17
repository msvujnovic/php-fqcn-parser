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
     * @var int
     */
    private $pathType;
    /**
     * @var string
     */
    private $basePath;

    /**
     * Options constructor.
     *
     * @param int    $pathType
     * @param string $basePath
     */
    public function __construct($pathType, $basePath)
    {
        $this->setPathType($pathType);
        $this->setBasePath($basePath);
    }

    /**
     * @param $pathType
     *
     * @throws InvalidOptionException
     */
    private function validatePathType($pathType)
    {
        $supportedTypes = [static::PATH_TYPE_RELATIVE, static::PATH_TYPE_ABSOLUTE];

        if (!in_array($pathType, $supportedTypes)) {
            $supportedTypesString = implode($supportedTypes, ', ');
            throw new InvalidOptionException(
                "Path type set to unsupported value $pathType. Supported values: $supportedTypesString"
            );
        }
    }

    /**
     * @return int
     */
    public function getPathType()
    {
        return $this->pathType;
    }

    /**
     * @param int $pathType
     *
     * @return $this
     */
    public function setPathType($pathType)
    {
        $this->validatePathType($pathType);
        $this->pathType = $pathType;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }
}
