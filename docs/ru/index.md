# Шаблон «Спецификация» для PHP

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
