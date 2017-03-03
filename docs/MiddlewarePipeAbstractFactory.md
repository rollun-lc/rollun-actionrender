#MiddlewarePipeAbstractFactory

Фабрика для создания любых **MiddlewarePipe**.

**PipeLine** задаются в конфиге.

Пример:
```php
    MiddlewarePipeAbstractFactory::KEY => [
        'htmlReturner' => [
            MiddlewarePipeAbstractFactory::KEY_MIDDLEWARES => [
                'HtmlParamResolver'
                'HtmlRendererAction'
            ]
        ]
    ],
```

Где `'htmlReturner'` имя сервиса по которому будет возвращен **Pipe**.   
А `'middlewares'` массив содержащий список имен сервисов по которым можено будет из **SM** достать **Middleware**.
