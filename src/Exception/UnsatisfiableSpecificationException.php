<?php

declare(strict_types=1);

namespace DobroSite\Specification\Exception;

/**
 * Невыполнимая спецификация.
 *
 * Пример: A AND !A
 */
class UnsatisfiableSpecificationException extends \LogicException
{
}
