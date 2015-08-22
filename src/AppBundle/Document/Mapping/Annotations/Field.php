<?php

namespace AppBundle\Document\Mapping\Annotations;

use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/** @Annotation */
final class Field extends AbstractField
{
    public $type = 'cms_field';
}
