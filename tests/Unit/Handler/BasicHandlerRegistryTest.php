<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Handler;

use DobroSite\Specification\Exception\Handler\NoMatchingHandlerException;
use DobroSite\Specification\Exception\Handler\UnsupportedSpecificationException;
use DobroSite\Specification\Handler\BasicHandlerRegistry;
use DobroSite\Specification\Handler\Handler;
use DobroSite\Specification\Tests\Unit\Handler\Fixture\Handler\FakeSpec1GenericHandler;
use DobroSite\Specification\Tests\Unit\Handler\Fixture\Handler\FakeSpec2ExtendedHandler;
use DobroSite\Specification\Tests\Unit\Handler\Fixture\Handler\FakeSpec2GenericHandler;
use DobroSite\Specification\Tests\Unit\Handler\Fixture\Specification\FakeSpec1;
use PHPUnit\Framework\TestCase;

/**
 * Тесты основного реестра обработчиков.
 *
 * @covers \DobroSite\Specification\Handler\BasicHandlerRegistry
 */
class BasicHandlerRegistryTest extends TestCase
{
    /**
     * Проверяет успешное получение обработчика.
     *
     * @throws \Exception
     */
    public function testReturnsGenericHandler(): void
    {
        $handler1 = new FakeSpec1GenericHandler();
        $handler2 = new FakeSpec2GenericHandler();
        $spec = new FakeSpec1();

        $registry = new BasicHandlerRegistry();
        $registry->registerHandler($handler1);
        $registry->registerHandler($handler2);

        $handler = $registry->getHandlerFor($spec);

        self::assertSame($handler1, $handler);
    }

    /**
     * Проверяет успешное получение обработчика с требуемыми интерфейсами.
     *
     * @throws \Exception
     */
    public function testReturnsHandlerWithInterfaces(): void
    {
        $handler1 = new FakeSpec1GenericHandler();
        $handler2 = new FakeSpec2GenericHandler();
        $handler3 = new FakeSpec2ExtendedHandler();
        $spec = new FakeSpec1();

        $registry = new BasicHandlerRegistry();
        $registry->registerHandler($handler1);
        $registry->registerHandler($handler2);
        $registry->registerHandler($handler3);

        $handler = $registry->getHandlerFor($spec, [\Countable::class]);

        self::assertSame($handler3, $handler);
    }

    /**
     * Проверяет вбрасывание исключения при отсутствии обработчиков конкретной спецификации.
     *
     * @throws \Exception
     */
    public function testThrowsExceptionIfNoHandlerAvailableForSpecification(): void
    {
        $handler1 = $this->createConfiguredMock(
            Handler::class,
            ['getSpecificationClassName' => 'FakeSpec_001']
        );
        $handler2 = $this->createConfiguredMock(
            Handler::class,
            ['getSpecificationClassName' => 'FakeSpec_002']
        );
        $spec = new FakeSpec1();

        $registry = new BasicHandlerRegistry();
        $registry->registerHandler($handler1);
        $registry->registerHandler($handler2);

        $this->expectException(NoMatchingHandlerException::class);
        $this->expectExceptionMessage(
            sprintf('No handlers found for specification %s.', FakeSpec1::class)
        );

        $registry->getHandlerFor($spec);
    }


    /**
     * Проверяет вбрасывание исключения при отсутствии обработчиков с нужными интерфейсами.
     *
     * @throws \Exception
     */
    public function testThrowsExceptionIfNoHandlerWithRequiredInterfaces(): void
    {
        $handler1 = new FakeSpec1GenericHandler();
        $handler2 = new FakeSpec2GenericHandler();

        $spec = new FakeSpec1();

        $registry = new BasicHandlerRegistry();
        $registry->registerHandler($handler1);
        $registry->registerHandler($handler2);

        $this->expectException(NoMatchingHandlerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'No handlers with interfaces Countable, ArrayAccess found for specification %s.',
                FakeSpec1::class
            )
        );

        $registry->getHandlerFor($spec, [\Countable::class, \ArrayAccess::class]);
    }
}
