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
Мы просто можем разделить их на три Middleware, и добавить их в PipeLine

Первый SomeActionMiddleware. Он будет выполять какое то действие и ложить его в атрибуты запроса под именем 'responceData'.
```php
class SomeActionMiddleware implements MiddlewareInterface 
{
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        //todo some Action
        //...
        $request = $request->withAtrribute('responceData', $data);
        if (isset($out)) {
           return $out($request, $response);
        }
        return $response;
    }
}
```

Второй SomeRenderMiddleware. Он будет брать данные из атрибута 'responceData' и строить представление.
И класть его в атрибут запроса 'Psr\Http\Message\ResponseInterface'.

```php
class SomeRenderMiddleware implements MiddlewareInterface 
{
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $data =  $request->getAtrribute('responceData');
        //todo $html gen
        //..
        retrun new HtmlResponse($html, $status);
    }
}
```

А третий SomeReturnerMiddleware. Он будет брать резултат из атрибута запроса, и отдавать его пользователю.
Моежт использоваться в качестве аспекта.

```php
class SomeRenderMiddleware implements MiddlewareInterface 
{
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $data =  $request->getAtrribute('responceData');
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

1) **Action** - Выполняет определенное действие. Результат должен пометсить в атребут запроста `responseData`

2) **Render** - Отдает ответ пользователю.

3) **Returner** - возвращает результат. 

Так же вам не нежно заботится о **Returner** та как по умолчанию 
используеться самая простая реализация которая просто возвращает резаультат.
Но если вам нужно использовать его в качестве аспекта, 
вы можете указать в конфиге конкретный сервис который вернет Returner Middleware.


Теперь останется только указать в конфиге наш Middleware-Service.
```php
     ActionRenderAbstractFactory::KEY_AR_SERVICE => [
        'home' => [
            ActionRenderAbstractFactory::KEY_AR_MIDDLEWARE => [
                ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE => 'SomeActionMiddleware',
                ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'SomeRenderMiddleware'
                // Не обязательно указывать так как будет использовать Returner по умолчанию.
                //ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'SomeReturnerrMiddleware'
            ]
        ]
     ]
```
> [Default Returner](../src/ReturnMiddleware.php)

`'home'` - имя по которому будет создан данный **ActionRender Middleware**
`'SomeActionMiddleware'` и `'SomeRenderMiddleware'` имена сервисов по которым **SM** вернет соответствующе **Middleware**. 

Так же, бывают ситуации когда мы должны получить какие то параметры из Request до выполения самого Action или Render.
Для таких случаях предусмотрено что каждый из эих двух **Middleware** могут быть заменены на двумя **Middleware**

* Для **Action**  
    1) **ParamResolver** - выкусывает нужные параметры акшену из запроса и кладет их в атрибуты.  
    2) **Action** -  выполняет действие и результат кладет в `responceData`.  
* Соответсвенно для **Render**  
    1) **ParamResolver** - выкусывает нужные параметры вьюверу из запроса и кладет их в атрибуты.  
    2) **Render** -  выполняет отрисовку результата и кладет их в атрибуты.  
    
## Замечания

* Каждый из **Middleware** может быть **Middleware**, **pipeLine** либо **LazyLoadFactory** (Которая вернет **Middleware**).
    > Пример [**LazyLoadFactory** -> ResponseRendererAbstractFactory](../src/ActionRender/Renderer/ResponseRendererAbstractFactory.php)

* **LazyLoadFactory** не могут передавать какие то занчения в **Middleware** по средсву **Request**.
Для этого стоит использовать либо параметры контейнера либо **ParamResolver Middleware**.

## Компоненты

* [ActionRenderAbstractFactory](./ActionRenderAbstractFactory.md) - Фабрика которая создает ActionRender.

* [MiddlewarePipeAbstractFactory](./MiddlewarePipeAbstractFactory.md) - Вспомогательная абстрактная фабрика для создания middlewarePipeLine.

* [ResponseRendererAbstractFactory](./ResponseRendererAbstractFactory.md) - Вспомогательная абстрактная LazyLoad фабрика для прорисовки данных.