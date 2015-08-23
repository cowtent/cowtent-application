<?php

namespace Cowtent\RestBundle\Security\Authentication\Provider;

use Cowtent\AccountBundle\Entity\Application;
use Cowtent\AccountBundle\Entity\User;
use Cowtent\RestBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Util\StringUtils;

class WsseProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @param UserProviderInterface $userProvider
     * @param string                $cacheDir
     */
    public function __construct(UserProviderInterface $userProvider, $cacheDir)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
    }

    /**
     * @param TokenInterface $token
     *
     * @return WsseUserToken
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user instanceof User) {
            $secret = $user->getPassword();
        } elseif ($user instanceof Application) {
            $secret = $user->getApiKey() . '{' . $user->getSalt() . '}';
        }

        if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $secret)) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed.');
    }

    /**
     * This function is specific to Wsse authentication and is only used to help this example
     *
     * For more information specific to the logic here, see
     * https://github.com/symfony/symfony-docs/pull/3134#issuecomment-27699129
     *
     * @param string $digest
     * @param string $nonce
     * @param string $created
     * @param string $secret
     *
     * @return bool
     */
    protected function validateDigest($digest, $nonce, $created, $secret)
    {
        // Check created time is not in the future
        if ((strtotime($created) - 100) > time()) {
            return false;
        }

        // Expire timestamp after 5 minutes
        if (time() - strtotime($created) > 300) {
            return false;
        }

        // Validate that the nonce is *not* used in the last 5 minutes
        // if it has, this could be a replay attack
        if (file_exists($this->cacheDir . '/' . $nonce)) {
            $duration = intval(file_get_contents($this->cacheDir . '/' . $nonce));

            if (($duration + 300) > time()) {
                throw new NonceExpiredException('Previously used nonce detected');
            }
        }

        // If cache directory does not exist we create it
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
        file_put_contents($this->cacheDir . '/' . $nonce, time());

        // Validate Secret
        $expected = base64_encode(sha1(base64_decode($nonce) . $created . $secret, true));

        return StringUtils::equals($expected, $digest);
    }

    /**
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken;
    }
}
