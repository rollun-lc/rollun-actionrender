# LazyLoadSwitchAbstractFactory

Фабрика которая возвращает middlewarePipe в который войдут те middleware ключи которых находяться в массиве,
полученном из атрибута запроса с указаным в конфиге иминем.
 > Если имя не указано явно, будет использовано по умолчанию - `LazyLoadSwitchAbstractFactory::DEFAULT_ATTRIBUTE_NAME`.

Рассмотрим конфиг.

```php
    LazyLoadSwitchAbstractFactory::LAZY_LOAD_SWITCH => [
        'testSwitch' => [
            LazyLoadSwitchAbstractFactory::KEY_ATTRIBUTE_NAME => "testArg", //not required
            LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                'test1' => 'middlewareTest1',
                'test2' => 'middlewareTest2',
                'test3' => 'middlewareTest3',
            ]
        ]
    ],
```
Список параметров

* LazyLoadSwitchAbstractFactory::KEY_ATTRIBUTE_NAME - имя атрибута. 
> Не обязательный параметр

* LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE - список middleware которые будут помещены в pipeLine.
> Только те middleware ключи которых находяться в массиве атрибута запроса будут помещены в pipeLine.