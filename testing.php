<?php

use GuzzleHttp\Client;

require __DIR__.'/vendor/autoload.php';

$client = new Client([
    'base_url' => 'http://localhost:8000',
    'defaults' => [
        'exceptions' => false
    ]
]);

$response = $client->post('/api/programmers');
echo $response;
echo "\n\n";die;