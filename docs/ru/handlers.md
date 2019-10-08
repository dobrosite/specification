# Обработчики и трансляторы

[Прямое использование](usage.md) может решить только крайне узкий круг задач. Например, чаще всего
спецификации применяются для извлечения сущностей из хранилищ. Которые, в свою очередь, чаще всего
реализуются с помощью баз данных. Понятно, что загружать из базы все записи, преобразовывать их в
сущности, а затем фильтровать полученный список с помощью спецификации — плохая идея. Было бы
гораздо лучше создать на основе спецификации запрос на SQL и получить только нужные сущности.

## Трансляторы

Получить SQL из спецификации можно написав транслятор и обработчики.

Задача транслятора — взять объект спецификации и создать его строковое представление (выражение).
Чтобы это сделать придётся для каждой спецификации написать свой обработчик, который знает как
создавать для неё выражение на SQL.

### Подготовка спецификаций

Прежде всего надо подготовить классы спецификаций — добавить в них методы, возвращающие их
параметры (при наличии). Пример:

```php
<?php
namespace Domain\Specification\Commodity;

use DobroSite\Specification\Specification;
use Domain\Entity\Commodity;

/**
 * Остатки товара больше указанного количества.
 */
class StocksGreaterThan implements Specification
{
    /**
     * Параметр спецификации (величина остатков, с которой выполняется сравнение).
     */
    private $value;
    
    public function __construct(int $value)
    {
        $this->value = $value;
    }
    
    /**
     * Возвращает значение параметра спецификации.
     * 
     * Этот метод понадобится для получения параметра спецификации.
     */
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
namespace Infrastructure\SQL\Specification\Handler;

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
namespace Infrastructure\SQL\Specification\Handler;

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
        // Возвращаем выражение на SQL, соответствующее спецификации.
        return sprintf('commodity.stocks > %d', $specification->getValue());
    }
}
```

### Написание транслятора

Транслятор должен реализовывать интерфейс [Translator](../../src/Translator/Translator.php),
содержащий единственный метод — `translate`. Метод принимает на вход спецификацию и должен
возвращать его строковое представление.

Для создания SQL в этом примере используются описанные выше обработчики спецификаций. 

```php
<?php
namespace Infrastructure\SQL\Specification\Translator;

use DobroSite\Specification\Handler\HandlerRegistry;
use DobroSite\Specification\Specification;
use DobroSite\Specification\Translator\Translator;
use Infrastructure\SQL\Specification\Handler\SqlHandler;

class SqlTranslator implements Translator
{
    /**
     * Реестр обработчиков спецификаций.
     *
     * @var HandlerRegistry
     */
    private $registry;

    public function __construct(HandlerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function translate(Specification $specification): string
    {
        // Ищем обработчик переданной спецификации, умеющий создавать из неё выражение на SQL
        // (т. е. реализующий интерфейс SqlHandler).
        $handler = $this->registry->getHandlerFor($specification, [SqlHandler::class]);

        return $handler->composeSqlFor($specification);
    }
}
```

### Собираем всё вместе 

```php
<?php

use DobroSite\Specification\Handler\BasicHandlerRegistry;
use DobroSite\Specification\Logical\AllOf;
use Domain\Specification\Commodity\StocksGreaterThan;
use Infrastructure\SQL\Specification\Handler\AllOfHandler;
use Infrastructure\SQL\Specification\Handler\AnyOfHandler;
use Infrastructure\SQL\Specification\Handler\NotHandler;
use Infrastructure\SQL\Specification\Handler\StocksGreaterThanHandler;
use Infrastructure\SQL\Specification\Translator\SqlTranslator;

// Создаём реестр обработчиков спецификаций.
$registry = new BasicHandlerRegistry();

// Создаём и регистрируем обработчики спецификаций.
$registry->registerHandler(new AllOfHandler());
$registry->registerHandler(new AnyOfHandler());
$registry->registerHandler(new NotHandler());
$registry->registerHandler(new StocksGreaterThan());
// ...

// Создаём транслятор.
$translator = new SqlTranslator($registry);

// Создаём спецификацию.
$specification = new AllOf(new StocksGreaterThan(100), /* ... */);

// Получаем выражение на SQL для нашей спецификации.
$sql = $translator->translate($specification);
```
