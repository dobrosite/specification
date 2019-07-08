# Обработчики и трансляторы

[Прямое использование](usage.md) может решить только крайне узкий круг задач. Например, чаще всего
спецификации применяются для извлечения сущностей из хранилищ. Которые, в свою очередь, чаще всего
реализуются с помощью баз данных. Понятно, что загружать из базы все записи, преобразовывать их в
сущности, а затем фильтровать полученный список с помощью спецификации — плохая идея. Было бы
гораздо лучше создать на основе спецификации запрос SQL и получить только нужные сущности.

## Трансляторы

Получить SQL из спецификации можно написав транслятор и обработчики.

Транслятор должен реализовывать интерфейс [Translator](../../src/Translator/Translator.php). Его
задача — взять объект спецификации и создать его строковое представление (выражение). Чтобы это
сделать придётся для каждой спецификации написать свой обработчик, который знает как создавать для
неё выражение на SQL.

### Подготовка спецификаций

Прежде всего надо подготовить классы спецификаций — добавить в них методы, возвращающие их
параметры (при наличии). Пример:

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
    
    // Добавляем метод для получения параметра спецификации.
    public function getValue(): int
    {
        return $this->value;
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

### Объявление интерфейса

Следующим шагом надо объявить интерфейс, позволяющий получать выражение для спецификации. Можно
унаследовать его от [Handler](../../src/Handler/Handler.php). Иначе придётся указывать для каждого
обработчика оба интерфейса — ваш и `Handler`.

```php
<?php
namespace Infrastructure\SQL\Specification;

use DobroSite\Specification\Handler\Handler;
use DobroSite\Specification\Specification;

interface SqlHandler extends Handler
{
    public function composeSqlFor(Specification $specification): string;
}
```

### Написание обработчиков

Теперь для каждой спецификации, надо написать обработчик с интерфейсом `SqlHandler`. Пример:

```php
<?php
namespace Infrastructure\SQL\Specification;

use DobroSite\Specification\Specification;
use Domain\Specification\Commodity\StocksGreaterThan;

class StocksGreaterThanHandler implements SqlHandler
{
    public function getSpecificationClassName(): string
    {
        // Имя спецификации, которую поддерживает этот обработчик.
        return StocksGreaterThan::class;
    }

    public function composeSqlFor(Specification $specification): string
    {
        return sprintf('commodity.stocks > %d', $specification->getValue());
    }
}
```

### Написание транслятора

> TODO

### Собираем всё вместе 

> TODO
