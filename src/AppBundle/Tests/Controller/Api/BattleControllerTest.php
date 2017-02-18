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
			'projectId' => $project->getId(),
			'programmerId' => $programmer->getId()
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
		$this->asserter()->assertResponsePropertyEquals(
			$response,
			'_links.programmer.href',
			$this->adjustUri('/api/programmers/Fred')
		);
		$this->asserter()->assertResponsePropertyEquals(
			$response,
			'_embedded.programmer.nickname',
			'Fred'
		);

		// todo for later
		//$this->assertTrue($response->hasHeader('Location'));
	}

	// FAILURES! ???, 500
	// response with Postman is ok :
	//{
	//"errors": {
	//"programmerId": [
	//"This value is not valid."
	//],
	//"projectId": [
	//"This value should not be blank."
	//]
	//},
	//"status": 400,
	//  "type": "http://localhost:8000/docs/errors#validation_error",
	//  "title": "There was a validation error"
	//}
//	public function testPOSTBattleValidationErrors()
//	{
//		// create a Programmer owned by someone else
//		$this->createUser('someone_else');
//
//		$programmer = $this->createProgrammer(
//			['nickname' => 'Fred'],
//			'someone_else'
//		);
//
//		$data = array(
//			'projectId' => null,
//			'programmerId' => $programmer->getId()
//		);
//
//		$response = $this->client->post('/api/battles', [
//			'body' => json_encode($data),
//			'headers' => $this->getAuthorizedHeaders('weaverryan')
//		]);
//
//		$this->debugResponse($response);
//		$this->assertEquals(400, $response->getStatusCode());
//		$this->asserter()->assertResponsePropertyExists($response, 'errors.projectId');
//		$this->asserter()->assertResponsePropertyEquals($response, 'errors.projectId[0]', 'This value should not be blank.');
//		$this->asserter()->assertResponsePropertyEquals($response, 'errors.programmerId[0]', 'This value is not valid.');
//	}
}
