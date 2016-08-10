<?php
namespace Tests\EngineWorks\Templates\Plugins;

use EngineWorks\Templates\Plugins\HtmlEscape;
use PHPUnit\Framework\TestCase;

class HtmlEscapeTest extends TestCase
{
    public function testConstructor()
    {
        $html = new HtmlEscape();
        $this->assertSame(ENT_COMPAT | ENT_HTML5, $html->getDefaultHtmlFlags());
    }

    public function testCallablesTable()
    {
        $expectedTableNames = ['e', 'js', 'ejs', 'uri', 'url', 'qry'];
        $html = new HtmlEscape();
        $table = $html->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }

    public function testHtml()
    {
        $text = '<br class="br" />\'&';
        $expected = '&lt;br class=&quot;br&quot; /&gt;\'&amp;';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->html($text));
    }

    public function testSetDefaultHtmlFlagsThrowsException()
    {
        $html = new HtmlEscape();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The default html flags value is not valid');

        $html->setDefaultHtmlFlags('');
    }

    public function testJavascript()
    {
        $text = 'var x = "foo";';
        $expected = 'var x = \"foo\";';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->javascript($text));
    }

    public function testJavascriptInHtml()
    {
        $text = "x = '1'; y = \"2\";";
        $expected = "x = \\'1\\'; y = &quot;2&quot;;";
        $html = new HtmlEscape();
        $this->assertSame($expected, $html->javascriptInHtml($text));
    }

    public function testUri()
    {
        $text = ':/?#[]@!$&\'()*+,;" ';
        $expected = '%3A%2F%3F%23%5B%5D%40%21%24%26%27%28%29%2A%2B%2C%3B%22%20';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->uri($text));
    }

    public function testUrl()
    {
        $path = '/index/?some&baz=9&foo=x#anchor';
        $vars = ['foo' => 1, 'bar' => 2];
        $expected = '/index/?some=&baz=9&foo=1&bar=2#anchor';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->url($path, $vars));
    }

    public function testUrlWithOutQueryStringFragmentAndVars()
    {
        $path = '/index';
        $expected = '/index';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->url($path));
    }

    public function testUrlInvalidUrl()
    {
        $html = new HtmlEscape();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The url is not a string');
        $html->url(new \stdClass());
    }
}
