<?php
namespace EngineWorks\Templates;

class Template
{
    private $callables;

    public function __construct(Callables $callables = null)
    {
        $this->callables = ($callables) ? : new Callables();
    }

    public function callables()
    {
        return $this->callables;
    }

    public function __call($name, $arguments)
    {
        return $this->callables->call($name, $arguments);
    }

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

    public function fetch($templateFilename, array $templateVariables = [])
    {
        if (! $this->isValidTemplateFilename($templateFilename, $errorMessage)) {
            throw new \InvalidArgumentException($errorMessage);
        }
        if (! ob_start()) {
            throw new \RuntimeException('Cannot create a new buffer');
        }
        extract($templateVariables, EXTR_SKIP);
        require $templateFilename;
        return ob_get_clean();
    }
}
