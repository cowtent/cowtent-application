<?php

namespace Cowtent\RestBundle\Controller;

use Cowtent\AccountBundle\Entity\Account;
use Cowtent\AccountBundle\Entity\Application;
use Cowtent\AccountBundle\Model\ApplicationManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $user = $this->getUser();

        return $user;
    }

    /**
     * @param Request $request
     * @Rest\Post("/create")
     *
     * @return mixed
     */
    public function createAction(Request $request)
    {
        $account = $this->getAccount();

        $application = new Application();
        $application->setUsername($request->request->get('name'));
        $application->setEnabled(true);
        $application->setAccount($account);

        /** @var ApplicationManager $manager */
        $manager = $this->get('cowtent.account.application.manager');
        $manager->updateCanonicalFields($application);
        $manager->generateApiKey($application);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
        } catch (\Exception $e) {
            return array(
                get_class($e),
                $e->getCode(),
                $e->getMessage(),
            );
        }

        return $application;
    }

    /**
     * @param Request $request
     * @Rest\Post("/resetSalt")
     *
     * @return mixed
     */
    public function resetSaltAction(Request $request)
    {
        /** @var Account $account */
        $account = $this->getAccount();

        $repository = $this->getDoctrine()->getRepository('CowtentAccountBundle:Application');
        $criterias  = array(
            'account' => $account,
            'apiKey'  => $request->request->get('api_key'),
        );

        /** @var Application $application */
        $application = $repository->findOneBy($criterias);

        if (!$application) {
            throw new NotFoundHttpException('Application not found.');
        }

        $application->resetSalt();

        $em = $this->getDoctrine()->getManager();
        $em->persist($application);
        $em->flush();

        return array(
            'apiKey' => $request->request->get('api_key'),
            'salt'   => $application->getSalt(),
        );
    }
}
