<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Exception\Handler;

use DobroSite\Specification\CompositeSpecification;
use DobroSite\Specification\Exception\Handler\NoMatchingHandlerException;
use DobroSite\Specification\Handler\Handler;
use DobroSite\Specification\Handler\HandlerRegistry;
use DobroSite\Specification\Specification;
use DobroSite\Specification\Tests\Fixture\Specification\SimpleString;
use PHPUnit\Framework\TestCase;

/**
 * Тесты исключения «Нет подходящего обработчика».
 *
 * @covers \DobroSite\Specification\Exception\Handler\NoMatchingHandlerException
 */
class NoMatchingHandlerExceptionTest extends TestCase
{
    /**
     * Проверяет создание исключения без указания интерфейсов.
     *
     * @throws \Throwable
     */
    public function testWithoutInterfaces(): void
    {
        $spec = new SimpleString('foo');
        $exception = new NoMatchingHandlerException($spec);

        self::assertEquals(
            sprintf('No handlers found for specification %s.', SimpleString::class),
            $exception->getMessage()
        );
    }

    /**
     * Проверяет создание исключения с указанием интерфейсов.
     *
     * @throws \Throwable
     */
    public function testWithInterfaces(): void
    {
        $spec = new SimpleString('foo');
        $exception = new NoMatchingHandlerException(
            $spec,
            [Handler::class, HandlerRegistry::class]
        );

        self::assertEquals(
            sprintf(
                'No handlers with interfaces %s, %s found for specification %s.',
                Handler::class,
                HandlerRegistry::class,
                SimpleString::class
            ),
            $exception->getMessage()
        );
    }
}
