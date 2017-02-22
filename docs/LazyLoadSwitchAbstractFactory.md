# LazyLoadSwitchAbstractFactory

Фабрика которая возвращает один из указаного в конфиге списка middleware.

Рассмотрим конфиг.

```php
    LazyLoadSwitchAbstractFactory::LAZY_LOAD_SWITCH => [
        'authTypeSwitch' => [
            LazyLoadSwitchAbstractFactory::KEY_COMPARATOR_SERVICE =>
                \rollun\permission\Comparator\PathRequestComparator::class,
            LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                '/\/base/' => 'baseAuth',
                '/\//' => 'openId',
            ]
        ],
        'authReturnSwitch' => [
            LazyLoadSwitchAbstractFactory::KEY_COMPARATOR_SERVICE => 'returnResultAttributeRequestComparator',
            LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                'true' => \rollun\actionrender\ReturnMiddleware::class,
                'false' => \rollun\permission\Auth\Middleware\UserResolver::class,
            ]
        ]
    ],

```

Конфигурация Switch-а предусматривает два параметра 

* `LazyLoadSwitchAbstractFactory::KEY_COMPARATOR_SERVICE` - имя сервиса компаратора.
* `LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE` - список имен сервисов middleware.
> Ключем указываеться паттерн, а значение имя сервиса.

В данном конфиге указанно два Switch-а, расмотрим их по порядку. 

### authTypeSwitch

Данный Switch вернет `'baseAuth'` middleware если мы будем находиться в подветке роутов `/base`, в ином случае будет возвращен `'openId'`.
    
### authReturnSwitch

В случае если атребут запроса `returnResult` равен `'true'` будет возвращен ` \rollun\actionrender\ReturnMiddleware::class`,
иначе `\rollun\permission\Auth\Middleware\UserResolver::class`.
    

## RequestComparator 

Компаратор - класс реализующий `RequestComparatorInterface::class`.
Принимает на вход обьект запроса и патерн ответа, в случае если патер ответа удовлетворительный, компаратор вернет `true`,
в ином случае будет возвращен `false`.

Данная библиотека предоставляет два готовых `RequestComparator`

* PathRequestComparator - компаратор который сравнивает pattern с значением **path** запроса. 
> Возвращает `true` если значением **path** удвоволетворяет паттерн. Использует pattern в качестве регулярки.
> Учитывайте что более широкие патерны могут перекрывать более узкие.

* AttributeRequestComparator - компаратор который сравнивает pattern c указаным ему в конфигурации полем артриубта запроса.  
> Вернет true если найдет соответсвее.

Конфиг фабрики 

```php
    AttributeRequestComparatorAbstractFactory::KEY_COMPARATOR => [
        'returnResultAttributeRequestComparator' => [
            AttributeRequestComparatorAbstractFactory::KEY_ATTRIBUTE_KEY => 'returnResult'
        ]
    ],
```

Ключем `AttributeRequestComparatorAbstractFactory::KEY_ATTRIBUTE_KEY` указываем имя атребута запроса с которым будем сравнивать.