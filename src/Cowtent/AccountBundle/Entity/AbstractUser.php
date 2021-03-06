<?php

namespace Cowtent\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_user_common")
 * @ORM\Entity(repositoryClass="Cowtent\AccountBundle\Entity\UserRepository")
 * @ORM\InheritanceType("JOINED") // SINGLE_TABLE
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "application" = "Application"})
 */
abstract class AbstractUser implements AdvancedUserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * The salt to use for hashing
     *
     * @var string
     * @ORM\Column(name="salt", type="string")
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @ORM\Column(name="password", type="string")
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="account_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var boolean
     * @ORM\Column(name="locked", type="boolean")
     */
    protected $locked;

    /**
     * @var boolean
     * @ORM\Column(name="expired", type="boolean")
     */
    protected $expired;

    /**
     * @var \DateTime
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     */
    protected $expiresAt;

    /**
     * @var array
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @var boolean
     * @ORM\Column(name="credentials_expired", type="boolean")
     */
    protected $credentialsExpired;

    /**
     * @var \DateTime
     * @ORM\Column(name="credentials_expire_at", type="datetime", nullable=true)
     */
    protected $credentialsExpireAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var Account
     */
    protected $account;

    /**
     *
     */
    public function __construct()
    {
        $this->salt = $this->generateSalt();
        $this->enabled = false;
        $this->locked = false;
        $this->expired = false;
        $this->roles = array();
        $this->credentialsExpired = false;
    }

    /**
     * @param string $role
     *
     * @return self
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Returns the user unique id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Gets the encrypted password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Gets the last login time.
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    abstract public function getRoles();

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        if (true === $this->expired) {
            return false;
        }

        if (null !== $this->expiresAt && $this->expiresAt->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        if (true === $this->credentialsExpired) {
            return false;
        }

        if (null !== $this->credentialsExpireAt && $this->credentialsExpireAt->getTimestamp() < time()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsExpired()
    {
        return !$this->isCredentialsNonExpired();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return !$this->isAccountNonExpired();
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return !$this->isAccountNonLocked();
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    /**
     * @param \Cowtent\AccountBundle\Entity\AbstractUser|null $user
     *
     * @return bool
     */
    public function isUser(AbstractUser $user = null)
    {
        return null !== $user && $this->getId() === $user->getId();
    }

    /**
     * @param string $role
     *
     * @return self
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return self
     */
    public function setCredentialsExpireAt(\DateTime $date)
    {
        $this->credentialsExpireAt = $date;

        return $this;
    }

    /**
     * @param boolean $boolean
     *
     * @return self
     */
    public function setCredentialsExpired($boolean)
    {
        $this->credentialsExpired = $boolean;

        return $this;
    }

    /**
     * @param boolean $boolean
     *
     * @return self
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (Boolean) $boolean;

        return $this;
    }

    /**
     * Sets this user to expired.
     *
     * @param Boolean $boolean
     *
     * @return self
     */
    public function setExpired($boolean)
    {
        $this->expired = (Boolean) $boolean;

        return $this;
    }

    /**
     * @param \DateTime $date
     *
     * @return self
     */
    public function setExpiresAt(\DateTime $date)
    {
        $this->expiresAt = $date;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param boolean $boolean
     *
     * @return self
     */
    public function setSuperAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }

        return $this;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * @param \DateTime $time
     *
     * @return self
     */
    public function setLastLogin(\DateTime $time)
    {
        $this->lastLogin = $time;

        return $this;
    }

    /**
     * @param boolean $boolean
     *
     * @return self
     */
    public function setLocked($boolean)
    {
        $this->locked = $boolean;

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * Set account
     *
     * @param Account|null $account
     * @return self
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getUsername();
    }

    /**
     * @return string
     */
    protected function generateSalt()
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}
