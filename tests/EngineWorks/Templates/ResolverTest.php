<?php
namespace Tests\EngineWorks\Templates;

use EngineWorks\Templates\Resolver;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    public function testConstructorWithDefaultValues()
    {
        $resolver = new Resolver();
        $this->assertSame('', $resolver->getDirectory());
        $this->assertSame('php', $resolver->getExtension());
    }

    public function testConstructorWithArguments()
    {
        $resolver = new Resolver('foo', 'bar');
        $this->assertSame('foo', $resolver->getDirectory());
        $this->assertSame('bar', $resolver->getExtension());
    }

    public function testSetDirectory()
    {
        $resolver = new Resolver();
        $resolver->setDirectory('foo/bar/baz');
        $this->assertSame('foo/bar/baz', $resolver->getDirectory());
    }

    public function testSetExtension()
    {
        $resolver = new Resolver();
        $resolver->setExtension('bah');
        $this->assertSame('bah', $resolver->getExtension());
    }

    public function testResolve()
    {
        $resolver = new Resolver('templates');
        $this->assertEquals('templates/folder/name.php', $resolver->resolve('folder/name'));
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
        $resolver = new Resolver('templates');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('');
        $resolver->resolve($path);
    }
}
