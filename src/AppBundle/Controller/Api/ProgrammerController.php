<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Programmer;
use AppBundle\Form\ProgrammerType;
use AppBundle\Form\UpdateProgrammerType;
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

class ProgrammerController extends BaseController
{
    /**
     * @Route("/api/programmers")
	 * @Method("POST")
     */
	public function newAction(Request $request)
	{
		$programmer = new Programmer();
		$form = $this->createForm(new ProgrammerType(), $programmer, array('csrf_protection' => false)); // 'csrf_protection' => false doesn't work in src/AppBundle/Form/ProgrammerType.php ???
		$this->processForm($request, $form);

		if (!$form->isValid()) {
			return $this->throwApiProblemValidationException($form);
		}

		$programmer->setUser($this->findUserByUsername('weaverryan'));

		$em = $this->getDoctrine()->getManager();
		$em->persist($programmer);
		$em->flush();

		$response = $this->createApiResponse($programmer, 201);
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

		$response = $this->createApiResponse($programmer, 200);

		return $response;
	}

	/**
	 * @Route("/api/programmers", name="api_programmers_list")
	 * @Method("GET")
	 */
	public function listAction(Request $request)
	{
		$page = $request->query->get('page', 1);

		$qb = $this->getDoctrine()
			->getRepository('AppBundle:Programmer')
			->findAllQueryBuilder();

		$adapter = new DoctrineORMAdapter($qb);
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage(10);
		$pagerfanta->setCurrentPage($page);

		$programmers = [];
		foreach ($pagerfanta->getCurrentPageResults() as $result) {
			$programmers[] = $result;
		}

		$response = $this->createApiResponse([
			'total' => $pagerfanta->getNbResults(),
			'count' => count($programmers),
			'programmers' => $programmers,
		], 200);

		return $response;
	}

	/**
	 * @Route("/api/programmers/{nickname}", name="api_programmers_update")
	 * @Method({"PUT", "PATCH"})
	 */
	public function updateAction($nickname, Request $request)
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
	public function deleteAction($nickname)
	{
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

	private function processForm(Request $request, FormInterface $form)
	{
		$data = json_decode($request->getContent(), true);

		if ($data === null) {
			$apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

			throw new ApiProblemException($apiProblem);
		}

		$clearMissing = $request->getMethod() != 'PATCH';
		$form->submit($data, $clearMissing);
	}

	private function getErrorsFromForm(FormInterface $form)
	{
		$errors = array();
		foreach ($form->getErrors() as $error) {
			$errors[] = $error->getMessage();
		}
		foreach ($form->all() as $childForm) {
			if ($childForm instanceof FormInterface) {
				if ($childErrors = $this->getErrorsFromForm($childForm)) {
					$errors[$childForm->getName()] = $childErrors;
				}
			}
		}
		return $errors;
	}

	private function throwApiProblemValidationException(FormInterface $form){
		$errors = $this->getErrorsFromForm($form);

		$apiProblem = new ApiProblem(
			400,
			ApiProblem::TYPE_VALIDATION_ERROR
		);

		$apiProblem->set('errors', $errors);

		throw new ApiProblemException($apiProblem);
	}
}