# Specification pattern for PHP

## Filtering by specification

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
