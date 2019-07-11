<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Logical;

use DobroSite\Specification\Logical\AllOf;
use DobroSite\Specification\Specification;
use PHPUnit\Framework\TestCase;

/**
 * Модульные тесты спецификации «И» («все»).
 *
 * @covers \DobroSite\Specification\Logical\AllOf
 */
class AllOfTest extends TestCase
{
    /**
     * Проверяет что конструктор принимает только объекты.
     *
     * @throws \Exception
     */
    public function testConstructorAcceptOnlyObjects(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Argument "specifications" should be an array of %s, but element #1 is integer.',
                Specification::class
            )
        );

        new AllOf($this->createMock(Specification::class), 123);
    }

    /**
     * Проверяет что конструктор принимает только объекты с интерфейсом Specification.
     */
    public function testConstructorAcceptOnlyObjectsWithSpecification(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Argument "specifications" should be an array of %s, but element #1 is stdClass.',
                Specification::class
            )
        );

        new AllOf($this->createMock(Specification::class), new \stdClass());
    }

    /**
     * Проверяет что спецификация не удовлетворена, если не удовлетворена хотя бы одна вложенная
     * спецификация.
     */
    public function testNotSatisfiedIfAtLeastOneNestedSpecNotSatisfied(): void
    {
        $candidate = new \stdClass();

        $nestedSpec1 = $this->createMock(Specification::class);
        $nestedSpec1
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($candidate))
            ->willReturn(true);

        $nestedSpec2 = $this->createMock(Specification::class);
        $nestedSpec2
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(static::equalTo($candidate))
            ->willReturn(false);

        $specification = new AllOf($nestedSpec1, $nestedSpec2);

        self::assertFalse($specification->isSatisfiedBy($candidate));
    }

    /**
     * Проверяет что возвращаются все вложенные спецификации.
     */
    public function testReturnAllNestedSpecs(): void
    {
        $nestedSpec1 = $this->createMock(Specification::class);
        $nestedSpec2 = $this->createMock(Specification::class);

        $specification = new AllOf($nestedSpec1, $nestedSpec2);

        self::assertSame([$nestedSpec1, $nestedSpec2], $specification->getSpecifications());
    }

    /**
     * Проверяет что для спецификация удовлетворена, если удовлетворены все вложенные спецификации.
     */
    public function testSatisfiedIfAllNestedSpecsSatisfied(): void
    {
        $candidate = new \stdClass();

        $nestedSpec1 = $this->createMock(Specification::class);
        $nestedSpec1
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($candidate))
            ->willReturn(true);

        $nestedSpec2 = $this->createMock(Specification::class);
        $nestedSpec2
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($candidate))
            ->willReturn(true);

        $specification = new AllOf($nestedSpec1, $nestedSpec2);

        self::assertTrue($specification->isSatisfiedBy($candidate));
    }
}
