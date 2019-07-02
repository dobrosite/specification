<?php

declare(strict_types=1);

namespace DobroSite\Specification;

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
 * @since 1.0
 */
final class AllOf implements Specification
{
    /**
     * Вложенные спецификации.
     *
     * @var Specification[]
     */
    private $specifications;

    /**
     * Создаёт спецификацию.
     *
     * @param Specification ...$specifications Вложенные спецификации.
     *
     * @since 1.0
     */
    public function __construct(...$specifications)
    {
        if (count($specifications) < 2) {
            throw new \LogicException(
                sprintf(
                    '%s required at least 2 specifications, but %d given.',
                    __METHOD__,
                    count($specifications)
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
