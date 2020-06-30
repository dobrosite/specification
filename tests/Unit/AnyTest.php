<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit;

use DobroSite\Specification\Any;
use PHPUnit\Framework\TestCase;

/**
 * Модульные тесты спецификации «Любой».
 *
 * @covers \DobroSite\Specification\Any
 */
class AnyTest extends TestCase
{
    /**
     * Проверяет что для спецификация всегда удовлетворена.
     */
    public function testSatisfied(): void
    {
        $specification = new Any();

        self::assertTrue($specification->isSatisfiedBy(new \stdClass()));
    }
}
