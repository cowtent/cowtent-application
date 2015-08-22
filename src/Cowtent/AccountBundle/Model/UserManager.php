<?php

namespace Cowtent\AccountBundle\Model;

use Cowtent\AccountBundle\Entity\Account;
use Cowtent\AccountBundle\Entity\User;
use Cowtent\AccountBundle\Util\CanonicalizerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var CanonicalizerInterface
     */
    protected $usernameCanonicalizer;

    /**
     * @var CanonicalizerInterface
     */
    protected $emailCanonicalizer;

    /**
     * @param Registry                $doctrine
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     */
    public function __construct(Registry $doctrine, EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer)
    {
        $this->doctrine = $doctrine;
        $this->encoderFactory = $encoderFactory;
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer = $emailCanonicalizer;
    }

    /**
     * @param User $user
     */
    public function updatePassword(User $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    /**
     * @param User $user
     */
    public function updateCanonicalFields(User $user)
    {
        $user->setUsernameCanonical($this->canonicalizeUsername($user->getUsername()));
        $user->setEmailCanonical($this->canonicalizeEmail($user->getEmail()));
    }

    /**
     * @param string $email
     *
     * @return string
     */
    protected function canonicalizeEmail($email)
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }

    /**
     * @param string $username
     *
     * @return string
     */
    protected function canonicalizeUsername($username)
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }

    /**
     * @param User $user
     *
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    protected function getEncoder(User $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }
}
