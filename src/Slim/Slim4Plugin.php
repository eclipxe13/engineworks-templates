<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Slim;

use EngineWorks\Templates\Plugin;
use Slim\Interfaces\RouteParserInterface;

class Slim4Plugin implements Plugin
{
    /**
     * @inheritdoc
     * @return array{pathFor: string, baseUrl: string}
     */
    public function getCallablesTable(): array
    {
        return [
            'pathFor' => 'pathFor',
            'baseUrl' => 'baseUrl',
        ];
    }

    /** @var RouteParserInterface */
    private $router;

    /** @var string */
    private $baseUrl;

    public function __construct(RouteParserInterface $router, string $baseUrl)
    {
        $this->router = $router;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $name
     * @param string[] $data
     * @param string[] $queryParams
     * @return string
     */
    public function pathFor(string $name, array $data = [], array $queryParams = []): string
    {
        return $this->router->urlFor($name, $data, $queryParams);
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }
}
