<?php

declare(strict_types=1);

namespace DobroSite\Specification\Handler;

use DobroSite\Specification\Exception\Handler\NoMatchingHandlerException;
use DobroSite\Specification\Specification;

/**
 * Реестр обработчиков с основной функциональностью.
 *
 * @since 2.0
 */
class BasicHandlerRegistry implements HandlerRegistry
{
    /**
     * Обработчики спецификаций.
     *
     * @var Handler[][]
     */
    private $handlers = [];

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
        $group = get_class($specification);
        if (!array_key_exists($group, $this->handlers) || count($this->handlers[$group]) === 0) {
            throw new NoMatchingHandlerException($specification);
        }

        // Если нет требований к наличию определённых интерфейсов, возвращаем первый
        // попавшийся обработчик.
        if (count($requiredInterfaces) === 0) {
            return reset($this->handlers[$group]);
        }

        // TODO Здесь могут быть проблемы с производительностью при большом количестве
        //      обработчиков.

        foreach ($this->handlers[$group] as $handler) {
            $actualInterfaces = class_implements($handler, false);
            $implementedInterfaces = array_intersect($requiredInterfaces, $actualInterfaces);
            if (count($implementedInterfaces) === count($requiredInterfaces)) {
                return $handler;
            }
        }

        throw new NoMatchingHandlerException($specification, $requiredInterfaces);
    }

    /**
     * Регистрирует обработчик спецификаций.
     *
     * @param Handler $handler
     *
     * @return void
     *
     * @since 2.0
     */
    public function registerHandler(Handler $handler): void
    {
        $group = $handler->getSpecificationClassName();
        if (!array_key_exists($group, $this->handlers)) {
            $this->handlers[$group] = [];
        }
        $this->handlers[$group][] = $handler;
    }
}
