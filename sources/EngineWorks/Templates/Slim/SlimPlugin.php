<?php
namespace EngineWorks\Slim;

use EngineWorks\Templates\Plugin;
use Slim\Interfaces\RouterInterface;

class SlimPlugin implements Plugin
{
    public function getCallablesTable()
    {
        return [
            'pathFor' => 'pathFor',
            'baseUrl' => 'baseUrl',
        ];
    }

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * SlimPlugin constructor.
     * @param RouterInterface $router
     * @param string $baseUrl
     */
    public function __construct(RouterInterface $router, $baseUrl)
    {
        if (! is_string($baseUrl)) {
            throw new \InvalidArgumentException('baseUrl must be a string');
        }
        $this->router = $router;
        $this->baseUrl = $baseUrl;
    }

    public function pathFor($name, array $data = [], array $queryParams = [])
    {
        return $this->router->pathFor($name, $data, $queryParams);
    }

    public function baseUrl()
    {
        return $this->baseUrl;
    }
}
