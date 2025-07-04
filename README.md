## PHP SDK для работы с iKassa Dusik Cloud

<b>iKassa Dusik Cloud</b></br>
Облачная касса в Республике Беларусь без физического СКО</br>

#### Официальная документация:
Описание облачной кассы: https://ikassa.by/box-solutions/dusik_cloud </br>
API: https://ikassa.pages.imlab.by/cloud-cashbox/doc/auth.html</br></br>

### Подключение к проекту:
```cli
composer require igormakarov/ikassa.cloud.php.sdk
```
```php
require_once 'vendor/autoload.php';
```

### Работа с Авторизацией(получение device_code, получение прав для работы с API):

Для работы с авторизаций с iKassa потребуется класс Auth:
```php
$auth = new Auth(
    new AuthData(
        'https://accounts.stage.imlab.by',
        '<client_id>'
    )
);
```
> client_id - предприятие или клиент получает при регистрации и личном обращении в поддержку iKassa

Получение данных для подтверждения:
```php
$bindData = $auth->bindingData("_os", "_osVer", '_deviceName', '_appName');
```
при успешно выполнении в $bindData запишет объект BindingData, котором хранится и можно будет получить user_code для привязки кассы в ЛК iKassa 
и device_code для дальнейшего получения токена авторизации 

Пример получения user_code и device_code из BindingData
```php 
$deviceCode = $bindData->getDeviceCode(); // метод получения device_code
$userCode = $bindData->getUserCode(); // метод получения user_code
$expireIn = $bindData->getExpiresIn(); // метод получения скрока действия user_code для привязки кассы в ЛК 
```

<b>Только после того, как получили $userCode и привязали по нему кассу в ЛК, можно получить ключ для работы с API</b> 

Получаем права доступа для работы с API

```php
$accessTokenData = $auth->getAccessTokenData('<ваш_device_code_полученный_выше>');

$accessTokenData->getAccessToken() // получаем access_token для работы с API
$accessTokenData->getRefreshToken() // получаем refresh_token для обновления access_token через expire_in времени
$accessTokenData->getExpiresIn() // получаем время по истечению которого нужно обновить access_token,
```
Обновление прав доступа
    
```php
$newAccessTokenData = $auth->refreshAccessTokenData('<your_refresh_token>') //your_refresh_token - токен получены с помощью $accessTokenData->getRefreshToken() ранее
```

> refreshAccessTokenData - получает тот же объект AccessTokenData что и getAccessTokenData


### Основные методы для работы с API 

Для работы с API iKassa потребуется класс IKassaApiClient:
```php

$kassaApi = new IKassaApiClient(
    new AuthData(
        'https://api.cloud.stage.imlab.by',
        'access_token'
    )
);
```
> access_token - токен полученный из методов getAccessTokenData или refreshAccessTokenData

В случае успеха, в $kassaApi запишется класс, для дальнейшей работы с API.

```php
$kassaApi->isConnected(); // проверяет, привязана ли касса в личном кабинете iKassa.
$kassaApi->getShift(); // возвращает данные о текущей смене
$kassaApi->shiftIsOpen(); // проверяет, открыта ли смена в данный момент
$kassaApi->openShift(); // открытие смены
$kassaApi->closeShift(); // закрытие смены. Перед закрытием смены, из кассы обязательно должна быть изьяты все наличные.
$kassaApi->getCashSumInCashBox(string $currency); // проверка наличных средств в кассе
```
> $currency - валюта, использующаяся в кассе(BYN, USD, EUR или RUB). Типы валют которые используются можно узнать в Currencies::class

Простая продажа товара.

```php
// Кол-во тов. позиции. Передается минимальное значение с учетом 3х знаков после запятой. Например: 1.000 (1 штука) = 1000. 1.234 (1 кг 234 г) = 1234

$iKassaClient->sale(
        new Receipt(
            new Header("ФИО кассира", Currencies::BYN), //ФИО кассира и валюта
            new Positions([ //Товары
                (new PositionBuilder('test good', 200, 1000)) //Название товара, стоимость, количество
                    ->setSection(new Section(1, 'отдел 1')) //Номер и название отдела
                    ->setTax(TaxTypes::TAX_10) //HДС 10%, Доступны значения - 0, 10, 20, 25
                    ->build()
            ]),
            new Payments([ //Оплаты
                new Payment(PaymentTypes::CASH, 200), //Тип оплаты "наличка" - CASH, "безнал" - CARD, иные способы расчета "ОTHER" 
            ])
        )
    ); // возвращает УИ - уникальный идентификатор документа

```
Более сложная продажа со скидками
```php
$iKassaClient->sale(
         new Receipt(
             new Header("ФИО кассира", Currencies::BYN),
             new Positions([
                 (new PositionBuilder('test good', 200, 1000))
                     ->setSection(new Section(1, 'отдел 1'))
                     ->setTax(TaxTypes::TAX_10)
                     ->setDiscount(new Discount(100, "ночная скидка на товар")) //Скидка на товар
                     ->toService() // как услуга
                     ->build(),
                 (new PositionBuilder('test good 2', 100, 1000))
                     ->setSection(new Section(1, 'отдел 1'))
                     ->setTax(TaxTypes::TAX_10)
                     ->setMarkup(new Markup(100, "надбавка просто так на товар")) // Надбавка на товар
                     ->build()
             ]),
             new Payments([
                 new Payment(PaymentTypes::CASH, 100),
                 new Payment(PaymentTypes::CARD, 100),
             ]),
             new Modifiers([
                 new Discount(100, "скидка на весь чек")
             ])
         )
     ); // возвращает УИ - уникальный идентификатор документа
```

Возврат 
```php
$iKassaClient->refund(
        new RefundReceipt(
            new Header("ФИО кассира", Currencies::BYN),
            new Positions([
                (new PositionBuilder('test good', 100, 1000))
                    ->setSection(new Section(1, 'отдел 1'))
                    ->setTax(TaxTypes::TAX_10)
                     ->setDiscount(new Discount(100, "ночная скидка"))
                    ->build(),
                new Position('test good 22', 100, 1000)
            ]),
            new Payments([
                new Payment(PaymentTypes::CASH, 20),
                new Payment(PaymentTypes::CARD, 80),
            ])
        )
    ); // возвращает УИ - уникальный идентификатор документа
```

Внесение и изъятие и выдача денег 
```php

// Внесение
$iKassaClient->deposit(new FiscalDocumentData(new Header("ФИО Кассира", Currencies::BYN), 100));
// Изъятие. Обязательно нужно выполнять для каждой валюты перед закрытием смены, если есть наличка в кассе.
$iKassaClient->withdraw(new FiscalDocumentData(new Header("ФИО Кассира", Currencies::BYN), 100));
// Выдача
$iKassaClient->cHWithdraw(new FiscalDocumentData(new Header("MyName", Currencies::$BYN), 10));

// Все методы возвращают УИ - уникальный идентификатор документа
```
Аннулирование последнего документа
```php
$iKassaClient->rollback(new RollbackFiscalDocumentData(new RollbackHeader("ФИО кассира"), 61)); //61 - номер последнего документа
// возвращает УИ - уникальный идентификатор документа
```


Как получить чек в HTML-формате по его УИ
```php
//адреса для получения чека в html формате
//https://receipts.cloud.ikassa.by/ - для прода 
//https://receipts.cloud.stage.imlab.by/ - для стейджа

var_dump((new DocumentRender(
    "https://receipts.cloud.stage.imlab.by/"
))->render('813D867242EC1704071834F3'));

```

Дополнительные функции
```php
//Получить сумму наличных в кассе определенной валюты
$sum = $iKassaClient->getCashSumInCashBox(Currencies::BYN);
```
