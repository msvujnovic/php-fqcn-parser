<?php

namespace PhpFqcnParser\File;

use Illuminate\Support\Collection;

/**
 * @author Marko S. Vujnovic <msvujnovic@gmail.com>
 * @since  17.05.2018
 */
class FileFactory
{
    /**
     * @param string[] $relativePaths
     * @param string   $basePath
     *
     * @return Collection|File[]
     */
    public function createFromRelativeAndBasePath(array $relativePaths, $basePath)
    {
        $basePath = rtrim($basePath, DIRECTORY_SEPARATOR);

        return Collection::make($relativePaths)->map(function ($path) use ($basePath) {
            $relativePath = ltrim($path, DIRECTORY_SEPARATOR);
            $absolutePath = $basePath . DIRECTORY_SEPARATOR . $relativePath;
            return new File($absolutePath);
        });
    }

    /**
     * @param string[] $absolutePaths
     *
     * @return Collection|File[]
     */
    public function createFromAbsolutePaths(array $absolutePaths)
    {
        return Collection::make($absolutePaths)->map(function ($path) {
            return new File($path);
        });
    }
}
