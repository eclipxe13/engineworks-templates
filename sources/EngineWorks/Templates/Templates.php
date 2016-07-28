<?php
namespace EngineWorks\Templates;

class Templates
{
    /** @var string */
    private $directory;

    /** @var string */
    private $extension;

    /** @var Callables */
    private $defaultCallables;

    public function __construct($directory, Callables $defaultCallables = null, $extension = 'php')
    {
        $this->setDirectory($directory);
        $this->setDefaultCallables($defaultCallables);
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
     * @return Callables
     */
    public function getDefaultCallables()
    {
        return $this->defaultCallables;
    }

    /**
     * @param Callables $defaultCallables
     */
    public function setDefaultCallables(Callables $defaultCallables = null)
    {
        $this->defaultCallables = $defaultCallables;
    }

    public function create(Callables $callables = null)
    {
        $callables = $callables ? : $this->defaultCallables;
        $template = new Template($callables);
        return $template;
    }

    public function filename($template)
    {
        if (0 === strpos($template, '../') || false !== strpos($template, '/../')) {
            throw new \InvalidArgumentException('The filename try to escape the current path');
        }
        return $this->directory . '/' . $template . '.' . $this->extension;
    }

    public function fetch($template, array $variables = [])
    {
        $filename = $this->filename($template);
        return $this->create()->fetch($filename, $variables);
    }
}
