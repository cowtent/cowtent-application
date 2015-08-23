<?php

namespace Cowtent\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        return $this->render('CowtentAccountBundle:Security:login.html.twig');
    }

    public function loginCheckAction()
    {
    }
}
