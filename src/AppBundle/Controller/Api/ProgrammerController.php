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
     */
	public function newAction(Request $request)
	{
		$data = json_decode($request->getContent(), true);
		$programmer = new Programmer($data['nickname'], $data['avatarNumber']);
		$programmer->setTagLine($data['tagLine']);
		$programmer->setUser($this->findUserByUsername('weaverryan'));

		$em = $this->getDoctrine()->getManager();
		$em->persist($programmer);
		$em->flush();

		$response = new Response('It worked. Believe me - I\'m an API', 201);
		$response->headers->set('Location', '/some/programmer/url');

		return $response;

//		$data = json_decode($request->getContent(), true);
//		$programmer = new Programmer();
//		$form = $this->createForm(new ProgrammerType(), $programmer);
//		$form->submit($data);
//		$programmer->setUser($this->findUserByUsername('weaverryan'));
//
//		//return new Response(serialize($programmer));
//		$em = $this->getDoctrine()->getManager();
//		$em->persist($programmer);
//		$em->flush();
//
//		return new Response('It worked. Believe me - I\'m an API');
	}
}