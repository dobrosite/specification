# Написание спецификаций

Сердцем библиотеки является интерфейс [Specification](../../src/Specification.php), который
предоставляет всего один метод — `isSatisfiedBy`, принимающий на входе произвольное значение и
возвращающий истину или ложь, в зависимости от того, удовлетворяет это значение спецификации или
нет.

## Простые спецификации

Например, у нас есть интернет-магазин и мы хотим показывать только товары, которые были опубликованы
(т. е. администратор разрешил их показ посетителям). Мы можем написать для этого спецификацию:

```php
<?php
namespace Domain\Specification\Commodity;

use DobroSite\Specification\Specification;
use Domain\Entity\Commodity;

class Published implements Specification
{
    public function isSatisfiedBy($candidate): bool
    {
        if (!$candidate instanceof Commodity) {
            return false;
        }
        
        return $candidate->published();
    }
}
```

Пример использования:

```php
<?php
use Domain\Entity\Commodity;
use Domain\Entity\Specification\Commodity as CommoditySpecs;

$spec = new CommoditySpecs/Published();

$commodity = new Commodity();
$spec->isSatisfiedBy($commodity); // false 

$commodity->publish();
$spec->isSatisfiedBy($commodity); // true 
```

## Спецификации с параметрами

Спецификации могут иметь параметры. Например, требование к количеству оставшегося товара.

```php
<?php
namespace Domain\Specification\Commodity;

use DobroSite\Specification\Specification;
use Domain\Entity\Commodity;

class StocksGreaterThan implements Specification
{
    private $value;
    
    public function __construct(int $value)
    {
        $this->value;
    }
    
    public function isSatisfiedBy($candidate): bool
    {
        if (!$candidate instanceof Commodity) {
            return false;
        }
        
        return $candidate->stocks() > $this->value;
    }
}
```

Пример использования:

```php
<?php
use Domain\Entity\Commodity;
use Domain\Entity\Specification\Commodity as CommoditySpecs;

$spec = new CommoditySpecs/StocksGreaterThan(100);

$commodity = new Commodity();
$commodity->changeStocksTo(10);
$spec->isSatisfiedBy($commodity); // false 

$commodity->publish();
$commodity->changeStocksTo(200);
$spec->isSatisfiedBy($commodity); // true 
```

## Составные спецификации

Одним из главных преимуществ спецификаций является возможность создавать с их помощью очень сложные
условия. Для этого библиотека предоставляет интерфейс
[CompositeSpecification](../../src/CompositeSpecification.php) и готовые спецификации:

- [Logical\AllOf](../../src/Logical/AllOf.php) — «все», «логическое И»;
- [Logical\AnyOf](../../src/Logical/AnyOf.php) — «любое», «логическое ИЛИ»;
- [Logical\Not](../../src/Logical/Not.php) — отрицание, «логическое НЕ»; 

Например, мы можем построить спецификацию «опубликованные товары с остатком больше 100»:

```php
<?php
use Dobrosite\Specification\Specification\Logical;
use Domain\Entity\Commodity;
use Domain\Entity\Specification\Commodity as CommoditySpecs;

$spec = new Logical\AllOf(
    new CommoditySpecs\Published(),
    new CommoditySpecs\StocksGreaterThan(100)
);

$commodity = new Commodity();
// ...
$spec->isSatisfiedBy($commodity); 
```
