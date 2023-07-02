<?php

class Token {
    private $link;
    private $data = [];
    

    function __construct($clientId, $clientSecret, $authCode, $redirectUri, $subdomain) {
        $this->data = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $authCode,
            'redirect_uri' => $redirectUri,
        ];
        $this->link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';
    }

    public function getAccessToken() {
        $tokenInfo = $this->readTokenFromFile();
        echo $tokenInfo['access_token'] == null;
        if($tokenInfo['access_token'] == null || $tokenInfo['expiration_time'] < time()) {
            return $this->fetchToken();
        }
        return $tokenInfo['access_token'];
    }
    

    private function fetchToken() {
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
        Validation::checkHttpCode($code);

        $response = json_decode($out, true);

        $accessToken = $response['access_token'];
        $refreshToken = $response['refresh_token'];
        $expiresIn = $response['expires_in'];
        $this->data["refresh_token"] = $refreshToken; 
        $this->data["grant_type"] = "refresh_token";

        $this->saveTokenToFile([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'expiration_time' => $expiresIn + time(),
        ]);
        
        return $accessToken;
    }

    private function saveTokenToFile($tokenInfo) {
        $json = json_encode($tokenInfo);
        file_put_contents(__DIR__ . '/token.json', $json);
    }
    
    // Функция для чтения токена из файла
    private function readTokenFromFile() {
        $json = file_get_contents(__DIR__ . './token.json');
        return json_decode($json, true);
    }
    

}