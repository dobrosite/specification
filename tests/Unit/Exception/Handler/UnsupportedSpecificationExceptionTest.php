<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Exception\Handler;

use DobroSite\Specification\Exception\Handler\UnsupportedSpecificationException;
use DobroSite\Specification\Handler\Handler;
use DobroSite\Specification\Tests\Fixture\Specification\SimpleString;
use PHPUnit\Framework\TestCase;

/**
 * Тесты исключения «Спецификация не поддерживается обработчиком».
 *
 * @covers \DobroSite\Specification\Exception\Handler\UnsupportedSpecificationException
 */
class UnsupportedSpecificationExceptionTest extends TestCase
{
    /**
     * Проверяет правильность создания исключения.
     *
     * @throws \Throwable
     */
    public function testConstructedProperly(): void
    {
        $spec = new SimpleString('foo');

        $handler = $this->createConfiguredMock(
            Handler::class,
            ['getSpecificationClassName' => 'FooSpec']
        );

        $exception = new UnsupportedSpecificationException($spec, $handler);
        $regexp = sprintf(
            '/^Mock_\S+ supports only FooSpec specifications, but %s given\.$/',
            preg_quote(SimpleString::class, '/')
        );
        self::assertMatchesRegularExpression($regexp, $exception->getMessage());
    }
}
