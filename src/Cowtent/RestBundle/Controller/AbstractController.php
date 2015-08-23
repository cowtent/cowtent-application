<?php

namespace Cowtent\RestBundle\Controller;

use Cowtent\AccountBundle\Entity\AbstractUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

abstract class AbstractController extends Controller
{
    /**
     * Get the account from the Security Token Storage.
     *
     * @throws UnsupportedUserException
     * @return mixed
     *
     * @see Controller::getUser()
     */
    public function getAccount()
    {
        $user = $this->getUser();

        if (!$user instanceof AbstractUser || !$user->getAccount()) {
            throw new UnsupportedUserException('Missing account for current user.');
        }

        return $user->getAccount();
    }
}
