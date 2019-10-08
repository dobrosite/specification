<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Handler;

use DobroSite\Specification\Exception\Handler\NoMatchingHandlerException;
use DobroSite\Specification\Handler\CompositeHandlerRegistry;
use DobroSite\Specification\Handler\Handler;
use DobroSite\Specification\Handler\HandlerRegistry;
use DobroSite\Specification\Specification;
use PHPUnit\Framework\TestCase;

/**
 * Тесты составного реестра обработчиков.
 *
 * @covers \DobroSite\Specification\Handler\CompositeHandlerRegistry
 */
class CompositeHandlerRegistryTest extends TestCase
{
    /**
     * Проверяет нахождение подходящего обработчика.
     *
     * @throws \Throwable
     */
    public function testMatchingHandlerFound(): void
    {
        $spec = $this->createMock(Specification::class);
        $expectedHandler = $this->createMock(Handler::class);

        $registry = new CompositeHandlerRegistry();

        $innerRegistry1 = $this->createMock(HandlerRegistry::class);
        $registry->addRegistry($innerRegistry1);
        $innerRegistry1
            ->expects(self::once())
            ->method('getHandlerFor')
            ->with(self::identicalTo($spec), self::equalTo([Handler::class]))
            ->willThrowException(new NoMatchingHandlerException($spec));

        $innerRegistry2 = $this->createMock(HandlerRegistry::class);
        $registry->addRegistry($innerRegistry2);
        $innerRegistry2
            ->expects(self::once())
            ->method('getHandlerFor')
            ->with(self::identicalTo($spec), self::equalTo([Handler::class]))
            ->willReturn($expectedHandler);

        $handler = $registry->getHandlerFor($spec, [Handler::class]);

        self::assertSame($expectedHandler, $handler);
    }

    /**
     * Проверяет отсутствие подходящего обработчика.
     *
     * @throws \Throwable
     */
    public function testMatchingHandlerNotFound(): void
    {
        $spec = $this->createMock(Specification::class);

        $registry = new CompositeHandlerRegistry();

        $innerRegistry1 = $this->createMock(HandlerRegistry::class);
        $registry->addRegistry($innerRegistry1);
        $innerRegistry1
            ->expects(self::once())
            ->method('getHandlerFor')
            ->with(self::identicalTo($spec), self::equalTo([Handler::class]))
            ->willThrowException(new NoMatchingHandlerException($spec));

        $innerRegistry2 = $this->createMock(HandlerRegistry::class);
        $registry->addRegistry($innerRegistry2);
        $innerRegistry2
            ->expects(self::once())
            ->method('getHandlerFor')
            ->with(self::identicalTo($spec), self::equalTo([Handler::class]))
            ->willThrowException(new NoMatchingHandlerException($spec));

        $this->expectException(NoMatchingHandlerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'No handlers with interfaces %s found for specification Mock_Specification_',
                Handler::class
            )
        );

        $registry->getHandlerFor($spec, [Handler::class]);
    }
}
