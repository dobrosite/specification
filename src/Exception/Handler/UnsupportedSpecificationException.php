<?php

declare(strict_types=1);

namespace DobroSite\Specification\Exception\Handler;

use DobroSite\Specification\Handler\Handler;
use DobroSite\Specification\Specification;

/**
 * Спецификация не поддерживается обработчиком.
 *
 * @since 2.0
 */
class UnsupportedSpecificationException extends \LogicException
{
    /**
     * Создаёт исключение.
     *
     * @param Specification $specification Обрабатываемая спецификация.
     * @param Handler       $handler       Обработчик, который не смог обработать спецификацию
     *
     * @since 2.0
     */
    public function __construct(Specification $specification, Handler $handler)
    {
        $message = sprintf(
            '%s supports only %s specifications, but %s given.',
            get_class($handler),
            $handler->getSpecificationClassName(),
            get_class($specification)
        );

        parent::__construct($message);
    }
}
