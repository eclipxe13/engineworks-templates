<?php

declare(strict_types=1);

namespace EngineWorks\Templates;

interface Plugin
{
    /**
     * Return an array of key value where the key is the name of the function
     * and the value is a string with the method name
     *
     * @return array<string, string>
     */
    public function getCallablesTable(): array;
}
