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
     * @param Request $request
     * @Rest\Post("/add")
     * @return mixed
     */
    public function addAction(Request $request)
    {
        try {
            $account = $this->getAccount();

            $application = new Application();
            $application->setUsername($request->request->get('name'));
            $application->setEnabled(TRUE);
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

    /**
     * @param Request $request
     * @Rest\Post("/resetSalt")
     * @return mixed
     */
    public function resetSaltAction(Request $request)
    {
        try {
            $account = $this->getAccount();

            $repository = $this->getDoctrine()->getRepository('CowtentAccountBundle:Application');
            $criterias  = array(
              'account' => $account,
              'apiKey'  => $request->request->get('apiKey'),
            );

            /** @var Application $application */
            $application = $repository->findOneBy($criterias);
            $application->resetSalt();

            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            return array(
              'apiKey' => $request->request->get('apiKey'),
              'salt' => $application->getSalt(),
            );
        } catch(\Exception $e) {
            return array(get_class($e), $e->getCode(), $e->getMessage());
        }
    }
}
