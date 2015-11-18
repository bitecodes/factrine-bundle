<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\Util;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class SchemaReader
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function read()
    {
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        $array = [];

        /** @var ClassMetadata $meta */
        foreach($metadata as $meta) {
            $array[$meta->getName()] = $this->getFieldMappings($meta);
        }

        return $array;
    }

    private function getFieldMappings(ClassMetadata $meta)
    {
        $result = [];

        foreach($meta->fieldMappings as $mapping) {
            $result[$mapping['fieldName']] = 'bla';
        }

        return $result;
    }

}