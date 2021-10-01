<?php

declare(strict_types=1);

namespace DobroSite\Specification\Logical;

use DobroSite\Specification\CompositeSpecification;
use DobroSite\Specification\Specification;

/**
 * Спецификация «И» («все»).
 *
 * Требует одновременного соответствия сущности всем вложенным спецификациям.
 *
 * Пример:
 *
 * ```
 * new AllOf($spec1, $spec2, $spec3, ...)
 * ```
 *
 * @since 2.2 Класс больше не является окончательным.
 * @since 2.0 Перемещено в пространство Logical.
 * @since 1.0
 */
class AllOf implements CompositeSpecification
{
    /**
     * Вложенные спецификации.
     *
     * @var Specification[]
     */
    private array $specifications;

    /**
     * Создаёт спецификацию.
     *
     * @param Specification ...$specifications Вложенные спецификации.
     *
     * @since 2.3 Может принимать одну спецификацию.
     * @since 1.0
     */
    public function __construct(...$specifications)
    {
        if (count($specifications) < 1) {
            throw new \LogicException(
                sprintf(
                    '%s requires at least one specification.',
                    __METHOD__
                )
            );
        }

        array_walk(
            $specifications,
            static function ($element, $index) {
                if ($element instanceof Specification) {
                    return;
                }

                throw new \InvalidArgumentException(
                    sprintf(
                        'Argument "specifications" should be an array of %s, but element #%d is %s.',
                        Specification::class,
                        $index,
                        is_object($element) ? get_class($element) : gettype($element)
                    )
                );
            }
        );

        $this->specifications = $specifications;
    }

    /**
     * Возвращает вложенные спецификации.
     *
     * @return Specification[]
     *
     * @since 1.0
     */
    public function getSpecifications(): array
    {
        return $this->specifications;
    }

    /**
     * Возвращает true, если переданный кандидат удовлетворяет спецификации.
     *
     * @param mixed $candidate
     *
     * @return bool
     *
     * @since 1.0
     */
    public function isSatisfiedBy($candidate): bool
    {
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfiedBy($candidate)) {
                return false;
            }
        }

        return true;
    }
}
