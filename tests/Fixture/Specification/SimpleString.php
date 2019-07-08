<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Fixture\Specification;

use DobroSite\Specification\Specification;

class SimpleString implements Specification
{
    private $string;

    public function __construct(string $foo)
    {
        $this->string = $foo;
    }

    public function isSatisfiedBy($candidate): bool
    {
        return true;
    }

    public function getString(): string
    {
        return $this->string;
    }
}
