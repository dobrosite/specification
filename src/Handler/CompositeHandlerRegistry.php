<?php

declare(strict_types=1);

namespace DobroSite\Specification\Handler;

use DobroSite\Specification\Exception\Handler\NoMatchingHandlerException;
use DobroSite\Specification\Specification;

/**
 * Составной реестр обработчиков.
 *
 * Позволяет работать с несколькими реестрами как с одним.
 *
 * @since 2.0
 */
class CompositeHandlerRegistry implements HandlerRegistry
{
    /**
     * Вложенные реестры.
     *
     * @var HandlerRegistry[]
     */
    private array $registries = [];

    /**
     * Добавляет новый реестр.
     *
     * @param HandlerRegistry $registry
     *
     * @return void
     *
     * @since 2.0
     */
    public function addRegistry(HandlerRegistry $registry): void
    {
        $this->registries[] = $registry;
    }

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
    ): Handler {
        foreach ($this->registries as $registry) {
            try {
                $handler = $registry->getHandlerFor($specification, $requiredInterfaces);
            } catch (NoMatchingHandlerException $exception) {
                // Ищем в следующем реестре.
                continue;
            }

            return $handler;
        }

        throw new NoMatchingHandlerException($specification, $requiredInterfaces);
    }
}
