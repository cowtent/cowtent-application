<?php

namespace Cowtent\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="application_homepage")
     */
    public function indexAction()
    {
        return $this->render('CowtentApplicationBundle:Default:index.html.twig');
    }
}
