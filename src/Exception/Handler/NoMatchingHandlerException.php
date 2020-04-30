<?php

declare(strict_types=1);

namespace DobroSite\Specification\Exception\Handler;

use DobroSite\Specification\Specification;

/**
 * Нет подходящего обработчика.
 *
 * @since 2.0
 */
class NoMatchingHandlerException extends \LogicException
{
    /**
     * Создаёт исключение.
     *
     * @param Specification      $specification      Обрабатываемая спецификация.
     * @param array<int, string> $requiredInterfaces Список запрошенных интерфейсов.
     *
     * @since 2.0
     */
    public function __construct(Specification $specification, array $requiredInterfaces = [])
    {
        $message = sprintf(
            'No handlers%s found for specification %s.',
            count($requiredInterfaces) > 0
                ? ' with interfaces ' . implode(', ', $requiredInterfaces)
                : '',
            get_class($specification)
        );

        parent::__construct($message);
    }
}
