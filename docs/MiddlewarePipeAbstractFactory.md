#MiddlewarePipeAbstractFactory

Фабрика для создания любых **MiddlewarePipe**.

**PipeLine** задаются в конфиге.

Пример:
```php
    MiddlewarePipeAbstractFactory::KEY_AMP => [
        'htmlReturner' => [
            'middlewares' => [
                'HtmlParamResolver'
                'HtmlRendererAction'
            ]
        ]
    ],
```

Где `'htmlReturner'` имя сервиса по которому будет возвращен **Pipe**.   
А `'middlewares'` массив содержащий список имен сервисов по которым можено будет из **SM** достать **Middleware**.
