<?php

declare(strict_types=1);

namespace DobroSite\Specification\Logical;

use DobroSite\Specification\CompositeSpecification;
use DobroSite\Specification\Specification;

/**
 * Спецификация «НЕ».
 *
 * @since 2.0 находится в пространстве Logical.
 * @since 1.0
 */
final class Not implements CompositeSpecification
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
     * @return Specification[]
     *
     * @since 2.0
     */
    public function getSpecifications(): array
    {
        return [$this->specification];
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
