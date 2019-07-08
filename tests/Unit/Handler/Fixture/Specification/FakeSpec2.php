<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Handler\Fixture\Specification;

use DobroSite\Specification\Specification;

class FakeSpec2 implements Specification
{
    public function isSatisfiedBy($candidate): bool
    {
        return false;
    }
}
