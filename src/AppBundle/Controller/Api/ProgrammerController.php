<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Programmer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProgrammerController extends Controller
{
    /**
     * @Route("/api/programmers")
     */
    public function newAction()
    {
		return new Response('Let\'s do this!');
    }
}