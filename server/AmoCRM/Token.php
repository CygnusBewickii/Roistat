<?php

include __DIR__ . './utils/Validation.php';

class Token {
    private $link;
    private $data = [];
    private $accessToken; // В реальных проектах стоит использовать более безопасный метод хранения (+ хэшировать)
    private $refreshToken;
    private $exprirationTime;
    

    function __construct($clientId, $clientSecret, $authCode, $redirectUri, $subdomain) {
        $this->data = [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'grantType' => 'authorization_code',
            'code' => $authCode,
            'redirectUri' => $redirectUri,
        ];
        $this->link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';
    }

    public function get_token() {
        $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $this->link);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($this->data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $code = (int)$code;
        Validation::check_http_code($code);

        $response = json_decode($out, true);
        $this->accessToken = $response['access_token']; //Access токен
        $this->refreshToken = $response['refresh_token']; //Refresh токен
        $this->exprirationTime = $response['expires_in']; //Через сколько действие токена истекает
        echo $this->accessToken, $this->refreshToken, $this->exprirationTime;
    }



}