<?php

declare(strict_types=1);

namespace DobroSite\Specification\Handler;

/**
 * Обработчик спецификаций.
 *
 * @since 2.0
 */
interface Handler
{
    /**
     * Возвращает имя класса поддерживаемых спецификаций.
     *
     * @return string
     *
     * @since 2.0
     */
    public function getSpecificationClassName(): string;
}
