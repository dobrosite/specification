<?php

declare(strict_types=1);

namespace DobroSite\Specification\Tests\Unit\Translator;

use DobroSite\Specification\Logical\AllOf;
use DobroSite\Specification\Logical\AnyOf;
use DobroSite\Specification\Logical\Not;
use DobroSite\Specification\Tests\Fixture\Specification\SimpleString;
use DobroSite\Specification\Translator\DebugTranslator;
use PHPUnit\Framework\TestCase;

/**
 * Тесты отладочного транслятора.
 *
 * @covers \DobroSite\Specification\Translator\DebugTranslator
 */
class DebugTranslatorTest extends TestCase
{
    /**
     * Проверяемый транслятор.
     *
     * @var DebugTranslator
     */
    private $translator;

    /**
     * Проверяет составную спецификацию.
     *
     * @throws \Exception
     */
    public function testComposite(): void
    {
        $specification = new AllOf(new SimpleString('foo'), new SimpleString('bar'));

        self::assertEquals(
            sprintf(
                'Logical\AllOf(%1$s[String: foo], %1$s[String: bar])',
                'Tests\\Fixture\\Specification\\SimpleString'
            ),
            $this->translator->translate($specification)
        );
    }

    /**
     * Проверяет многоуровневую составную спецификацию.
     *
     * @throws \Exception
     */
    public function testMultiLevelComposite(): void
    {
        $specification = new AllOf(
            new SimpleString('foo'),
            new AnyOf(
                new SimpleString('bar'),
                new Not(new SimpleString('baz'))
            )
        );

        self::assertEquals(
            sprintf(
                'Logical\AllOf(%1$s[String: foo], Logical\AnyOf(%1$s[String: bar], Logical\Not(%1$s[String: baz])))',
                'Tests\\Fixture\\Specification\\SimpleString'
            ),
            $this->translator->translate($specification)
        );
    }

    /**
     * Проверяет простую спецификацию.
     *
     * @throws \Exception
     */
    public function testPrimitive(): void
    {
        $specification = new SimpleString('foo');

        self::assertEquals(
            'Tests\\Fixture\\Specification\\SimpleString[String: foo]',
            $this->translator->translate($specification)
        );
    }

    /**
     * Готовит окружение теста.
     *
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->translator = new DebugTranslator();
    }
}
