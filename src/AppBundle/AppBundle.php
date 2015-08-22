<?php

namespace AppBundle;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function __construct()
    {
        if (!Type::hasType('cms_field')) {
            Type::addType('cms_field', 'AppBundle\Document\Types\Field');
        }
    }

    public function boot()
    {
//        /** @var ManagerRegistry $entityManager */
//        $entityManager = $this->container->get('doctrine_mongodb');
//
//        /* @var $customType \AppBundle\Document\Types\FieldType */
//        $customType = Type::getType('field');
//
//        $customType->setDocumentManager($entityManager);
    }
}
