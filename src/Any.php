<?php

declare(strict_types=1);

namespace DobroSite\Specification;

/**
 * Спецификация «Любой».
 *
 * Любой кандидат будет удовлетворять этой спецификации. Может быть полезно использовать в методах,
 * которые должны возвращать экземпляр {@see Specification}, но при этом могут и не предъявлять
 * никаких требований к кандидату.
 *
 * Пример:
 *
 * ```
 * $complexSpecification = new AllOf(
 *   $specification1,
 *   $foo->createSpecification($bar, $baz)
 * );
 * ```
 *
 * Здесь метод `createSpecification` может вернуть экземпляр `Any`, если он не хочет добавлять
 * никаких условий.
 *
 * @since 2.4
 */
class Any implements Specification
{
    /**
     * Всегда возвращает true.
     *
     * @param mixed $candidate
     *
     * @return bool
     */
    public function isSatisfiedBy($candidate): bool
    {
        return true;
    }
}
