<?php

namespace Cowtent\RestBundle\Security\Firewall;

use Cowtent\RestBundle\Security\Authentication\Token\WsseUserToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class WsseListener implements ListenerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param TokenStorageInterface          $tokenStorage
     * @param AuthenticationManagerInterface $authenticationManager
     * @param LoggerInterface                $logger
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        LoggerInterface $logger
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->logger = $logger;
    }

    /**
     * @param GetResponseEvent $event
     * @throws BadRequestHttpException
     * @throws AccessDeniedHttpException
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $wsseRegex = '/UsernameToken Username="([^"]+)", ' .
            'PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';

        if (!$request->headers->has('x-wsse') ||
            1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {
            throw new BadRequestHttpException('Missing WSSE headers.');
        }

        $token = new WsseUserToken();
        $token->setUser($matches[1]);

        $token->digest   = $matches[2];
        $token->nonce    = $matches[3];
        $token->created  = $matches[4];

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);
        } catch (AuthenticationException $failed) {
            $failedMessage = 'WSSE Login failed. Why ? ' . $failed->getMessage();
            $this->logger->error($failedMessage);

            throw new AccessDeniedHttpException('WSSE Login failed.');
        }
    }
}
