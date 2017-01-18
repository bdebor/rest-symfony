<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Programmer;
use AppBundle\Form\ProgrammerType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProgrammerController extends BaseController
{
    /**
     * @Route("/api/programmers")
	 * @Method("POST")
     */
	public function newAction(Request $request)
	{
//		$data = json_decode($request->getContent(), true);
//		$programmer = new Programmer($data['nickname'], $data['avatarNumber']);
//		$programmer->setTagLine($data['tagLine']);
//		$programmer->setUser($this->findUserByUsername('weaverryan'));
//
//		$em = $this->getDoctrine()->getManager();
//		$em->persist($programmer);
//		$em->flush();
//
//		$response = new Response('It worked. Believe me - I\'m an API', 201);
//		$response->headers->set('Location', '/some/programmer/url');
//
//		return $response;

//		$data = json_decode($request->getContent(), true);
//		$programmer = new Programmer();
//
//		$form = $this->createForm(new ProgrammerType(), $programmer);
//		$form->submit($data);
//
//		$programmer->setUser($this->findUserByUsername('weaverryan'));
//
//		return new Response(serialize($programmer));
//		Programmer {#711 ?
//			-id: null
//			-nickname: null
//			-avatarNumber: null
//			-tagLine: null
//			-powerLevel: 0
//			+" AppBundle\Entity\Programmer id": null // ??? error, AppBundle\Entity\Programmer
//			+" AppBundle\Entity\Programmer nickname": "ObjectOrienter419"
//			+" AppBundle\Entity\Programmer avatarNumber": 5
//			+" AppBundle\Entity\Programmer tagLine": "a test dev!"
//			+" AppBundle\Entity\Programmer powerLevel": null
//		}
//
//		$em = $this->getDoctrine()->getManager();
//		$em->persist($programmer);
//		$em->flush();
//
//		return new Response('It worked. Believe me - I\'m an API');

		$data = json_decode($request->getContent(), true);
		$programmer = new Programmer();
		$programmer->setNickname($data['nickname']);
		$programmer->setAvatarNumber($data['avatarNumber']);
		$programmer->setTagLine($data['tagLine']);
		$programmer->setUser($this->findUserByUsername('weaverryan'));

		$em = $this->getDoctrine()->getManager();
		$em->persist($programmer);
		$em->flush();

		$data = $this->serializeProgrammer($programmer);
		$response = new JsonResponse($data, 201);
		$programmerUrl = $this->generateUrl(
			'api_programmers_show',
			['nickname' => $programmer->getNickname()]
		);
		$response->headers->set('Location', $programmerUrl); // ??? 500 Error
//		$response->headers->set('Location', '$programmerUrl');

		return $response;
	}


	/**
	 * @Route("/api/programmers/{nickname}", name="api_programmers_show")
	 * @Method("GET")
	 */
	public function showAction($nickname)
	{
		$programmer = $this->getDoctrine()
			->getRepository('AppBundle:Programmer')
			->findOneByNickname($nickname);

		if (!$programmer) {
			throw $this->createNotFoundException(sprintf(
				'No programmer found with nickname "%s"',
				$nickname
			));
		}

		$data = $this->serializeProgrammer($programmer);

		$response = new JsonResponse($data, 200);

		return $response;
	}

	/**
	 * @Route("/api/programmers", name="api_programmers_list")
	 * @Method("GET")
	 */
	public function listAction()
	{
		$programmers = $this->getDoctrine()
			->getRepository('AppBundle:Programmer')
			->findAll();

		$data = ['programmers' => []];
		foreach($programmers as $programmer){
			$data['programmers'][] = $this->serializeProgrammer($programmer);
		}

		$response = new JsonResponse($data, 200);

		return $response;
	}

	private function serializeProgrammer(Programmer $programmer)
	{
		return  array(
			'nickname' => $programmer->getNickname(),
			'avatarNumber' => $programmer->getAvatarNumber(),
			'powerLevel' => $programmer->getPowerLevel(),
			'tagLine' => $programmer->getTagLine(),
		);
	}
}