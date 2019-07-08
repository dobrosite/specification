<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Handler\Fixture\Handler;

use DobroSite\Specification\Handler\Handler;
use DobroSite\Specification\Tests\Unit\Handler\Fixture\Specification\FakeSpec1;

class FakeSpec2ExtendedHandler implements Handler, \Countable
{
    public function count()
    {
    }

    public function getSpecificationClassName(): string
    {
        return FakeSpec1::class;
    }
}
