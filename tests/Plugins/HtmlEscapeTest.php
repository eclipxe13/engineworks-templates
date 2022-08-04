<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests\Plugins;

use EngineWorks\Templates\Plugins\HtmlEscape;
use EngineWorks\Templates\Tests\TestCase;

final class HtmlEscapeTest extends TestCase
{
    public function testConstructor(): void
    {
        $html = new HtmlEscape();
        $this->assertSame(ENT_COMPAT | ENT_HTML5, $html->getDefaultHtmlFlags());
    }

    public function testCallablesTable(): void
    {
        $expectedTableNames = ['e', 'js', 'ejs', 'uri', 'url', 'qry'];
        $html = new HtmlEscape();
        $table = $html->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }

    public function testHtml(): void
    {
        $text = '<br class="br" />\'&';
        $expected = '&lt;br class=&quot;br&quot; /&gt;\'&amp;';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->html($text));
    }

    public function testJavascript(): void
    {
        $text = 'var x = "foo";';
        $expected = 'var x = \"foo\";';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->javascript($text));
    }

    public function testJavascriptInHtml(): void
    {
        $text = "x = '1'; y = \"2\";";
        $expected = "x = \\'1\\'; y = &quot;2&quot;;";
        $html = new HtmlEscape();
        $this->assertSame($expected, $html->javascriptInHtml($text));
    }

    public function testUri(): void
    {
        $text = ':/?#[]@!$&\'()*+,;" ';
        $expected = '%3A%2F%3F%23%5B%5D%40%21%24%26%27%28%29%2A%2B%2C%3B%22%20';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->uri($text));
    }

    public function testUrl(): void
    {
        $path = '/index/?some&baz=9&foo=x#anchor';
        $vars = ['foo' => 1, 'bar' => 2];
        $expected = '/index/?some=&baz=9&foo=1&bar=2#anchor';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->url($path, $vars));
    }

    public function testUrlWithOutQueryStringFragmentAndVars(): void
    {
        $path = '/index';
        $expected = '/index';

        $html = new HtmlEscape();
        $this->assertSame($expected, $html->url($path));
    }
}
