<?php
namespace EngineWorks\Templates\Plugins;

use EngineWorks\Templates\Plugin;

class FormatNumber implements Plugin
{
    public function getCallablesTable()
    {
        return [
            'fn' => 'format',
        ];
    }

    /** @var int */
    private $defaultDecimals;

    public function __construct($defaultDecimals = 2)
    {
        $this->setDefaultDecimals($defaultDecimals);
    }

    /**
     * Format a number, if the expression is not a number then uses 0
     *
     * @param int|float $number
     * @param int $decimals = $this->defaultDecimals()
     * @return string
     */
    public function format($number, $decimals = -1)
    {
        if ($decimals < 0) {
            $decimals = $this->getDefaultDecimals();
        }
        return number_format(is_numeric($number) ? $number : 0, $decimals);
    }

    /**
     * @return int
     */
    public function getDefaultDecimals()
    {
        return $this->defaultDecimals;
    }

    /**
     * @param int $defaultDecimals
     */
    public function setDefaultDecimals($defaultDecimals)
    {
        if (! is_int($defaultDecimals) || $defaultDecimals < 0) {
            throw new \InvalidArgumentException('The default decimals argument is not an integer greater than zero');
        }
        $this->defaultDecimals = $defaultDecimals;
    }
}
