<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests;

use EngineWorks\Templates\Resolver;

final class ResolverTest extends TestCase
{
    public function testConstructorWithDefaultValues(): void
    {
        $resolver = new Resolver();
        $this->assertSame('', $resolver->getDirectory());
        $this->assertSame('php', $resolver->getExtension());
    }

    public function testConstructorWithArguments(): void
    {
        $resolver = new Resolver('foo', 'bar');
        $this->assertSame('foo', $resolver->getDirectory());
        $this->assertSame('bar', $resolver->getExtension());
    }

    public function testSetDirectory(): void
    {
        $resolver = new Resolver();
        $resolver->setDirectory('foo/bar/baz');
        $this->assertSame('foo/bar/baz', $resolver->getDirectory());
    }

    public function testSetExtension(): void
    {
        $resolver = new Resolver();
        $resolver->setExtension('bah');
        $this->assertSame('bah', $resolver->getExtension());
    }

    public function testResolve(): void
    {
        $resolver = new Resolver('templates');
        $this->assertEquals('templates/folder/name.php', $resolver->resolve('folder/name'));
    }

    /**
     * @return array<int, array<string>>
     */
    public function providerFilenameThrowsException(): array
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
    public function testFilenameThrowsException(string $path): void
    {
        $resolver = new Resolver('templates');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The filename try to escape the current path');
        $resolver->resolve($path);
    }
}
