<?php
namespace EngineWorks\Templates;

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
    public function __construct($directory = '', $extension = 'php')
    {
        $this->setDirectory($directory);
        $this->setExtension($extension);
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Resolve a filename by its friendly name, the real name will be
     * directory + template + extension
     *
     * @param string $template
     * @return string
     */
    public function resolve($template)
    {
        if (0 === strpos($template, '../') || false !== strpos($template, '/../')) {
            throw new \InvalidArgumentException('The filename try to escape the current path');
        }
        return $this->directory . '/' . $template . '.' . $this->extension;
    }
}
