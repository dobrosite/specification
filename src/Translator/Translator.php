<?php

declare(strict_types=1);

namespace DobroSite\Specification\Translator;

use DobroSite\Specification\Exception\Handler\NoMatchingHandlerException;
use DobroSite\Specification\Exception\UnsatisfiableSpecificationException;
use DobroSite\Specification\Specification;

/**
 * Транслятор спецификаций в выражения.
 *
 * @since 2.0
 */
interface Translator
{
    /**
     * Строит выражение на основе спецификации.
     *
     * @param Specification $specification
     *
     * @return string
     *
     * @throws NoMatchingHandlerException
     * @throws UnsatisfiableSpecificationException
     *
     * @since x.x Может выбрасывать исключение NoMatchingHandlerException.
     * @since 2.0
     */
    public function translate(Specification $specification): string;
}
