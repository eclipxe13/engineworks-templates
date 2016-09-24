<?php
namespace EngineWorks\Templates;

use Psr\Http\Message\ResponseInterface;

class Templates
{
    /** @var string */
    private $directory;

    /** @var string */
    private $extension;

    /** @var Callables */
    private $defaultCallables;

    /**
     * Templates constructor.
     *
     * @param string $directory Locations where templates are
     * @param Callables|null $defaultCallables Default callables object for new objects
     * @param string $extension Templates extension
     */
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

    /**
     * Create a Template passing a Callables object
     * If no Callables is provided it try to use the default Callables object
     *
     * @param Callables|null $callables
     * @return Template
     */
    public function create(Callables $callables = null)
    {
        $callables = $callables ? : $this->defaultCallables;
        $template = new Template($callables);
        return $template;
    }

    /**
     * Resolve a filename by its friendly name, the real name will be
     * directory + template + extension
     *
     * @param string $template
     * @return string
     */
    public function filename($template)
    {
        if (0 === strpos($template, '../') || false !== strpos($template, '/../')) {
            throw new \InvalidArgumentException('The filename try to escape the current path');
        }
        return $this->directory . '/' . $template . '.' . $this->extension;
    }

    /**
     * Create a template from its frienly name using the specified variables.
     *
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function fetch($template, array $variables = [])
    {
        $filename = $this->filename($template);
        return $this->create()->fetch($filename, $variables);
    }

    /**
     * Return the response object with the return value of the fetched template
     * Use this function as a compatibility method with PSR-7
     *
     * @param ResponseInterface $response
     * @param string $template
     * @param array $variables
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, array $variables = [])
    {
        $response->getBody()->write($this->fetch($template, $variables));
        return $response;
    }
}
