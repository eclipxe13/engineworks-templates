<?php
namespace Tests\EngineWorks\Templates;

use EngineWorks\Templates\Callables;
use EngineWorks\Templates\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public function testConstructor()
    {
        $template = new Template();

        $this->assertInstanceOf(Callables::class, $template->callables());
    }

    public function testIsValidTemplateFilenameDirectory()
    {
        $template = new Template();
        $this->assertFalse($template->isValidTemplateFilename(__DIR__));
    }

    public function testIsValidTemplateFilenameNotExistent()
    {
        $template = new Template();
        $this->assertFalse($template->isValidTemplateFilename(__DIR__ . '/does-not-exists.txt'));
    }

    public function testIsValidTemplateFilenameNotReadable()
    {
        $template = new Template();
        $tempfile = tempnam(null, null);
        chmod($tempfile, 0);

        $this->assertFalse($template->isValidTemplateFilename($tempfile), 'Testing not readable return true');

        chmod($tempfile, 0600);
        unlink($tempfile);
    }

    public function testStaticHello()
    {
        $expectedContent = '-- Hello world --';
        $template = new Template();
        $content = $template->fetch($this->samplesFile('hello-world.php'));
        $this->assertEquals($expectedContent, $content);
    }

    public function testDynamicHello()
    {
        $expectedContent = '-- Hello Carlos --';
        $template = new Template();
        $content = $template->fetch($this->samplesFile('hello-somebody.php'), ['name' => 'Carlos']);
        $this->assertEquals($expectedContent, $content);
    }

    public function testFetchDoNotAffectTemplateFilename()
    {
        $expectedContent = '-- Hello world --';
        $template = new Template();
        $content = $template->fetch($this->samplesFile('hello-world.php'), ['templateFilename' => 'x']);
        $this->assertEquals($expectedContent, $content);
    }

    public function testTemplateWithCallable()
    {
        $expectedContent = '-- Hello CARLOS --';
        $template = new Template();
        $template->callables()->add('x', 'strtoupper');

        $content = $template->fetch($this->samplesFile('hello-callable.php'), ['name' => 'Carlos']);
        $this->assertEquals($expectedContent, $content);
    }

    public function testWithInvalidTemplateFile()
    {
        $filename = 'non-existent.php';
        $template = new Template();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Template $filename does not exists");

        $template->fetch($filename);
    }

    /**
     * Helper function
     *
     * @param string $filename
     * @return string
     */
    private function samplesFile($filename = '')
    {
        $path = Utils::samples();
        return $path . (('' !== $filename) ? '/' . $filename : '');
    }
}
