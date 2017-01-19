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
		$this->assertStringEndsWith('/api/programmers/'.$nickname, $response->getHeader('Location'));
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
		$this->asserter()->assertResponsePropertiesExist($response, array(
			'nickname',
			'avatarNumber',
			'powerLevel',
			'tagLine'
		));
		$this->asserter()->assertResponsePropertyEquals($response, 'nickname', 'UnitTester');
	}

	public function testGETProgrammersCollection()
	{
		$this->createProgrammer(array(
			'nickname' => 'UnitTester',
			'avatarNumber' => 3,
		));
		$this->createProgrammer(array(
			'nickname' => 'CowboyCoder',
			'avatarNumber' => 5,
		));

		$response = $this->client->get('/api/programmers');
		$this->printLastRequestUrl();
		$this->assertEquals(200, $response->getStatusCode());
		$this->asserter()->assertResponsePropertyIsArray($response, 'programmers');
		$this->asserter()->assertResponsePropertyCount($response, 'programmers', 2);
		$this->asserter()->assertResponsePropertyEquals($response, 'programmers[1].nickname', 'CowboyCoder');
	}
}
