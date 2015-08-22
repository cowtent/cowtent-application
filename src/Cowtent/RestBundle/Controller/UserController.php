<?php

namespace Cowtent\RestBundle\Controller;

use Cowtent\AccountBundle\Entity\User;
use Cowtent\AccountBundle\Model\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 *
 */
class UserController extends AbstractController
{
    /**
     * @Rest\Get("")
     */
    public function indexAction()
    {
//        throw new \Exception('test');
//        return new Response('coucou');

        $user = $this->getUser();

        return $user;

        return array(
          'foo' => 'bar',
        );
    }

    /**
     * @Rest\Post("/add")
     * @return bool
     */
    public function addAction(Request $request)
    {
        try {
            $account = $this->getAccount();

            $user = new User();
            $user->setUsername($request->request->get('username'));
            $user->setPlainPassword($request->request->get('password'));
            $user->setEmail($request->request->get('email'));
            $user->setAccount($account);

            /** @var UserManager $manager */
            $manager = $this->get('cowtent.account.user.manager');
            $manager->updateCanonicalFields($user);
            $manager->updatePassword($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        } catch(\Exception $e) {
            return array(get_class($e), $e->getCode(), $e->getMessage());
        }
    }
}
