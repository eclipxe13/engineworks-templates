<?php
namespace EngineWorks\Templates;

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
        $this->callables = ($callables) ? : new Callables();
        $this->resolver = ($resolver) ? : new Resolver();
    }

    /**
     * Retrieve the Callables object
     *
     * @return Callables
     */
    public function callables()
    {
        return $this->callables;
    }

    /**
     * Retrieve the Resolver object
     *
     * @return Resolver
     */
    public function resolver()
    {
        return $this->resolver;
    }

    /**
     * Magic method to export calls to $this into calls to Callables
     *
     * @param string $name
     * @param mixed $arguments
     * @return string
     */
    public function __call($name, $arguments)
    {
        return $this->callables->call($name, $arguments);
    }

    /**
     * Retrieve if a resolved file is a valid template
     * If return FALSE then the $errorMessage variable is populated
     *
     * @param string $templateFilename
     * @param string $errorMessage
     * @return bool
     */
    public function isValidTemplateFilename($templateFilename, &$errorMessage = '')
    {
        if (! file_exists($templateFilename)) {
            $errorMessage = "Template {$templateFilename} does not exists";
            return false;
        }
        if (! is_readable($templateFilename)) {
            $errorMessage = "Template {$templateFilename} is not readable";
            return false;
        }
        if (is_dir($templateFilename)) {
            $errorMessage = "Template {$templateFilename} is a directory";
            return false;
        }
        return true;
    }

    /**
     * Fetch and return the content of a templates passing the specified variables.
     * Inside the template the variable $this refer to exactly this template object
     *
     * @param string $templateName
     * @param array $templateVariables
     * @return string
     */
    public function fetch($templateName, array $templateVariables = [])
    {
        $templateName = $this->resolver->resolve($templateName);
        if (! $this->isValidTemplateFilename($templateName, $errorMessage)) {
            throw new \InvalidArgumentException($errorMessage);
        }
        if (! ob_start()) {
            throw new \RuntimeException('Cannot create a new buffer');
        }
        // as we are using EXTR_OVERWRITE lets remove $templateName if set
        unset($templateVariables['templateName']);
        extract($templateVariables, EXTR_OVERWRITE);
        /** @noinspection PhpIncludeInspection */
        require $templateName;
        return ob_get_clean();
    }
}
