<?php

declare(strict_types=1);

namespace DobroSite\Specification;

/**
 * Спецификация.
 *
 * @link  https://designpatternsphp.readthedocs.io/ru/latest/Behavioral/Specification/README.html
 * @since 1.0
 */
interface Specification
{
    /**
     * Возвращает true, если переданный кандидат удовлетворяет спецификации.
     *
     * @param mixed $candidate
     *
     * @return bool
     *
     * @since 1.0
     */
    public function isSatisfiedBy($candidate): bool;
}
