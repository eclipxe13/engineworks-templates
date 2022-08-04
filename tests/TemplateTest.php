<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests;

use EngineWorks\Templates\Callables;
use EngineWorks\Templates\Resolver;
use EngineWorks\Templates\Template;

final class TemplateTest extends TestCase
{
    public function testConstructor(): void
    {
        $template = new Template();

        $this->assertInstanceOf(Callables::class, $template->callables());
        $this->assertInstanceOf(Resolver::class, $template->resolver());
    }

    public function testIsValidTemplateFilenameDirectory(): void
    {
        $template = new Template();
        $this->assertFalse($template->isValidTemplateFilename(__DIR__));
    }

    public function testIsValidTemplateFilenameNotExistent(): void
    {
        $template = new Template();
        $this->assertFalse($template->isValidTemplateFilename(__DIR__ . '/does-not-exists.txt'));
    }

    public function testIsValidTemplateFilenameNotReadable(): void
    {
        $template = new Template();
        $tempfile = tempnam('', '');
        if (false === $tempfile) {
            $this->fail('Unable to create a temporary file name');
        }
        chmod($tempfile, 0);

        $this->assertFalse($template->isValidTemplateFilename($tempfile), 'Testing not readable return true');

        chmod($tempfile, 0600);
        unlink($tempfile);
    }

    public function testStaticHello(): void
    {
        $expectedContent = '-- Hello world --';
        $template = new Template();
        $content = $template->fetch($this->samplePath('hello-world'));
        $this->assertEquals($expectedContent, $content);
    }

    public function testDynamicHello(): void
    {
        $expectedContent = '-- Hello Carlos --';
        $template = new Template();
        $content = $template->fetch($this->samplePath('hello-somebody'), ['name' => 'Carlos']);
        $this->assertEquals($expectedContent, $content);
    }

    public function testFetchDoNotAffectTemplateFilename(): void
    {
        $expectedContent = '-- Hello world --';
        $template = new Template();
        $content = $template->fetch($this->samplePath('hello-world'), ['templateFilename' => 'x']);
        $this->assertEquals($expectedContent, $content);
    }

    public function testTemplateWithCallable(): void
    {
        $expectedContent = '-- Hello CARLOS --';
        $template = new Template();
        $template->callables()->add('x', 'strtoupper');

        $content = $template->fetch($this->samplePath('hello-callable'), ['name' => 'Carlos']);
        $this->assertEquals($expectedContent, $content);
    }

    public function testWithInvalidTemplateFile(): void
    {
        $filename = 'non-existent';
        $template = new Template();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Template .* does not exists/');

        $template->fetch($filename);
    }
}
