<?php

declare(strict_types=1);

namespace DobroSite\Specification;

/**
 * Спецификация «НЕ».
 *
 * @since 1.0
 */
final class Not implements Specification
{
    /**
     * Отрицаемая спецификация.
     *
     * @var Specification
     */
    private $specification;

    /**
     * Создаёт спецификацию.
     *
     * @param Specification $specification
     *
     * @since 1.0
     */
    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    /**
     * Возвращает отрицаемую спецификацию.
     *
     * @return Specification
     *
     * @since 1.0
     */
    public function getSpecification(): Specification
    {
        return $this->specification;
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
        return !$this->specification->isSatisfiedBy($candidate);
    }
}
