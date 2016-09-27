<?php

/* @var $templates \EngineWorks\Templates\Templates */
/* @var $value int */

echo "$value\n";
$maxvalue = 10;
if ($value < $maxvalue) {
    echo $templates->fetch('recursive', [
        'templates' => $templates,
        'value' => $value + 1
    ]);
}
