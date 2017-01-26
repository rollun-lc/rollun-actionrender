#ActionRenderAbstractFactory

Фабрика для создания **ActionRender**. 
Создаст pipeLine с последовательностью двух **Middleware** - **Action** и **Render**
Значение задаеться в конфиге

```php
     ActionRenderAbstractFactory::KEY_AR_SERVICE => [
        'home' => [
            ActionRenderAbstractFactory::KEY_AR_MIDDLEWARE => [
                ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE => 'Action',
                ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'Render'
            ]
        ]
     ]
```
`'home'` - имя по которому будет создан данный **ActionRender Middleware**
`'Action'` и `'Render'` имена сервисов по которым **SM** вернет **Middleware**. 
`'Action'` выполнит какое то дейсвие и результат положит в атребут запроса `responceData`, а `'Render'` отобразит данный результат.
