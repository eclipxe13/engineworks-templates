<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests\Plugins;

use EngineWorks\Templates\Plugins\FormatNumber;
use EngineWorks\Templates\Tests\TestCase;
use InvalidArgumentException;

final class FormatNumberTest extends TestCase
{
    public function testConstructor(): void
    {
        $formatNumber = new FormatNumber();
        $this->assertSame(2, $formatNumber->getDefaultDecimals());
    }

    public function testCallablesTable(): void
    {
        $expectedTableNames = ['fn'];
        $formatNumber = new FormatNumber();
        $table = $formatNumber->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }

    public function testConstructorWithDecimals(): void
    {
        $formatNumber = new FormatNumber(4);
        $this->assertSame(4, $formatNumber->getDefaultDecimals());
    }

    public function testSetDefaultDecimals(): void
    {
        $formatNumber = new FormatNumber();
        $formatNumber->setDefaultDecimals(4);
        $this->assertSame(4, $formatNumber->getDefaultDecimals());
    }

    public function testSetDefaultDecimalsThrowsInvalidArgumentException(): void
    {
        $formatNumber = new FormatNumber();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The default decimals argument is not an integer greater than zero');

        $formatNumber->setDefaultDecimals(-5);
    }

    public function testFormat(): void
    {
        $formatNumber = new FormatNumber(2);
        $number = 12345.678;
        $expectedTwoDecimals = '12,345.68';
        $expectedFourDecimals = '12,345.6780';
        $this->assertSame($expectedTwoDecimals, $formatNumber->format($number));
        $this->assertSame($expectedFourDecimals, $formatNumber->format($number, 4));
    }
}
