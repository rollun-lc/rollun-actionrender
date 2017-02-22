# LazyLoadDirectAbstractFactory

Фабрика которая возвращает тот middleware который ей вернет указаная directFactory.
Передает ей в качестве параматра `$requestedName`, значение атребута `resourceName`.

```php
    LazyLoadDirectAbstractFactory::KEY_LAZY_LOAD => [
        'webhookLazyLoad' => [
            LazyLoadDirectAbstractFactory::KEY_DIRECT_FACTORY =>
                \rollun\callback\Middleware\Factory\InterruptorDirectFactory::class
        ]
    ],
```
Параметр `LazyLoadDirectAbstractFactory::KEY_DIRECT_FACTORY` указывам класс DirectFactory.
