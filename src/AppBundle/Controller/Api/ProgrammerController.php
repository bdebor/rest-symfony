<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Programmer;
use AppBundle\Form\ProgrammerType;
use AppBundle\Form\UpdateProgrammerType;
use AppBundle\Pagination\PaginatedCollection;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Api\ApiProblem;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Api\ApiProblemException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class ProgrammerController extends BaseController {
	/**
	 * @Route("/api/programmers")
	 * @Method("POST")
	 */
	public function newAction(Request $request) {
		$programmer = new Programmer();
		$form       = $this->createForm(new ProgrammerType(), $programmer, array('csrf_protection' => false)); // 'csrf_protection' => false doesn't work in src/AppBundle/Form/ProgrammerType.php ???
		$this->processForm($request, $form);

		if (!$form->isValid()) {
			return $this->throwApiProblemValidationException($form);
		}

		$programmer->setUser($this->getUser());

		$em = $this->getDoctrine()->getManager();
		$em->persist($programmer);
		$em->flush();

		$response      = $this->createApiResponse($programmer, 201);
		$programmerUrl = $this->generateUrl(
			'api_programmers_show',
			['nickname' => $programmer->getNickname()]
		);
		$response->headers->set('Location', $programmerUrl);

		return $response;
	}

	/**
	 * @Route("/api/programmers/{nickname}", name="api_programmers_show")
	 * @Method("GET")
	 */
	public function showAction($nickname) {
		$programmer = $this->getDoctrine()
			->getRepository('AppBundle:Programmer')
			->findOneByNickname($nickname);

		if (!$programmer) {
			throw $this->createNotFoundException(sprintf(
				'No programmer found with nickname "%s"',
				$nickname
			));
		}

		$response = $this->createApiResponse($programmer, 200);

		return $response;
	}

	/**
	 * @Route("/api/programmers", name="api_programmers_collection")
	 * @Method("GET")
	 */
	public function listAction(Request $request) {
		$filter = $request->query->get('filter');

		$qb = $this->getDoctrine()
			->getRepository('AppBundle:Programmer')
			->findAllQueryBuilder($filter);

		$paginatedCollection = $this->get('pagination_factory')
			->createCollection($qb, $request, 'api_programmers_collection');

		$response = $this->createApiResponse($paginatedCollection, 200);

		return $response;
	}

	/**
	 * @Route("/api/programmers/{nickname}", name="api_programmers_update")
	 * @Method({"PUT", "PATCH"})
	 */
	public function updateAction($nickname, Request $request) {
		$programmer = $this->getDoctrine()
			->getRepository('AppBundle:Programmer')
			->findOneByNickname($nickname);

		if (!$programmer) {
			throw $this->createNotFoundException(sprintf(
				'No programmer found with nickname "%s"',
				$nickname
			));
		}

		$form = $this->createForm(new UpdateProgrammerType(), $programmer, array('csrf_protection' => false)); // 'csrf_protection' => false doesn't work in src/AppBundle/Form/ProgrammerType.php ???
		$this->processForm($request, $form);

		if (!$form->isValid()) {
			return $this->throwApiProblemValidationException($form);
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($programmer);
		$em->flush();

		$response = $this->createApiResponse($programmer, 200);

		return $response;
	}

	/**
	 * @Route("/api/programmers/{nickname}")
	 * @Method("DELETE")
	 */
	public function deleteAction($nickname) {
		$programmer = $this->getDoctrine()
			->getRepository('AppBundle:Programmer')
			->findOneByNickname($nickname);

		if ($programmer) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($programmer);
			$em->flush();
		}

		return new Response(null, 204);
	}

	/**
	 * @Route("/api/programmers/{nickname}/battles", name="api_programmers_battles_list")
	 */
	public function battlesListAction(Programmer $programmer)
	{
		$battles = $this->getDoctrine()->getRepository('AppBundle:Battle')
			->findBy(['programmer' => $programmer]);

		return $this->createApiResponse($battles);
	}
}