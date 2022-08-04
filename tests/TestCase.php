<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function samplePath(string $append = ''): string
    {
        return __DIR__ . '/_files/samples/' . $append;
    }
}
