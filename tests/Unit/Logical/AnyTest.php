<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Logical;

use DobroSite\Specification\Logical\AnyOf;
use DobroSite\Specification\Specification;
use PHPUnit\Framework\TestCase;

/**
 * Модульные тесты спецификации «ИЛИ» («любой»).
 *
 * @covers \DobroSite\Specification\Logical\AnyOf
 */
class AnyTest extends TestCase
{
    /**
     * Проверяет что конструктор принимает только объекты.
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

        new AnyOf($this->createMock(Specification::class), 123);
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

        new AnyOf($this->createMock(Specification::class), new \stdClass());
    }

    /**
     * Проверяет что спецификация не удовлетворена, если не удовлетворена ни одна вложенная
     * спецификация.
     */
    public function testNotSatisfiedIfNoNestedSpecSatisfied(): void
    {
        $entity = new \stdClass();

        $nestedSpec1 = $this->createMock(Specification::class);
        $nestedSpec1
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($entity))
            ->willReturn(false);

        $nestedSpec2 = $this->createMock(Specification::class);
        $nestedSpec2
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($entity))
            ->willReturn(false);

        $specification = new AnyOf($nestedSpec1, $nestedSpec2);

        self::assertFalse($specification->isSatisfiedBy($entity));
    }

    /**
     * Проверяет что возвращаются все вложенные спецификации.
     */
    public function testReturnAllNestedSpecs(): void
    {
        $nestedSpec1 = $this->createMock(Specification::class);
        $nestedSpec2 = $this->createMock(Specification::class);

        $specification = new AnyOf($nestedSpec1, $nestedSpec2);

        self::assertSame([$nestedSpec1, $nestedSpec2], $specification->getSpecifications());
    }

    /**
     * Проверяет что для спецификация удовлетворена, если удовлетворена хотя бы одна вложенная
     * спецификация.
     */
    public function testSatisfiedIfAtLeastOnceNestedSpecSatisfied(): void
    {
        $entity = new \stdClass();

        $nestedSpec1 = $this->createMock(Specification::class);
        $nestedSpec1
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($entity))
            ->willReturn(false);

        $nestedSpec2 = $this->createMock(Specification::class);
        $nestedSpec2
            ->expects(self::atLeastOnce())
            ->method('isSatisfiedBy')
            ->with(self::equalTo($entity))
            ->willReturn(true);

        $specification = new AnyOf($nestedSpec1, $nestedSpec2);

        self::assertTrue($specification->isSatisfiedBy($entity));
    }
}
