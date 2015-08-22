<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;
use AppBundle\Document\Mapping\Annotations as CustomTypes;

/**
 * @MongoDB\Document(collection="fields")
 * @Gedmo\Loggable
 */
class Field
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
     * @MongoDB\String
     * @var string
     */
    private $type;

    /**
     * @MongoDB\String
     * @var string
     */
    private $form;

    /**
     * @CustomTypes\Field
     * @var array
     */
    private $content;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
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
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
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
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set form
     *
     * @param string $form
     * @return self
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Get form
     *
     * @return string $form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set values
     *
     * @param object $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get values
     *
     * @return object $values
     */
    public function getContent()
    {
        return $this->content;
    }
}
