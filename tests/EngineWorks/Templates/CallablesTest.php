<?php
namespace Tests\EngineWorks\Templates;

use EngineWorks\Templates\Callables;
use EngineWorks\Templates\Plugins\HtmlEscape;
use EngineWorks\Templates\Plugins\Transliterate;
use PHPUnit\Framework\TestCase;

class CallablesTest extends TestCase
{
    /** @var Callables */
    private $callables;

    protected function setUp()
    {
        $this->callables = new Callables();
    }

    public function testConstructor()
    {
        $this->assertSame([], $this->callables->names());
    }

    public function testExists()
    {
        $this->callables->add('x', 'strtoupper');
        $this->assertFalse($this->callables->exists('y'));
        $this->assertTrue($this->callables->exists('x'));
    }

    public function testAddUsingFunctionName()
    {
        $this->callables->add('x', 'strtoupper');
        $this->callables->add('y', 'strtolower');
        $this->assertCount(2, $this->callables);
    }

    public function testAddUsingStaticMethod()
    {
        $this->callables->add('x', [\DateTime::class, 'createFromFormat']);
        $this->assertTrue($this->callables->exists('x'));
    }

    public function testAddUsingObjectMethod()
    {
        $this->callables->add('x', [$this, 'testAddUsingObjectMethod']);
        $this->assertTrue($this->callables->exists('x'));
    }

    public function testAddUsingClousure()
    {
        $this->callables->add('x', function () {
            return;
        });
        $this->assertTrue($this->callables->exists('x'));
    }

    public function testNames()
    {
        $this->callables->add('x', 'strtoupper');
        $this->callables->add('y', 'ucfirst');
        $this->assertSame(['x', 'y'], $this->callables->names());
    }

    public function testRemove()
    {
        // populate
        $this->callables->add('x', 'strtoupper');
        $this->callables->add('y', 'ucfirst');
        $this->assertCount(2, $this->callables);

        // remove
        $this->callables->remove('x');
        $this->callables->remove('y');
        // non existent remove does not throw any exception
        $this->callables->remove('non-existent');
        $this->assertCount(0, $this->callables);
    }

    public function testGet()
    {
        $this->callables->add('x', 'strtoupper');
        $this->assertNotNull($this->callables->get('x'));
        $this->assertNull($this->callables->get('y'));
    }

    public function testAttachAll()
    {
        $html = new HtmlEscape();
        $transliterate = new Transliterate();
        $expectedTable = array_merge(
            array_keys($html->getCallablesTable()),
            array_keys($transliterate->getCallablesTable())
        );

        $this->callables->attachAll([
            $html,
            null,
            $transliterate,
            new \stdClass(),
            [],
            123,
        ]);
        $this->assertEquals($expectedTable, $this->callables->names());
    }

    public function testAttachDetach()
    {
        $plugin = new HtmlEscape();
        $this->callables->attach($plugin);
        $this->assertSame(array_keys($plugin->getCallablesTable()), $this->callables->names());
        $this->callables->detach($plugin);
        $this->assertCount(0, $this->callables);
    }

    public function testCall()
    {
        $parameter = 'Foo Bar';
        $expected = 'FOO BAR';

        $this->callables->add('x', 'strtoupper');
        $this->assertSame($expected, $this->callables->call('x', [$parameter]));
    }
}
