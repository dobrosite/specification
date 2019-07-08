<?php

declare(strict_types=1);

namespace DobroSite\Specification\Handler;

use DobroSite\Specification\Exception\Handler\NoMatchingHandlerException;
use DobroSite\Specification\Specification;

/**
 * Реестр обработчиков спецификаций.
 *
 * Предоставляет централизованный доступ ко всем доступным обработчикам.
 *
 * @since 2.0
 */
interface HandlerRegistry
{
    /**
     * Возвращает обработчик для указанной спецификации.
     *
     * @param Specification $specification      Спецификация, для которой запрашивает разработчик.
     * @param string[]      $requiredInterfaces Список дополнительных интерфейсов, которые должен
     *                                          поддерживать обработчик.
     *
     * @return Handler
     *
     * @throws NoMatchingHandlerException Если нет обработчика для этой спецификации.
     *
     * @since 2.0
     */
    public function getHandlerFor(
        Specification $specification,
        array $requiredInterfaces = []
    ): Handler;
}
