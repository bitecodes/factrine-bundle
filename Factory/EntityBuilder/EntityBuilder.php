<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder;

use Dflydev\DotAccessData\Data;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder\Associations\ManyToMany;
use Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder\Associations\ManyToOne;
use Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder\Associations\OneToMany;
use Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder\Associations\OneToOne;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class EntityBuilder
 * @package Fludio\FactoryBundle\Factory\Util
 */
class EntityBuilder
{
    /**
     * @var ObjectManager
     */
    protected $om;
    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @param EntityManager $om
     */
    public function __construct(EntityManager $om)
    {
        $this->om = $om;
        $this->accessor = new PropertyAccessor();
    }

    /**
     * @param $entity
     * @param array $params
     * @param null $callback
     * @return array|mixed|object
     * @throws Exception
     */
    public function createEntity($entity, $params = [], $callback = null)
    {
        $params = $this->prepareParams($params);

        // check if entity exists
        if(!class_exists($entity)) {
            throw new Exception('Class not found');
        }

        $meta = $this->om->getClassMetadata($entity);

        $instance = new $entity;

        foreach($meta->getFieldNames() as $field) {
            if(in_array($field, $meta->getIdentifierFieldNames())) {
                continue;
            }

            if($params->get($field)) {
                $this->accessor->setValue($instance, $field, $params->get($field));
            }
        }

        foreach($meta->getAssociationNames() as $association) {
            $this->handleAssociation($association, $meta, $instance, $params);
        }

        if(is_callable($callback)) {
            $instance = $callback($instance);
        }

        return $instance;
    }

    /**
     * @param $params
     * @return Data
     */
    private function prepareParams($params)
    {
        $params = is_array($params) ? $params : [];

        $data = new Data();

        foreach($params as $path => $value) {
            $data->set($path, $value);
        }

        return $data;
    }

    /**
     * @param $association
     * @param ClassMetadataInfo $meta
     * @param $instance
     * @param Data $params
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    private function handleAssociation($association, ClassMetadataInfo $meta, $instance, Data $params)
    {
        $data = $params->get($association);
        if(is_array($data) && isset($data[0])) {
            foreach($data as $index => $set) {
                false;
                $mapping = $meta->getAssociationMapping($association);

                switch($mapping['type']) {
                    case ClassMetadataInfo::ONE_TO_ONE:
                        $assoc = new OneToOne($this->accessor, $this);
                        break;
                    case ClassMetadataInfo::MANY_TO_ONE:
                        $assoc = new ManyToOne($this->accessor, $this);
                        break;
                    case ClassMetadataInfo::ONE_TO_MANY:
                        $assoc = new OneToMany($this->accessor, $this);
                        break;
                    case ClassMetadataInfo::MANY_TO_MANY:
                        $assoc = new ManyToMany($this->accessor, $this);
                        break;
                }
                $assoc->handle($association, $meta, $instance, new Data([$association => $set]));
            }
        } else {
            false;
            $mapping = $meta->getAssociationMapping($association);

            switch($mapping['type']) {
                case ClassMetadataInfo::ONE_TO_ONE:
                    $assoc = new OneToOne($this->accessor, $this);
                    break;
                case ClassMetadataInfo::MANY_TO_ONE:
                    $assoc = new ManyToOne($this->accessor, $this);
                    break;
                case ClassMetadataInfo::ONE_TO_MANY:
                    $assoc = new OneToMany($this->accessor, $this);
                    break;
                case ClassMetadataInfo::MANY_TO_MANY:
                    $assoc = new ManyToMany($this->accessor, $this);
                    break;
            }
            $assoc->handle($association, $meta, $instance, $params);
        }
    }
}
