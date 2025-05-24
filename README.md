# REST-api для онлайн магазина

## Пользователь
- ### Регистрация-`POST /api/auth/register`
Запрос:
```json
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "secret123"
}
```  
Ответ:
```json
{
  "access_token": "1|abcdef123456...token...",
  "token_type": "Bearer"
}
```
- ### Авторизация - `POST /api/login`   
Запрос:
```json
{
  "email": "test@example.com",
  "password": "secret123"
}
```
Ответ:
```json
{
  "access_token": "1|abcdef123456...token...",
  "token_type": "Bearer"
}
```
- ### Выход: `POST /api/logout`
Ответ:
```json
{
  "message": "Logged out"
}
```
## Товары
- ### Получение списка товаров `GET /api/products`
Пример запроса с сортировкой:`GET /api/products?sort=price_asc`
Ответ:
```json
[
  {
    "id": 1,
    "name": "Product A",
    "description": "Test product",
    "price": 9.99
  },
  {
    "id": 2,
    "name": "Product B",
    "description": "Another product",
    "price": 14.99
  }
]
```
- ### Получение товара по ID: `GET /api/products/{id}`
Ответ:
```json
{
  "id": 1,
  "name": "Product A",
  "description": "Test product",
  "price": 9.99
}
```

## Корзина
- ### Добавление товара в корзину: `POST /api/cart/items`
Запрос
```json
{
  "product_id": 1,
  "quantity": 2
}
```
Ответ
```json
{
  "message": "Item added to cart"
}
```
- ### Удаление товара из корзины: `DELETE /api/cart/items/{id}`
Запрос `DELETE /api/cart/items/3`  
Ответ:
```json
{
  "message": "Item removed from cart"
}
```
- ### Получение корзины: `GET /api/cart`
ответ:
```json
{
  "id": 5,
  "user_id": 1,
  "items": [
    {
      "id": 10,
      "product_id": 1,
      "quantity": 2,
      "product": {
        "name": "Product A",
        "price": 9.99
      }
    }
  ]
}
```
## Заказы
- ### Оформить заказ: `POST /api/checkout`
Запрос
```json
{
  "payment_method_id": 1
}
```
Ответ:
```json
{
  "message": "Order created",
  "payment_url": "http://site.com/api/payments/mock/12"
}
```
- ### Подтверждение оплаты: `GET /api/payments/mock/{order_id}`
Запрос: `GET /api/payments/mock/12`
Ответ:
```json
{
  "message": "Order marked as paid"
}
```
- ### Получить список заказов: `GET /api/orders`
Запрос с фильтрацией и сортировкой:
```
GET /api/orders?status=paid&sort=date_desc
```
Ответ:
```json
[
  {
    "id": 1,
    "status": "paid",
    "created_at": "2025-05-24T18:00:00Z",
    "payment_method": {
      "name": "MockPay"
    }
  }
]
```
- ### Получить заказ по ID: `GET /api/orders/{id}`
Запрос:
```
GET /api/orders/1
```
Ответ: 
```json
{
  "id": 1,
  "status": "paid",
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 9.99
    }
  ],
  "payment_method": {
    "name": "MockPay"
  }
}
```

## Ссылки оплаты
- Пример: `/payments/callback/{order_id}`
- При переходе по ссылке вызывается `PUT /api/orders/{id}/paid`

## Фоновая задача
- Scheduler проверяет заказы со статусом "pending", старше 2 минут
- Обновляет статус на "cancelled"

### Тесты
- `php artisan test --filter=AuthTest` - Авторизация
- `php artisan test --filter=CartTest` - Добавление/удаление из корзины
- `php artisan test --filter=CheckoutTest` - Тайм-аут заказов
- `php artisan test --filter=PaymentCallbackTest` - Оплата корзины и создание заказа









