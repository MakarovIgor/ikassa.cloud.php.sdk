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
> access_token - токен полученый из методов getAccessTokenData или refreshAccessTokenData

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

Изьятие денег из кассы.

```php
$header = new Header(
        string $cashierName,
        string $currency
    );

$fiscalDocumentData = new FiscalDocumentData(IHeader $header, int $sum);    

$kassaApi->withdraw(FiscalDocumentData $fiscalDocumentData); // Изьятие. Обязательно нужно выполнят перед закрытием смены, если есть наличка в кассе.
```

Продажа товара.

```php
$positions = [];

$position1 = new PositionBuilder(
    string $goodTitle,
    int $cost     // Цена за 1 ед. тов. позиции в копейках
    int $quantity // Кол-во тов. позиции. Передается минимальное значение с учетом 3х знаков после запятой. Например: 1.000 (1 штука) = 1000. 1.234 (1 кг 234 г) = 1234.
);

$position1->setTax(int $tax); // НДС товара. Доступны значения - 0, 10, 20, 25
$position1->setSection(Section $section); // Секция товара. Опциональное поле. Принимает обьект Section(int $code, string $name)
$positions[] = $position1->build();  

$items = new Positions($positions);

$paymentsArr = [];
$paymentsArr[] = new Payment(int $type, int sum); // $type - тип оплаты. 0 - Безнал, 1 - Наличные. 2 - др. способы. 

$payments = new Payments($paymentsArr); // //Кол-во оплат наличными не может быть больше 1 штуки

$modifier = new Modifier(int $sum, string $name = "", string $group = "") // Модификаторы цены (скидки/надбавки). Опциональное поле. 

//$sum
//Скидка: отрицательное значение.
//Надбавка: положительное значение.
//Значение по модулю не может превышать 54975581388799

//$name
//Наименование Скидки/Надбавки для отображения

//$group
//Идентификатор для группировки и получения статистической информации из внешнего ПО: бух. учет, ТУ и др.

$modifiers = new Modifiers(array $modifiers); // Может быть передана только 1 скидка и 1 надбавка.

$receipt = new Receipt(
    $header,
    $items,
    $payments,
    $modifiers
);

$kassaApi->sale(Receipt $receipt); // Продажа
```