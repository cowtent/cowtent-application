<?php

namespace Cowtent\RemoteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * @Route('/account')
 */
class AccountController extends BaseController
{
    /**
     * @Route('/')
     * @Method({'GET'})
     */
    public function viewAction()
    {
        $data = array(
            'user' => 'user_1',
        );

        return $this->renderJson($data);
    }

    /**
     * @Route('/update')
     * @Method({'POST'})
     */
    public function updateAction()
    {
        return array();
    }

    /**
     * @Route('/login')
     * @Method({'GET', 'POST'})
     * @Security('is_granted('IS_AUTHENTICATED_ANONYMOUSLY')')
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
            throw new UsernameNotFoundException('Account not found');
        } else {
            $token = new UsernamePasswordToken($user, null, 'your_firewall_name', $user->getRoles());
            $this->get('security.context')->setToken($token); //now the user is logged in

            //now dispatch the login event
            $request = $this->get('request');
            $event = new InteractiveLoginEvent($request, $token);
            $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
        }

        return $this->renderJson(array());
    }

    /**
     * @Route('/logout')
     * @Method({'GET', 'POST'})
     */
    public function logoutAction()
    {
        $data = array(
            ''
        );

        return $this->renderJson($data);
    }
}
