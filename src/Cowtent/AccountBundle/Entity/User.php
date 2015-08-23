<?php

namespace Cowtent\AccountBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_user")
 */
class User extends AbstractUser
{
    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=255)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(name="username_canonical", type="string", length=255, unique=true)
     */
    protected $usernameCanonical;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="email_canonical", type="string", length=255, unique=true)
     */
    protected $emailCanonical;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @var string
     * @ORM\Column(name="confirmation_token", type="string", nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var \DateTime
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="users")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="account_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    public function __construct()
    {
        parent::__construct();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function setUsernameCanonical($usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;

        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;

        return $this;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        /** @var Group $group */
        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * Gets the timestamp that the user requested a password reset.
     *
     * @return null|\DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
        $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * Gets the groups granted to the user.
     *
     * @return Collection
     */
    public function getGroups()
    {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }

    public function getGroupNames()
    {
        $names = array();
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    public function hasGroup($name)
    {
        return in_array($name, $this->getGroupNames());
    }

    public function addGroup(Group $group)
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    public function removeGroup(Group $group)
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }
}
