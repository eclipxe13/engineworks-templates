<?php
namespace EngineWorks\Templates\Tests\Plugins;

use EngineWorks\Templates\Plugins\FormatNumber;
use PHPUnit\Framework\TestCase;

class FormatNumberTest extends TestCase
{
    public function testConstructor()
    {
        $formatNumber = new FormatNumber();
        $this->assertSame(2, $formatNumber->getDefaultDecimals());
    }

    public function testCallablesTable()
    {
        $expectedTableNames = ['fn'];
        $formatNumber = new FormatNumber();
        $table = $formatNumber->getCallablesTable();
        foreach ($expectedTableNames as $name) {
            $this->assertArrayHasKey($name, $table, "The Callables table does not contain '$name'");
        }
    }

    public function testConstructorWithDecimals()
    {
        $formatNumber = new FormatNumber(4);
        $this->assertSame(4, $formatNumber->getDefaultDecimals());
    }

    public function testSetDefaultDecimals()
    {
        $formatNumber = new FormatNumber();
        $formatNumber->setDefaultDecimals(4);
        $this->assertSame(4, $formatNumber->getDefaultDecimals());
    }

    public function providerSetDefaultDecimalsThrowsInvalidArgumentException()
    {
        return [
            'null' => [null],
            'empty' => [''],
            'not integer' => ['0'],
            'negative integer' => ['-5'],
            'float' => [1.0],
        ];
    }

    /**
     * @param mixed $value
     * @dataProvider providerSetDefaultDecimalsThrowsInvalidArgumentException
     */
    public function testSetDefaultDecimalsThrowsInvalidArgumentException($value)
    {
        $formatNumber = new FormatNumber();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The default decimals argument is not an integer greater than zero');

        $formatNumber->setDefaultDecimals($value);
    }

    public function testFormat()
    {
        $formatNumber = new FormatNumber(2);
        $number = 12345.678;
        $expectedTwoDecimals = '12,345.68';
        $expectedFourDecimals = '12,345.6780';
        $this->assertSame($expectedTwoDecimals, $formatNumber->format($number));
        $this->assertSame($expectedFourDecimals, $formatNumber->format($number, 4));
    }
}
