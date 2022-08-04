<?php

declare(strict_types=1);

namespace EngineWorks\Templates;

use Psr\Http\Message\ResponseInterface;

class Templates
{
    /** @var Callables|null */
    private $defaultCallables;

    /** @var Resolver|null */
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

    public function getDefaultCallables(): ?Callables
    {
        return $this->defaultCallables;
    }

    public function setDefaultCallables(Callables $defaultCallables = null): void
    {
        $this->defaultCallables = $defaultCallables;
    }

    public function getDefaultResolver(): ?Resolver
    {
        return $this->defaultResolver;
    }

    public function setDefaultResolver(Resolver $defaultResolver = null): void
    {
        $this->defaultResolver = $defaultResolver;
    }

    /**
     * Create a Template passing a Callables object
     * If no Callables is provided it try to use the default Callables object
     *
     * @param Callables|null $callables
     * @param Resolver|null $resolver
     */
    public function create(Callables $callables = null, Resolver $resolver = null): Template
    {
        $callables = $callables ?? $this->defaultCallables;
        $resolver = $resolver ?? $this->defaultResolver;
        return new Template($callables, $resolver);
    }

    /**
     * Create a template from its frienly name using the specified variables.
     *
     * @param array<string, mixed> $variables
     */
    public function fetch(string $template, array $variables = []): string
    {
        return $this->create()->fetch($template, $variables);
    }

    /**
     * Return the response object with the return value of the fetched template
     * Use this function as a compatibility method with PSR-7
     *
     * @param array<string, mixed> $variables
     */
    public function render(ResponseInterface $response, string $template, array $variables = []): ResponseInterface
    {
        $response->getBody()->write($this->fetch($template, $variables));
        return $response;
    }
}
