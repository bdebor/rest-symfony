<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\BaseController;

class ProgrammerController extends BaseController
{
    /**
     * @Route("/api/battles")
	 * @Method("POST")
     */
	public function newAction(Request $request)
	{

	}
}