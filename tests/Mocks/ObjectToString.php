<?php

declare(strict_types=1);

namespace EngineWorks\Templates\Tests\Mocks;

final class ObjectToString
{
    /** @var string */
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
