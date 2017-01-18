<?php
namespace AppBundle\Tests\Controller\Api;

use AppBundle\Test\ApiTestCase;
use GuzzleHttp\Client;

class ProgrammerControllerTest extends ApiTestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->createUser('weaverryan');
	}

    public function testPOST()
    {
		$nickname = 'ObjectOrienter';;
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

	public function testGETProgrammer()
	{
		$this->createProgrammer(array(
			'nickname' => 'UnitTester',
			'avatarNumber' => 3,
		));

		$response = $this->client->get('/api/programmers/UnitTester');
		$this->assertEquals(200, $response->getStatusCode());
		$data = $response->json();
		$this->assertEquals(array(
			'nickname',
			'avatarNumber',
			'powerLevel',
			'tagLine'
		), array_keys($data));
	}
}
