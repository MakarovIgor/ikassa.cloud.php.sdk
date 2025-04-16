## PHP SDK для работы с iKassa Dusik Cloud

<b>iKassa Dusik Cloud</b></br>
Облачная касса в Республике Беларусь без физического СКО</br>

#### Официальная документация:
Описание облачной кассы: https://ikassa.by/box-solutions/dusik_cloud </br>
API: https://ikassa.pages.imlab.by/cloud-cashbox/doc/auth.html</br></br>

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

Пример получения user_token и device_code из BindingData
```php 
$deviceCode = $bindData->getDeviceCode();
$userCode = $bindData->getUserCode();
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

