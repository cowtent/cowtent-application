<?php

namespace Cowtent\AccountBundle\Model;

use Cowtent\AccountBundle\Entity\Application;
use Cowtent\AccountBundle\Util\CanonicalizerInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Util\SecureRandomInterface;

class ApplicationManager
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var CanonicalizerInterface
     */
    protected $nameCanonicalizer;

    /**
     * @var SecureRandomInterface
     */
    protected $secureRandom;

    /**
     * @param Registry                $doctrine
     * @param SecureRandomInterface   $secureRandom
     * @param CanonicalizerInterface  $nameCanonicalizer
     */
    public function __construct(
        Registry $doctrine,
        SecureRandomInterface $secureRandom,
        CanonicalizerInterface $nameCanonicalizer
    ) {
        $this->doctrine = $doctrine;
        $this->secureRandom = $secureRandom;
        $this->nameCanonicalizer = $nameCanonicalizer;
    }

    /**
     * @param Application $application
     */
    public function updateCanonicalFields(Application $application)
    {
        $application->setUsernameCanonical($this->canonicalizeName($application->getUsername()));
    }

    /**
     * @param Application $application
     */
    public function generateApiKey(Application $application)
    {
        $application->setApiKey($this->generateUuid());
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function canonicalizeName($name)
    {
        return $this->nameCanonicalizer->canonicalize($name);
    }

    /**
     * @return string
     */
    protected function generateUuid()
    {
        $conn = $this->doctrine->getConnection();
        $sql = 'SELECT ' . $conn->getDatabasePlatform()->getGuidExpression();

        return $conn->query($sql)->fetchColumn(0);
    }
}
