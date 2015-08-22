<?php

namespace Cowtent\RestBundle\Controller;

use Cowtent\AccountBundle\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractController extends Controller
{
    /**
     * Get the account from the Security Token Storage.
     *
     * @return mixed
     *
     * @see Controller::getUser()
     */
    public function getAccount()
    {
        $user = $this->getUser();

        if ($user instanceof Application) {
            return $user->getAccount();
        }

        return;
    }
}
