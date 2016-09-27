<?php

namespace Tests\EngineWorks\Templates;

class Utils
{
    public static function samples($append = '')
    {
        return dirname(dirname(__DIR__)) . '/samples/' . $append;
    }
}
