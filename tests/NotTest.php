<?php

declare(strict_types = 1);

namespace DobroSite\Specification\Tests;

use DobroSite\Specification\Not;
use DobroSite\Specification\Specification;
use PHPUnit\Framework\TestCase;

/**
 * Модульные тесты спецификации «НЕ».
 *
 * @covers \DobroSite\Specification\Not
 */
class NotTest extends TestCase
{
    /**
     * Проверяет что спецификация не удовлетворена, если удовлетворена вложенная спецификация.
     */
    public function testNotSatisfiedIfNestedSpecSatisfied(): void
    {
        $candidate = new \stdClass();

        $nestedSpec = $this->createMock(Specification::class);
        $nestedSpec
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($candidate))
            ->willReturn(true);

        $specification = new Not($nestedSpec);

        self::assertFalse($specification->isSatisfiedBy($candidate));
    }

    /**
     * Проверяет что возвращается вложенная спецификация.
     */
    public function testReturnNestedSpec(): void
    {
        $nestedSpec = $this->createMock(Specification::class);

        $specification = new Not($nestedSpec);

        self::assertSame($nestedSpec, $specification->getSpecification());
    }

    /**
     * Проверяет что для спецификация удовлетворена, если не удовлетворена вложенная спецификация.
     */
    public function testSatisfiedIfNestedSpecNotSatisfied(): void
    {
        $candidate = new \stdClass();

        $nestedSpec = $this->createMock(Specification::class);
        $nestedSpec
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($candidate))
            ->willReturn(false);

        $specification = new Not($nestedSpec);

        self::assertTrue($specification->isSatisfiedBy($candidate));
    }
}
