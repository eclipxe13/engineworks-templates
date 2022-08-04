<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests\Plugins;

use DateTime;
use EngineWorks\Templates\Plugin;
use EngineWorks\Templates\Plugins\Transliterate;
use EngineWorks\Templates\Tests\Mocks\ObjectToString;
use EngineWorks\Templates\Tests\TestCase;

final class TransliterateTest extends TestCase
{
    public function testConstructor(): void
    {
        $trans = new Transliterate();

        $this->assertInstanceOf(Plugin::class, $trans);
        $this->assertTrue(is_callable($trans->getDefaultEncoder()));
    }

    public function testCallablesTable(): void
    {
        $expectedTableNames = ['tr'];
        $html = new Transliterate();
        $table = $html->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }

    public function testNullEncoderStaticMethod(): void
    {
        $expected = '<p>"Lorem & Ipsum\'\0x02</p>';
        $message = '<p>"Lorem & Ipsum\'\0x02</p>';

        $trans = new Transliterate();
        $this->assertSame($expected, $trans::nullEncoder($message));
    }

    /**
     * @return array<string, array{string, array<string, mixed>, string}>
     */
    public function providerTransliterateUpperCase(): array
    {
        return [
            'normal' => ['hello {name}! {name}, are you ok?', ['name' => 'world'], 'hello WORLD! WORLD, are you ok?'],
            'object::__toString' => ['hello {name}!', ['name' => new ObjectToString('world')], 'hello WORLD!'],
            'array' => ['hello {name}!', ['name' => ['foo', new ObjectToString('bar'), ['BAZ']]], 'hello FOOBARBAZ!'],
            'integer' => ['hello {name}!', ['name' => 12345], 'hello 12345!'],
            'null' => ['hello {name}!', ['name' => null], 'hello !'],
            'invalid' => ['hello {name}!', ['name' => new DateTime()], 'hello !'],
            'two times' => ['{name} - {name}', ['name' => 'FOO'], 'FOO - FOO'],
            'some non-existent' => ['{name} - {age}', ['name' => 'FOO'], 'FOO - {age}'],
            'all non-existent' => ['{last} - {age}', ['name' => 'FOO'], '{last} - {age}'],
            'no arguments' => ['{last} - {age}', [], '{last} - {age}'],
            'no keyworks' => ['lorem ipsum', ['x' => 'foo'], 'lorem ipsum'],
            'double curly braces' => ['{{name}}', ['name' => 'foo'], '{FOO}'],
        ];
    }

    /**
     * @param array<string, mixed> $arguments
     * @dataProvider providerTransliterateUpperCase
     */
    public function testTransliterateUpperCase(string $message, array $arguments, string $expected): void
    {
        $trans = new Transliterate('strtoupper');
        $retrieved = $trans->transliterate($message, $arguments);
        $this->assertEquals($expected, $retrieved);
    }

    /**
     * @return array<string, mixed[]>
     */
    public function providerGetCurlyBracesKeys(): array
    {
        return [
            'unique' => ['{simple}', ['{simple}']],
            'trail' => ['hello {you}', ['{you}']],
            'lead' => ['{you} looks great', ['{you}']],
            'inner' => ['Point (x:{x},  y:{y})', ['{x}', '{y}']],
            'with dots' => ['this {has.dot} key', ['{has.dot}']],
            'with spaces' => ['{   with   spaces   }', ['{   with   spaces   }']],
            'multiple times' => ['{x}{x}{x}{y}', ['{x}', '{y}']],
            'only spaces' => ['{    }', ['{    }']],
            'tab' => ["{\t}", ["{\t}"]],
            'inner braces' => ['{{name} or {gender}}', ['{name}', '{gender}']],
            'double braces' => ['{{x}}', ['{x}']],
            'triple braces' => ['{{{some}}}', ['{some}']],
            'multi trail' => ['{{{{some}', ['{some}']],
            'multi lead' => ['{some}}}}', ['{some}']],
            'multi with spaces' => ['{{{{{ some }}}}}', ['{ some }']],
            'inner lead ' => ['{....}.....}', ['{....}']],
            'inner trail' => ['{.....{....}', ['{....}']],
            'inner lead and trail' => ['{....}{....}', ['{....}']],
            'inner invalid' => ['{....{}....}', []],
            'any' => ['this does not contains any', []],
            'invalid open' => ['contains only { open', []],
            'invalid close' => ['contains only } close', []],
            'invalid new line' => ["{invalid\nkey}", []],
            'invalid empty braces' => ['{}', []],
            'invalid double empty' => ['{{}}', []],
            'invalid inner lead' => ['{lead {}}', []],
            'invalid inner trail' => ['{{}  trail}', []],
            'invalid empty' => ['', []],
        ];
    }

    /**
     * @param $message
     * @param $expected
     * @dataProvider providerGetCurlyBracesKeys
     * @param string[]|mixed[] $expected
     */
    public function testGetCurlyBracesKeys(string $message, array $expected): void
    {
        $trans = new Transliterate();
        $this->assertEquals($expected, array_values($trans->getCurlyBracesKeys($message)));
    }
}
