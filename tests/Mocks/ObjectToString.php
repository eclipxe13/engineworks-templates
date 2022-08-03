<?php
namespace EngineWorks\Templates\Tests\Mocks;

class ObjectToString
{
    /** @var  string */
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function __toString()
    {
        return $this->content;
    }
}
