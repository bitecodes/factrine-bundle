<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\Util;

use Doctrine\Common\Persistence\Proxy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PersistanceHelper
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->accessor = new PropertyAccessor();
    }

    public function persist($entity)
    {
        $this->em->clear();

        $this->em->persist($entity);
        $this->persistAllAssociations($entity);

        $this->em->flush();
    }

    private function persistAllAssociations($entity)
    {
        $meta = $this->em->getClassMetadata(get_class($entity));

        foreach($meta->getAssociationMappings() as $mapping) {
            $child = $this->accessor->getValue($entity, $mapping['fieldName']);

            if($this->isCollection($mapping)) {
                $this->persistCollection($child);
            } else {
                $this->persistEntity($child);
            }
        }

    }

    /**
     * @param $mapping
     * @return bool
     */
    private function isCollection($mapping)
    {
        return in_array($mapping['type'], [ClassMetadataInfo::ONE_TO_MANY, ClassMetadataInfo::MANY_TO_MANY]);
    }

    /**
     * @param $entity
     */
    private function persistEntity($entity)
    {
        if ($this->isEntity($entity)) {
//            var_dump($this->em->contains($entity), get_class($entity));
            if(!$this->em->contains($entity)) {
                $this->em->persist($entity);
                $this->persistAllAssociations($entity);
            }
        }
    }

    /**
     * @param $collection
     */
    private function persistCollection($collection)
    {
        foreach ($collection as $entity) {
            $this->persistEntity($entity);
        }
    }

    /**
     * @param string|object $class
     *
     * @return boolean
     */
    public function isEntity($class)
    {
        if (is_object($class)) {
            $class = ($class instanceof Proxy)
                ? get_parent_class($class)
                : get_class($class);
        } else {
            return false;
        }

        return ! $this->em->getMetadataFactory()->isTransient($class);
    }
}
