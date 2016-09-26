<?php
namespace Tests\EngineWorks\Templates;

use EngineWorks\Templates\Callables;
use EngineWorks\Templates\Templates;
use PHPUnit\Framework\TestCase;
use Slim\Http\Response;

class TemplatesTest extends TestCase
{
    /** @var Templates */
    private $templates;

    protected function setUp()
    {
        $this->templates = new Templates(__DIR__, null, 'phtml');
    }

    public function testConstructor()
    {
        $this->assertSame(__DIR__, $this->templates->getDirectory());
        $this->assertSame('phtml', $this->templates->getExtension());
        $this->assertNull($this->templates->getDefaultCallables());
    }

    public function testCreateTemplateUsesDefaultCallablesWhenNoneProvided()
    {
        $callables = new Callables();
        $this->templates->setDefaultCallables($callables);
        $template = $this->templates->create();
        $this->assertSame($callables, $template->callables());
    }

    public function testCreateTemplatesUsesSpecifiedCallables()
    {
        $callables = new Callables();
        $this->templates->setDefaultCallables(null);
        $template = $this->templates->create($callables);
        $this->assertSame($callables, $template->callables());
    }

    public function testFilenameMethod()
    {
        $name = 'basename';
        $expected = __DIR__ . '/' . $name . '.phtml';
        $this->assertEquals($expected, $this->templates->filename($name));
    }

    public function providerFilenameThrowsException()
    {
        return [
            ['../other'],
            ['./../'],
            ['/folder/../folder/'],
        ];
    }

    /**
     * @param $path
     * @dataProvider providerFilenameThrowsException
     */
    public function testFilenameThrowsException($path)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('');
        $this->templates->filename($path);
    }

    public function testFetchUsingStaticHelloWorldSample()
    {
        // same as templateTest::samplesFile
        $path = realpath(__DIR__ . '/../../samples');
        $this->templates->setDirectory($path);
        $this->templates->setExtension('php');

        $expectedContent = '-- Hello world --';
        $content = $this->templates->fetch('hello-world');
        $this->assertEquals($expectedContent, $content);
    }

    public function testRender()
    {
        // use the Slim Response implementation
        $response = new Response();

        // same as templateTest::samplesFile
        $path = realpath(__DIR__ . '/../../samples');
        $this->templates->setDirectory($path);
        $this->templates->setExtension('php');

        $expectedContent = '-- Hello Response --';
        $response = $this->templates->render($response, 'hello-somebody', ['name' => 'Response']);
        $this->assertEquals($expectedContent, $response->getBody());
    }
}
