<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document
 * @Gedmo\Loggable
 */
class Document
{
    /**
     * @MongoDB\Id
     * @var string
     */
    private $id;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     * @var \DateTime
     */
    private $created;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable
     * @var \DateTime
     */
    private $updated;

    /**
     * @MongoDB\Integer
     * @var integer
     */
    private $status;

    /**
     * @MongoDB\String
     * @var string
     */
    private $branch;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime $updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return integer $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set branch
     *
     * @param string $branch
     * @return self
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * Get branch
     *
     * @return string $branch
     */
    public function getBranch()
    {
        return $this->branch;
    }
}
