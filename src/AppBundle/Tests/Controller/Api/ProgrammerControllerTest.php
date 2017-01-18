<?php
namespace AppBundle\Tests\Controller\Api;

use AppBundle\Test\ApiTestCase;
use GuzzleHttp\Client;

class ProgrammerControllerTest extends ApiTestCase
{
    public function testPOST()
    {
		$nickname = 'ObjectOrienter'.rand(0, 999);;
		$data = array(
			'nickname' => $nickname,
			'avatarNumber' => 5,
			'tagLine' => 'a test dev!'
		);

		$response = $this->client->post('/api/programmers', [
			'body' => json_encode($data)
		]);

		$this->assertEquals(201, $response->getStatusCode());
		$this->assertEquals('/api/programmers/'.$nickname, $response->getHeader('Location'));
		$finishedData = json_decode($response->getBody(true), true);
		$this->assertArrayHasKey('nickname', $finishedData);
		$this->assertEquals($nickname, $finishedData['nickname']);
	}
}
