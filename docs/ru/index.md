# Шаблон «Спецификация» для PHP

Сердцем библиотеки является интерфейс [Specification](../../src/Specification.php), который
предоставляет всего один метод — `isSatisfiedBy`, принимающий на входе произвольное значение и
возвращающий true, если это значение удовлетворяет спецификации.

## Фильтрация по спецификации

```php
<?php

/** @var Foo[] $candidates */
/** @var \DobroSite\Specification\Specification $specification */

$filtered = array_map(
    static function (Foo $candidate) use ($specification) {
        return $specification->isSatisfiedBy($candidate);
    },
    $candidates
);
```
