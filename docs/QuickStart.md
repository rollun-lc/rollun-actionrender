#ActionRender

## Быстрый старт

### Обычная практика

Пускай у нас есть обычный Middleware который чот то делает и возвращает результат.

```php
class SomeMiddleware implements MiddlewareInterface 
{
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        //todo some Action
        //...
        
        //todo $html gen
        //..
        
        retrun new HtmlResponse($html, $status);
    }
}
```

Как мы можем увидеть наш SomeMiddleware разделен на 2 логических блока Action и Render.
В одном мы производим какие то дейсвия для получения данных,
 в другом мы эти данные переводим в представляение.
И ссылаясь на принцип MVC тут четко видна граница Controller и View.
Так что было бы правильно разделить их.
Как мы можем это сделать ? 
Мы просто можем разделить их на два Middleware, и добавить их в PipeLine

Первый SomeActionMiddleware. Он будет выполять какое то действие и ложить его в атрибуты запроса под именем 'Responce-Data'.
```php
class SomeActionMiddleware implements MiddlewareInterface 
{
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        //todo some Action
        //...
        $request = $request->withAtrribute('Responce-Data', $data);
        if (isset($out)) {
           return $out($request, $response);
        }
        return $response;
    }
}
```

Второй SomeRenderMiddleware. Он будет брать данные из атрибута 'Responce-Data' и строить представление.

```php
class SomeRenderMiddleware implements MiddlewareInterface 
{
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $data =  $request->getAtrribute('Responce-Data');
        //todo $html gen
        //..
        retrun new HtmlResponse($html, $status);
    }
}
```
Тпереь нам останеться создать фабрику в которой мы создать pipeLine и положим туда эти 2 Middleware.
В общем случае мы можем представить любое действие как последовательность двух Middleware - Action и Render.

## ActionRender

Данная библиотека позволяет следовать данной идеалогии разделение действия на два Middleware.

1) **Action** - Выполняет определенное действие. Результат должен пометсить в атребут запроста `Response-Data`

2) **Render** - Отдает ответ пользователю.

Теперь останется только указать в конфиге наш Middleware-Service.


```php
     ActionRenderAbstractFactory::KEY_AR_SERVICE => [
        'home' => [
            ActionRenderAbstractFactory::KEY_AR_MIDDLEWARE => [
                ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE => 'SomeActionMiddleware',
                ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'SomeRenderMiddleware'
            ]
        ]
     ]
```
`'home'` - имя по которому будет создан данный **ActionRender Middleware**
`'SomeActionMiddleware'` и `'SomeRenderMiddleware'` имена сервисов по которым **SM** вернет соответствующе **Middleware**. 

Так же, бывают ситуации когда мы должны получить какие то параметры из Request до выполения самого Action или Render.
Для таких случаях предусмотрено что каждый из эих двух **Middleware** могут быть заменены на двумя **Middleware**

* Для **Action**  
    1) **ParamResolver** - выкусывает нужные параметры акшену из запроса и кладет их в атрибуты.  
    2) **Action** -  выполняет действие и результат кладет в `Responce-Data`.  
* Соответсвенно для **Render**  
    1) **ParamResolver** - выкусывает нужные параметры вьюверу из запроса и кладет их в атрибуты.  
    2) **Render** -  выполняет отрисовку результата и возвращает пользователю  
    
## Замечания

* Каждый из **Middleware** может быть **Middleware**, **pipeLine** либо **LazyLoadFactory** (Которая вернет **Middleware**).
    > Пример [**LazyLoadFactory** -> ResponseRendererAbstractFactory](../src/ActionRender/Renderer/ResponseRendererAbstractFactory.php)

* **LazyLoadFactory** не могут передавать какие то занчения в **Middleware** по средсву **Request**.
Для этого стоит использовать либо параметры контейнера либо **ParamResolver Middleware**.


## Компоненты

* [ActionRenderAbstractFactory](./ActionRenderAbstractFactory.md) - Фабрика которая создает ActionRender.

* [MiddlewarePipeAbstractFactory](./MiddlewarePipeAbstractFactory.md) - Вспомогательная абстрактная фабрика для создания middlewarePipeLine.

* [ResponseRendererAbstractFactory](./ResponseRendererAbstractFactory.md) - Вспомогательная абстрактная LazyLoad фабрика для прорисовки данных.