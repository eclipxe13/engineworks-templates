<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests\Slim;

use EngineWorks\Templates\Slim\Slim4Plugin;
use EngineWorks\Templates\Tests\TestCase;
use Slim\Interfaces\RouteParserInterface;

final class Slim4PluginTest extends TestCase
{
    public function testConstruct(): void
    {
        $pathFor = 'mocked return';
        $baseUrl = 'foo/bar';
        $router = $this->createMock(RouteParserInterface::class);
        $router->method('urlFor')->willReturn($pathFor);
        $slim4Plugin = new Slim4Plugin($router, $baseUrl);
        $this->assertSame($baseUrl, $slim4Plugin->baseUrl());
        $this->assertSame($pathFor, $slim4Plugin->pathFor('/', ['foo' => 'bar'], ['bar' => 'gaz']));
    }

    public function testCallablesTable(): void
    {
        $expectedTableNames = ['pathFor', 'baseUrl'];
        $slim4Plugin = new Slim4Plugin($this->createMock(RouteParserInterface::class), '');
        $table = $slim4Plugin->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }
}
