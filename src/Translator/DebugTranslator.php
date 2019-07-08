<?php

declare(strict_types=1);

namespace DobroSite\Specification\Translator;

use DobroSite\Specification\CompositeSpecification;
use DobroSite\Specification\Specification;

/**
 * Транслятор спецификаций для отладки.
 *
 * @since 2.0
 */
final class DebugTranslator implements Translator
{
    /**
     * Начальная часть пространства имён, которую следует удалять из имени спецификации.
     */
    private const NS_STRIP_PREFIX = 'DobroSite\\Specification\\';

    /**
     * Строит выражение на основе спецификации.
     *
     * @param Specification $specification
     *
     * @return string
     *
     * @since 2.0
     */
    public function translate(Specification $specification): string
    {
        if ($specification instanceof CompositeSpecification) {
            return $this->translateCompositeSpecification($specification);
        }

        return $this->translatePrimitiveSpecification($specification);
    }

    /**
     * Составляет представление операнда.
     *
     * @param string $name  Имя операнда.
     * @param mixed  $value Значение.
     *
     * @return string
     */
    private function composeOperand(string $name, $value): string
    {
        return sprintf('%s: %s', substr($name, 3), $value);
    }

    /**
     * Возвращает имена методов-геттеров.
     *
     * @param Specification $specification
     *
     * @return \Generator
     */
    private function getGetters(Specification $specification): \Generator
    {
        $class = new \ReflectionObject($specification);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if (stripos($method->getName(), 'get') === 0) {
                yield $method->getName();
            }
        }
    }

    /**
     * Возвращает имя спецификации.
     *
     * @param Specification $specification
     *
     * @return string
     */
    private function getSpecificationName(Specification $specification): string
    {
        $name = get_class($specification);
        if (stripos($name, self::NS_STRIP_PREFIX) === 0) {
            $name = substr($name, strlen(self::NS_STRIP_PREFIX));
        }

        return $name;
    }

    /**
     * Преобразовывает составную спецификацию.
     *
     * @param CompositeSpecification $specification
     *
     * @return string
     *
     * @since 2.0
     */
    private function translateCompositeSpecification(CompositeSpecification $specification): string
    {
        $innerSpecifications = $specification->getSpecifications();
        $innerExpressions = [];
        foreach ($innerSpecifications as $innerSpecification) {
            $innerExpressions[] = $this->translate($innerSpecification);
        }

        return $this->getSpecificationName($specification)
            . '(' . implode(', ', $innerExpressions) . ')';
    }

    /**
     * Преобразует простую (не составную) спецификацию.
     *
     * @param Specification $specification
     *
     * @return string
     */
    private function translatePrimitiveSpecification(Specification $specification): string
    {
        $getters = $this->getGetters($specification);
        $operands = [];
        foreach ($getters as $method) {
            $operands[] = $this->composeOperand($method, $specification->$method());
        }

        return $this->getSpecificationName($specification) . '[' . implode(', ', $operands) . ']';
    }
}
