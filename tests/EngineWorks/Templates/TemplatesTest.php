<?php
namespace Tests\EngineWorks\Templates;

use EngineWorks\Templates\Callables;
use EngineWorks\Templates\Resolver;
use EngineWorks\Templates\Templates;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;

class TemplatesTest extends TestCase
{
    /** @var Templates */
    private $templates;

    protected function setUp()
    {
        $this->templates = new Templates(null, null);
    }

    public function testConstructor()
    {
        $this->assertNull($this->templates->getDefaultCallables());
        $this->assertNull($this->templates->getDefaultResolver());
    }

    public function testCreateTemplateUsesDefaultCallablesWhenNoneProvided()
    {
        $callables = new Callables();
        $this->templates->setDefaultCallables($callables);
        $template = $this->templates->create();
        $this->assertSame($callables, $template->callables());
    }

    public function testCreateTemplateUsesDefaultResolverWhenNoneProvided()
    {
        $resolver = new Resolver();
        $this->templates->setDefaultResolver($resolver);
        $template = $this->templates->create();
        $this->assertSame($resolver, $template->resolver());
    }

    public function testCreateTemplatesUsesSpecifiedCallables()
    {
        $callables = new Callables();
        $this->templates->setDefaultCallables(null);
        $template = $this->templates->create($callables);
        $this->assertSame($callables, $template->callables());
    }

    public function testCreateTemplatesUsesSpecifiedResolver()
    {
        $resolver = new Resolver();
        $this->templates->setDefaultResolver(null);
        $template = $this->templates->create(null, $resolver);
        $this->assertSame($resolver, $template->resolver());
    }

    public function testFetchUsingStaticHelloWorldSample()
    {
        $this->templates->setDefaultResolver(new Resolver(Utils::samples(), 'php'));

        $expectedContent = '-- Hello world --';
        $content = $this->templates->fetch('hello-world');
        $this->assertEquals($expectedContent, $content);
    }

    public function testRender()
    {
        // use the Slim Response implementation
        $response = new Response();

        // same as templateTest::samplesFile
        $this->templates->setDefaultResolver(new Resolver(Utils::samples(), 'php'));

        $expectedContent = '-- Hello Response --';
        $response = $this->templates->render($response, 'hello-somebody', ['name' => 'Response']);
        $this->assertEquals($expectedContent, $response->getBody());
    }

    public function testFetchRecursive()
    {
        $this->templates->setDefaultResolver(new Resolver(Utils::samples(), 'php'));

        $fetched = $this->templates->fetch('recursive', [
            'value' => 1,
        ]);
        $expected = implode("\n", range(1, 10)) . "\n";
        $this->assertEquals($expected, $fetched);
    }
}
