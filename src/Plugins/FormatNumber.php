<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Plugins;

use EngineWorks\Templates\Plugin;
use InvalidArgumentException;

class FormatNumber implements Plugin
{
    /**
     * @return array{fn: string}
     */
    public function getCallablesTable(): array
    {
        return [
            'fn' => 'format',
        ];
    }

    /** @var int */
    private $defaultDecimals;

    public function __construct(int $defaultDecimals = 2)
    {
        $this->setDefaultDecimals($defaultDecimals);
    }

    /**
     * Format a number, if the expression is not a number, then uses 0
     *
     * @param mixed $number
     * @param int $decimals = $this->defaultDecimals()
     */
    public function format($number, int $decimals = -1): string
    {
        if ($decimals < 0) {
            $decimals = $this->getDefaultDecimals();
        }
        return number_format(is_numeric($number) ? (float) $number : 0, $decimals);
    }

    public function getDefaultDecimals(): int
    {
        return $this->defaultDecimals;
    }

    public function setDefaultDecimals(int $defaultDecimals): void
    {
        if ($defaultDecimals < 0) {
            throw new InvalidArgumentException('The default decimals argument is not an integer greater than zero');
        }
        $this->defaultDecimals = $defaultDecimals;
    }
}
