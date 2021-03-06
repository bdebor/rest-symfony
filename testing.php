<?php

use GuzzleHttp\Client;

require __DIR__.'/vendor/autoload.php';

$client = new Client([
    'base_url' => 'http://localhost:8000',
    'defaults' => [
        'exceptions' => false
    ]
]);

$nickname = 'ObjectOrienter'.rand(0, 999);
$data = array(
	'nickname' => $nickname,
	'avatarNumber' => 5,
	'tagLine' => 'a test dev!'
);
$response = $client->post('/api/programmers', [
	'body' => json_encode($data)
]);

echo $response;
echo "\n\n";

//$response = $client->get('/api/programmers/'.$nickname);
$programmerUrl = $response->getHeader('Location');
$response = $client->get($programmerUrl);

echo $response;
echo "\n\n";