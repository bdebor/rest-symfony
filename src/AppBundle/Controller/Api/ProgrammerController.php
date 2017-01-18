<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Programmer;
use AppBundle\Form\ProgrammerType;
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
//			+" AppBundle\Entity\Programmer id": null // AppBundle\Entity\Programmer ???
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

		return new Response('It worked. Believe me - I\'m an API');
	}


	/**
	 * @Route("/api/programmers/{nickname}", name="api_programmer_show")
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

		$data = array(
			'nickname' => $programmer->getNickname(),
			'avatarNumber' => $programmer->getAvatarNumber(),
			'powerLevel' => $programmer->getPowerLevel(),
			'tagLine' => $programmer->getTagLine(),
		);

		$response = new Response(json_encode($data), 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}