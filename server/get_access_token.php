<?php
$subdomain = 'murashovtemkayandexru'; //Поддомен нужного аккаунта
$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

/** Соберем данные для запроса */
$data = [
    'client_id' => '3fe3d1c0-efe3-426f-847e-b64e8d1c1ca6',
    'client_secret' => '3sygOQ7thDUghKwyJnJYWhaWGwjqPx5dXskJX9RuqJUK6v4qqYkZehrdKzKiqm2b',
    'grant_type' => 'authorization_code',
    'code' => 'def50200bb17190d56eef425995a21fb88d5c683c11006b85e34f6cff252e66922899435c8cd88b31fb55dbd0c2bfa6defbb63082ab48588dbcb6bba5a874475a344381207298c2407ae5e01856f301620a97bc2dadc106e4882278ecd406516c6db69e9cc5df01671caaa0e0f612cd9465fcfc6c99b0c1a54451d1d5903d02e919c53ecbcbc3837feca0efcfca6808eee8e85c6ef27edf2f7485246f4dac18043dc77f70bd8b94e76025c1fa9b8fd9a900ae1abedcc04824159f7f6f7152e6065947d5c58793085f2ec0788069cc5066332a8a840d8161f6467e2f0d0070069b461a882637b0c9422eaad83347cd18c7fd1761ca1b5be0f40f0d2908a430a7e041a2d77518db8f17fbc64425c9910940bc9d605aa30e1365a78b267ad56a5d0c075a37df3b502744ba439da5006095fd9abfd28efb3fc70cb7fbda85dc5137390c560c959c4158962c112981115f13cdee24a008463b663a1bfd0f66566d26ba4595f79ccc18e3e3034eec84bc28574e4e2d303f064c02cda9c3bbe0477bc7c82b39d0a0cce77bf2ad6bee64aa406379504484330cf81fa4349597e5ae9ce3eca7f78c6985c78f5b41deb931fdcf9c81d805b262b7393fb2efd3219a38eb3e8c489c311188404a835f7b97d68bc1adced28cca97f67b07af317d36c204200c0d7',
    'redirect_uri' => 'https://localhost',
];

/**
 * Нам необходимо инициировать запрос к серверу.
 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
 */
$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
/** Устанавливаем необходимые опции для сеанса cURL  */
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
/** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$code = (int)$code;
$errors = [
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
];

try
{
    /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
    if ($code < 200 || $code > 204) {
        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
    }
}
catch(\Exception $e)
{
    die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}

/**
 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 * нам придётся перевести ответ в формат, понятный PHP
 */
$response = json_decode($out, true);

$access_token = $response['access_token']; //Access токен
$refresh_token = $response['refresh_token']; //Refresh токен
$token_type = $response['token_type']; //Тип токена
$expires_in = $response['expires_in']; //Через сколько действие токена истекает
