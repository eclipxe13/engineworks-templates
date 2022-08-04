<?php

declare(strict_types=1);

namespace EngineWorks\Templates;

use InvalidArgumentException;
use RuntimeException;

class Template
{
    /** @var Callables */
    private $callables;

    /** @var Resolver */
    private $resolver;

    /**
     * Template constructor.
     *
     * @param Callables|null $callables If NULL then an empty Callables will be created
     * @param Resolver|null $resolver If NULL then a basic Resolver will be created
     */
    public function __construct(Callables $callables = null, Resolver $resolver = null)
    {
        $this->callables = $callables ?? new Callables();
        $this->resolver = $resolver ?? new Resolver();
    }

    /**
     * Retrieve the Callables object
     */
    public function callables(): Callables
    {
        return $this->callables;
    }

    /**
     * Retrieve the Resolver object
     */
    public function resolver(): Resolver
    {
        return $this->resolver;
    }

    /**
     * Magic method to export calls to $this into calls to Callables
     *
     * @param string $name
     * @param array<mixed> $arguments
     * @return string
     */
    public function __call(string $name, array $arguments): string
    {
        return $this->callables->call($name, $arguments);
    }

    /**
     * Retrieve if a resolved file is a valid template
     * If return FALSE then the $errorMessage variable is populated
     */
    public function isValidTemplateFilename(string $templateFilename, string &$errorMessage = ''): bool
    {
        if (! file_exists($templateFilename)) {
            $errorMessage = "Template $templateFilename does not exists";
            return false;
        }
        if (! is_readable($templateFilename)) {
            $errorMessage = "Template $templateFilename is not readable";
            return false;
        }
        if (is_dir($templateFilename)) {
            $errorMessage = "Template $templateFilename is a directory";
            return false;
        }
        return true;
    }

    /**
     * Fetch and return the content of a templates passing the specified variables.
     * Inside the template the variable $this refer to exactly this template object
     *
     * @param array<string, mixed> $templateVariables
     */
    public function fetch(string $templateName, array $templateVariables = []): string
    {
        $templateName = $this->resolver->resolve($templateName);
        $errorMessage = '';
        if (! $this->isValidTemplateFilename($templateName, $errorMessage)) {
            throw new InvalidArgumentException($errorMessage);
        }
        if (! ob_start()) {
            throw new RuntimeException('Cannot create a new buffer');
        }
        // as we are using EXTR_OVERWRITE lets remove $templateName if set
        unset($templateVariables['templateName']);
        extract($templateVariables, EXTR_OVERWRITE);
        require $templateName;
        return (string) ob_get_clean();
    }
}
