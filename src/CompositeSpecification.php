<?php

declare(strict_types=1);

namespace DobroSite\Specification;

/**
 * Составная спецификация.
 *
 * @since 2.0
 */
interface CompositeSpecification extends Specification
{
    /**
     * Возвращает вложенные спецификации.
     *
     * @return Specification[]
     *
     * @since 2.0
     */
    public function getSpecifications(): array;
}
