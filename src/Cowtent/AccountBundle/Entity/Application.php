<?php

namespace Cowtent\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_application")
 */
class Application extends AbstractUser
{
    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="name_canonical", type="string", length=255, unique=true)
     */
    protected $nameCanonical;

    /**
     * @var string
     * @ORM\Column(name="api_key", type="string", length=40, unique=true)
     */
    protected $apiKey;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="applications")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    protected $account;

    public function __construct()
    {
        parent::__construct();

        $this->setPassword('');
    }

    public function getUsername()
    {
        return $this->name;
    }

    public function getUsernameCanonical()
    {
        return $this->nameCanonical;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setUsername($username)
    {
        $this->name = $username;

        return $this;
    }

    public function setUsernameCanonical($usernameCanonical)
    {
        $this->nameCanonical = $usernameCanonical;

        return $this;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        return array('ROLE_API');
    }

    /**
     *
     */
    public function resetSalt()
    {
        $this->salt = $this->generateSalt();
    }
}
