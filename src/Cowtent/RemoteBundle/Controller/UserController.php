<?php

namespace Cowtent\RemoteBundle\Controller;

use Cowtent\AccountBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 */
class UserController extends BaseController
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function viewAction()
    {
        /** @var User $user */
        if (!$user = $this->getUser()) {
            return new UsernameNotFoundException();
        }

        return $user;

        $data = array(
          'id'            => $user->getId(),
          'username'      => $user->getUsername(),
          'email'         => $user->getEmail(),
          'last_login_at' => self::formatDatetime($user->getLastLogin()),
          'created_at'    => self::formatDatetime($user->getCreated()),
          'updated_at'    => self::formatDatetime($user->getUpdated()),
          'account'       => self::formatAccount($user->getAccount()),
          'groups'        => $user->getGroupNames(),
        );

        $view = $this->view($data, 200)
          ->setTemplate('CowtentRemoteBundle:User:view.html.twig')
          ->setTemplateVar('data')
          ->setTemplateData($data);

        return $this->handleView($view);
    }

    /**
     * @Route("/update")
     * @Method({"POST"})
     */
    public function updateAction()
    {
        return array();
    }

    /**
     * @Route("/login")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function loginAction(Request $request)
    {
        $user = $this->getUser();
        echo '';

        $code = $request->query->get('account');

        /** @var \Cowtent\AccountBundle\Model\Security $service */
        $service = $this->get('cowtent.account.security');
        $account = $service->loadAccountByCode($code);

        echo '';

        if (!$account) {
            throw new UsernameNotFoundException("Account not found");
        } else {
            $token = new UsernamePasswordToken($user, null, 'your_firewall_name', $user->getRoles());
            $this->get('security.context')->setToken($token); //now the user is logged in

            //now dispatch the login event
            $request = $this->get('request');
            $event   = new InteractiveLoginEvent($request, $token);
            $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
        }

        return $this->renderJson($data);
    }

    /**
     * @Route("/logout")
     * @Method({"GET", "POST"})
     */
    public function logoutAction()
    {
        $data = array(
          '',
        );

        return $this->renderJson($data);
    }
}
