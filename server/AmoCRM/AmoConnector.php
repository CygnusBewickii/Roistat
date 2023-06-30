<?php

include './Token.php';

class AmoConnector {
    
    public $token;

    function __construct($clientId, $clientSecret, $authCode, $redirectUri, $subdomain) {
        $this->token = new Token($clientId, $clientSecret, $authCode, $redirectUri, $subdomain);
    }
}