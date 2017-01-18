<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\BaseController;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction()
    {
        $var = 'O:27:"AppBundle\Entity\Programmer":5:{s:31:" AppBundle\Entity\Programmer id";N;s:37:" AppBundle\Entity\Programmer nickname";s:17:"ObjectOrienter419";s:41:" AppBundle\Entity\Programmer avatarNumber";i:5;s:36:" AppBundle\Entity\Programmer tagLine";s:11:"a test dev!";s:39:" AppBundle\Entity\Programmer powerLevel";N;}';
        dump(unserialize($var));

        return $this->render('homepage.twig');
    }
}
