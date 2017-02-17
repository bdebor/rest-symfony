<?php
namespace AppBundle\Tests\Controller\Api;

use AppBundle\Test\ApiTestCase;

class BattleControllerTest extends ApiTestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->createUser('weaverryan');
	}

	public function testPOSTCreateBattle()
	{
		$project = $this->createProject('my_project');
		$programmer = $this->createProgrammer(
			['nickname' => 'Fred'],
			'weaverryan'
		);

		$data = array(
			'project' => $project->getId(),
			'programmer' => $programmer->getId()
		);

		$response = $this->client->post('/api/battles', [
			'body' => json_encode($data),
			'headers' => $this->getAuthorizedHeaders('weaverryan')
		]);

		$this->debugResponse($response);
		$this->assertEquals(201, $response->getStatusCode());
		$this->asserter()->assertResponsePropertyExists($response, 'didProgrammerWin');
		$this->asserter()->assertResponsePropertyEquals($response, 'project', $project->getId());
		$this->asserter()->assertResponsePropertyEquals($response, 'programmer', 'Fred');
		// todo for later
		//$this->assertTrue($response->hasHeader('Location'));
	}
}
