<?php


namespace Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder\Associations;

class ManyToOne extends AbstractAssociation
{
    protected function selfReferential()
    {
        $data = $this->params->get($this->association);
        $mapping = $this->meta->getAssociationMapping($this->association);
        $assocClass = $this->meta->getAssociationTargetClass($this->association);

        // Check if data is already a concrete entity
        if(!$data instanceof $assocClass) {
            $field = is_null($mapping['mappedBy']) ? $mapping['inversedBy'] : $mapping['mappedBy'];
            $data[$mapping['fieldName']] = $this->instance;
            $data[$field] = $this->instance;
            $entity = $this->entityBuilder->createEntity($assocClass, $data);
        } else {
            $entity = $data;
        }

        // Create a new entity
        $this->accessor->setValue(
            $this->instance,
            $this->association,
            $entity
        );
    }

    protected function uniDirectional()
    {
        $data = $this->params->get($this->association);
        $assocClass = $this->meta->getAssociationTargetClass($this->association);

        // Only create entity, if not set in params
        if(!$data instanceof $assocClass) {
            $assocClass = $this->meta->getAssociationTargetClass($this->association);
            $entity = $this->entityBuilder->createEntity($assocClass, $data);
        } else {
            $entity = $data;
        }

        $this->accessor->setValue(
            $this->instance,
            $this->association,
            $entity
        );
    }

    protected function biDirectional()
    {
        $data = $this->params->get($this->association);
        $mapping = $this->meta->getAssociationMapping($this->association);
        $assocClass = $this->meta->getAssociationTargetClass($this->association);

        if(!$data instanceof $assocClass) {
            $field = is_null($mapping['mappedBy']) ? $mapping['inversedBy'] : $mapping['mappedBy'];
            $data[$field] = $this->instance;
            $entity = $this->entityBuilder->createEntity($assocClass, $data);
        } else {
            $entity = $data;
        }

        $this->accessor->setValue(
            $this->instance,
            $this->association,
            $entity
        );
    }
}
