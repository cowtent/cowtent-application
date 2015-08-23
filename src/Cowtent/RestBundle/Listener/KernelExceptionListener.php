<?php

namespace Cowtent\RestBundle\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class KernelExceptionListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $code = $exception->getCode();

        $debug = array(
            'class'   => get_class($exception),
            'code'    => $code,
            'message' => $exception->getMessage(),
        );
        $this->logger->error(print_r($debug, true));

        // HttpExceptionInterface est un type d'exception spécial qui
        // contient le code statut et les détails de l'entête
        if ($exception instanceof NotFoundHttpException) {
            $data = array(
                'error' => array(
                'code' => ($code ? $code : -3),
                'message' => $exception->getMessage(),
                ),
            );

            $response = new JsonResponse($data);
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $response->headers->set('Content-Type', 'application/json');
        } elseif ($exception instanceof HttpExceptionInterface) {
            $data = array(
                'error' => array(
                'code' => ($code ? $code : -2),
                'message' => $exception->getMessage(),
                ),
            );

            $response = new JsonResponse($data);
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $data = array(
                'error' => array(
                'code' => ($code ? $code : -1),
                'message' => 'Internal Server Error / ' . $exception->getMessage(),
                ),
            );

            $response = new JsonResponse($data);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // envoie notre objet réponse modifié à l'évènement
        $event->setResponse($response);
    }
}
