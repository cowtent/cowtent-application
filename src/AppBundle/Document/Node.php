<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Document\Mapping\Annotations as CustomTypes;

/**
 * @MongoDB\Document(collection="articles")
 * @Gedmo\Loggable
 */
class Node
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
    private $createdAt;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @MongoDB\Integer
     * @var integer
     */
    private $status;

    /**
     * @MongoDB\ObjectId
     * @var string
     */
    protected $owner;

    /**
     * @MongoDB\String
     * @var string
     * @Gedmo\Versioned
     */
    protected $title;

    /**
     * @MongoDB\Collection
     * @var array
     */
    protected $fields;

    /**
     * @CustomTypes\Field
     * @var array
     */
    protected $content;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Set owner
     *
     * @param string $owner
     * @return self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get owner
     *
     * @return string $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set fields
     *
     * @param collection $fields
     * @return self
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Get fields
     *
     * @return collection $fields
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set content
     *
     * @param cms_field $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return cms_field $content
     */
    public function getContent()
    {
        return $this->content;
    }
}
