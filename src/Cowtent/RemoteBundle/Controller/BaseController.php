<?php

namespace Cowtent\RemoteBundle\Controller;

use Cowtent\AccountBundle\Entity\Account;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    /**
     * Returns a rendered view.
     *
     * @param array $data
     * @param int   $status
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function renderJson(array $data = array(), $status = 200, $headers = array())
    {
        $response = new JsonResponse($data, $status, $headers);

        return $response;
    }

    /**
     * @param \DateTime $date
     *
     * @return array
     */
    public function formatDatetime($date)
    {
        if ($date) {
            $value = array(
                'date'      => $date->format('c'),
                'timestamp' => $date->getTimestamp(),
                'timezone'  => $date->getTimezone()->getName(),
            );

            return $value;
        }

        return null;
    }

    /**
     * @param Account $account
     *
     * @return array
     */
    public function formatAccount($account)
    {
        if ($account) {
            $value = array(
                'id'   => $account->getId(),
                'code' => $account->getCode(),
                'name' => $account->getName(),
                'created' => self::formatDatetime($account->getCreated()),
                'updated' => self::formatDatetime($account->getUpdated()),
            );

            return $value;
        }

        return null;
    }
}
