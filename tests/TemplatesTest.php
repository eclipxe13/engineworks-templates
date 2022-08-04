<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests;

use EngineWorks\Templates\Callables;
use EngineWorks\Templates\Resolver;
use EngineWorks\Templates\Templates;
use Slim\Psr7\Response;

final class TemplatesTest extends TestCase
{
    /** @var Templates */
    private $templates;

    protected function setUp(): void
    {
        $this->templates = new Templates(null, null);
    }

    public function testConstructor(): void
    {
        $this->assertNull($this->templates->getDefaultCallables());
        $this->assertNull($this->templates->getDefaultResolver());
    }

    public function testCreateTemplateUsesDefaultCallablesWhenNoneProvided(): void
    {
        $callables = new Callables();
        $this->templates->setDefaultCallables($callables);
        $template = $this->templates->create();
        $this->assertSame($callables, $template->callables());
    }

    public function testCreateTemplateUsesDefaultResolverWhenNoneProvided(): void
    {
        $resolver = new Resolver();
        $this->templates->setDefaultResolver($resolver);
        $template = $this->templates->create();
        $this->assertSame($resolver, $template->resolver());
    }

    public function testCreateTemplatesUsesSpecifiedCallables(): void
    {
        $callables = new Callables();
        $this->templates->setDefaultCallables(null);
        $template = $this->templates->create($callables);
        $this->assertSame($callables, $template->callables());
    }

    public function testCreateTemplatesUsesSpecifiedResolver(): void
    {
        $resolver = new Resolver();
        $this->templates->setDefaultResolver(null);
        $template = $this->templates->create(null, $resolver);
        $this->assertSame($resolver, $template->resolver());
    }

    public function testFetchUsingStaticHelloWorldSample(): void
    {
        $this->templates->setDefaultResolver(new Resolver($this->samplePath(), 'php'));

        $expectedContent = '-- Hello world --';
        $content = $this->templates->fetch('hello-world');
        $this->assertEquals($expectedContent, $content);
    }

    public function testRender(): void
    {
        // use the Slim Response implementation
        $response = new Response();

        // same as templateTest::samplesFile
        $this->templates->setDefaultResolver(new Resolver($this->samplePath(), 'php'));

        $expectedContent = '-- Hello Response --';
        $response = $this->templates->render($response, 'hello-somebody', ['name' => 'Response']);
        $this->assertEquals($expectedContent, $response->getBody());
    }

    public function testFetchRecursive(): void
    {
        $this->templates->setDefaultResolver(new Resolver($this->samplePath(), 'php'));

        $fetched = $this->templates->fetch('recursive', [
            'value' => 1,
        ]);
        $expected = implode("\n", range(1, 10)) . "\n";
        $this->assertEquals($expected, $fetched);
    }
}
