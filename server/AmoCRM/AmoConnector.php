<?php

include __DIR__ . './Token.php';
include __DIR__ . './Leads.php';
include __DIR__ . '/../utils/Validation.php';


class AmoConnector {
    
    public $token, $leads;

    function __construct($clientId, $clientSecret, $authCode, $redirectUri, $subdomain) {
        $this->token = new Token($clientId, $clientSecret, $authCode, $redirectUri, $subdomain);
        $this->leads = new Leads($this->token, $subdomain);
    }
}