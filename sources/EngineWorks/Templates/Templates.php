<?php
namespace EngineWorks\Templates;

use Psr\Http\Message\ResponseInterface;

class Templates
{
    /** @var Callables */
    private $defaultCallables;

    /** @var Resolver */
    private $defaultResolver;

    /**
     * Templates constructor.
     *
     * @param Callables|null $defaultCallables Default callables object for new objects
     * @param Resolver|null $defaultResolver Default resolver object for new objects
     */
    public function __construct(Callables $defaultCallables = null, Resolver $defaultResolver = null)
    {
        $this->setDefaultCallables($defaultCallables);
        $this->setDefaultResolver($defaultResolver);
    }

    /**
     * @return Callables|null
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
     * @return Resolver|null
     */
    public function getDefaultResolver()
    {
        return $this->defaultResolver;
    }

    /**
     * @param Resolver $defaultResolver
     */
    public function setDefaultResolver(Resolver $defaultResolver = null)
    {
        $this->defaultResolver = $defaultResolver;
    }

    /**
     * Create a Template passing a Callables object
     * If no Callables is provided it try to use the default Callables object
     *
     * @param Callables|null $callables
     * @param Resolver|null $resolver
     * @return Template
     */
    public function create(Callables $callables = null, Resolver $resolver = null)
    {
        $callables = $callables ? : $this->defaultCallables;
        $resolver = $resolver ? : $this->defaultResolver;
        return new Template($callables, $resolver);
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
        return $this->create()->fetch($template, $variables);
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
