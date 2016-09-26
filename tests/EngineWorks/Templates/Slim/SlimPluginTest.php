<?php
namespace Tests\EngineWorks\Templates\Slim;

use EngineWorks\Templates\Slim\SlimPlugin;
use PHPUnit\Framework\TestCase;
use Slim\Router;

class SlimPluginTest extends TestCase
{
    public function testConstruct()
    {
        $pathFor = 'mocked return';
        $baseUrl = 'foo/bar';
        $router = $this->createMock(Router::class);
        $router->method('pathFor')->willReturn($pathFor);
        $slimPlugin = new SlimPlugin($router, $baseUrl);
        $this->assertSame($baseUrl, $slimPlugin->baseUrl());
        print_r($pathFor, $slimPlugin->pathFor('/', ['foo' => 'bar'], ['bar' => 'gaz']));
    }

    public function testCallablesTable()
    {
        $expectedTableNames = ['pathFor', 'baseUrl'];
        $slimPlugin = new SlimPlugin(new Router(), '');
        $table = $slimPlugin->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }

    public function testConstructExceptionForBaseUrl()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('baseUrl must be a string');

        new SlimPlugin(new Router(), null);
    }
}
