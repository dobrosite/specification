# Прямое использование спецификаций

Прямое использование спецификаций — это самый простой, хотя и нечасто применяемый способ их
использования.

## Проверка значения

Примеры этого способа вы уже видели в разделе [Написание спецификаций](specifications.md). Он
сводится к передаче кандидата в метод `isSatisfiedBy`:

```php
<?php
use Domain\Entity\Commodity;
use Domain\Entity\Specification\Commodity as CommoditySpecs;

$spec = new CommoditySpecs/Published();

$commodity = new Commodity();
if ($spec->isSatisfiedBy($commodity)) {
    // ...
} 
```

## Фильтрация по спецификации

Также спецификации можно использовать для фильтрации массивов:

```php
<?php
use Domain\Entity\Commodity;
use Domain\Entity\Specification\Commodity as CommoditySpecs;

$spec = new CommoditySpecs/Published();
$published = array_map(
    static function ($candidate) use ($spec) {
        return $spec->isSatisfiedBy($candidate);
    },
    $commodities
);
```

## Больше возможностей

Больше возможностей дают [обработчики спецификаций](handlers.md).
