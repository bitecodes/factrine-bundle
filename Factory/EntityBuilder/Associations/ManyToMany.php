<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder\Associations;

class ManyToMany extends AbstractAssociation
{
    protected function selfReferential()
    {
        $data = $this->params->get($this->association);
        $mapping = $this->meta->getAssociationMapping($this->association);
        $assocClass = $this->meta->getAssociationTargetClass($this->association);

        if($data === false) {
            return;
        } elseif (!$data instanceof $assocClass) {
            if(null === $mapping['mappedBy']) {
                $data[$mapping['inversedBy']] = $this->instance;
                $data[$mapping['fieldName']] = false;
            } else {
                $data[$mapping['mappedBy']] = $this->instance;
                $data[$mapping['fieldName']] = false;
            }
            $entity = $this->entityBuilder->createEntity($assocClass, $data);
        } else {
            $entity = $data;
        }

        $this->accessor->setValue(
            $this->instance,
            $this->association,
            $this->add($entity)
        );
    }

    protected function uniDirectional()
    {
        $data = $this->params->get($this->association);
        $assocClass = $this->meta->getAssociationTargetClass($this->association);

        if(!$data instanceof $assocClass) {
            $entity = $this->entityBuilder->createEntity($assocClass, $data);
        } else {
            $entity = $data;
        }

        $this->accessor->setValue(
            $this->instance,
            $this->association,
            $this->add($entity)
        );
    }

    protected function biDirectional()
    {
        $data = $this->params->get($this->association);
        $mapping = $this->meta->getAssociationMapping($this->association);
        $assocClass = $this->meta->getAssociationTargetClass($this->association);

        if(!$data instanceof $assocClass) {
            $field = (null == $mapping['mappedBy']) ? $mapping['inversedBy'] : $mapping['mappedBy'];
            $data[$field] = $this->instance;
            $entity = $this->entityBuilder->createEntity($assocClass, $data);
        } else {
            $entity = $data;
        }

        $this->accessor->setValue(
            $this->instance,
            $this->association,
            $this->add($entity)
        );
    }

    protected function add($entity)
    {
        $collection = $this->accessor->getValue(
            $this->instance,
            $this->association
        );

        $collection[] = $entity;

        return $collection;
    }
}
