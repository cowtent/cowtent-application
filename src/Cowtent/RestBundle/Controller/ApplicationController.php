<?php

namespace Cowtent\RestBundle\Controller;

use Cowtent\AccountBundle\Entity\Application;
use Cowtent\AccountBundle\Model\ApplicationManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 *
 */
class ApplicationController extends AbstractController
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
    }

    /**
     * @Rest\Post("/add")
     * @return bool
     */
    public function addAction(Request $request)
    {
        try {
            $account = $this->getAccount();

            $application = new Application();
            $application->setUsername($request->request->get('name'));
            $application->setAccount($account);

            /** @var ApplicationManager $manager */
            $manager = $this->get('cowtent.account.application.manager');
            $manager->updateCanonicalFields($application);
            $manager->generateApiKey($application);

            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            return $application;
        } catch(\Exception $e) {
            return array(get_class($e), $e->getCode(), $e->getMessage());
        }
    }
}
