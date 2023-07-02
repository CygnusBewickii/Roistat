<?php
include __DIR__ . './AmoCRM/AmoConnector.php';

$config = require __DIR__ . './config.php';

$clientId = $config['client_id'];
$clientSecret = $config['client_secret'];
$code = $config['code'];
$redirectUri = $config['redirect_uri'];
$subdomain = $config['subdomain'];


$apiClient = new AmoConnector($clientId, $clientSecret, $code, $redirectUri, $subdomain);



