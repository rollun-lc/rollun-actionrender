# rollun-actionrender

---
## [Оглавление](https://github.com/avz-cmf/Server-Drakon/blob/master/Table%20of%20contents.md)

---

Каркас для создания приложений. 

* [Стандарты](https://github.com/rollun-com/rollun-skeleton/blob/master/docs/Standarts.md)

* [Quickstart](https://github.com/avz-cmf/saas/blob/master/docs/Quickstart.md)

* [ActionRenderAbstractFactory](https://github.com/rollun-com/rollun-actionrender/blob/master/docs/ActionRenderAbstractFactory.md)

* [MiddlewarePipeAbstractFactory](https://github.com/rollun-com/rollun-actionrender/blob/master/docs/MiddlewarePipeAbstractFactory.md)

* [ResponseRendererAbstractFactory](https://github.com/rollun-com/rollun-actionrender/blob/master/docs/ResponseRendererAbstractFactory.md)

* [ActionRender QuickStart](https://github.com/rollun-com/rollun-actionrender/blob/master/docs/QuickStart.md)

#ActionRender

Для более детального ознакомления обратитесь в [ActionRender QuickStart](https://github.com/rollun-com/rollun-actionrender/blob/master/docs/QuickStart.md)

Цепочка Middleware поделенная на две логические части Action и Render.

## Последовательость работы ActionRender

В самом простом случае у нас существует два **Middleware** 

1) **Action** - Выполняет определенное действие. Результат должен пометсить в атребут запроста `Response-Data`

2) **Render** - Создает ответ и кладет его в атрибут запроса с именем Psr\Http\Message\ResponseInterface.

Теоретически **Render** может вернуть ответ, но мы рекомендуем использовать для этого **Returner**.
Он достанеи Response из атрибута запроста и вернет его пользователю.

3) **Returner** - возвращает результат. 
Моежт использоваться в качестве аспекта.

Каждый из эих двух **Middleware** могут быть заменены на двумя **Middleware**

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