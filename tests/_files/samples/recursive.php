<?php

declare(strict_types=1);

/**
 * @var $this \EngineWorks\Templates\Template
 * @var $value int
 */
echo "$value\n";

if ($value < 10) {
    echo $this->fetch('recursive', [
        'value' => $value + 1,
    ]);
}
