<?php
namespace EngineWorks\Templates\Tests\Slim;

use EngineWorks\Templates\Slim\Slim4Plugin;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteParserInterface;

class Slim4PluginTest extends TestCase
{
    public function testConstruct()
    {
        $pathFor = 'mocked return';
        $baseUrl = 'foo/bar';
        $router = $this->createMock(RouteParserInterface::class);
        $router->method('urlFor')->willReturn($pathFor);
        $slim4Plugin = new Slim4Plugin($router, $baseUrl);
        $this->assertSame($baseUrl, $slim4Plugin->baseUrl());
        print_r($pathFor, $slim4Plugin->pathFor('/', ['foo' => 'bar'], ['bar' => 'gaz']));
    }

    public function testCallablesTable()
    {
        $expectedTableNames = ['pathFor', 'baseUrl'];
        $slim4Plugin = new Slim4Plugin($this->createMock(RouteParserInterface::class), '');
        $table = $slim4Plugin->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }
}
