<?php

declare(strict_types=1);

namespace EngineWorks\Templates;

use InvalidArgumentException;

class Resolver
{
    /** @var string */
    private $directory;

    /** @var string */
    private $extension;

    /**
     * Templates constructor.
     *
     * @param string $directory Locations where templates are
     * @param string $extension Templates extension
     */
    public function __construct(string $directory = '', string $extension = 'php')
    {
        $this->setDirectory($directory);
        $this->setExtension($extension);
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * Resolve a filename by its friendly name, the real name will be
     * directory + template + extension
     */
    public function resolve(string $template): string
    {
        if (0 === strpos($template, '../') || false !== strpos($template, '/../')) {
            throw new InvalidArgumentException('The filename try to escape the current path');
        }
        return $this->directory . '/' . $template . '.' . $this->extension;
    }
}
