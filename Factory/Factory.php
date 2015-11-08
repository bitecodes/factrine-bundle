<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Fludio\DoctrineEntityFactoryBundle\Factory\Util\EntityBuilder;

class Factory
{
    /**
     * @var EntityManager
     */
    protected $om;
    /**
     * @var int
     */
    protected $times = 1;
    /**
     * @var EntityBuilder
     */
    private $entityBuilder;

    /**
     * @param ObjectManager $om
     * @param EntityBuilder $entityBuilder
     */
    public function __construct(ObjectManager $om, EntityBuilder $entityBuilder)
    {
        $this->om = $om;
        $this->entityBuilder = $entityBuilder;
    }

    /**
     * @param $entity
     * @param array $params
     * @param \Closure|null $callback
     * @return array|mixed
     */
    public function make($entity, array $params = [], \Closure $callback = null)
    {
        $result = [];
        $loops = $this->times;
        $this->times = 1;

        for($i = 1; $i <= $loops; $i++) {
            $result[] = $this->entityBuilder->createEntity($entity, $params, $callback);
        }

        return count($result) > 1 ? $result : array_pop($result);
    }

    /**
     * @param $entity
     * @param array $params
     * @param \Closure|null $callback
     * @return array|mixed
     */
    public function create($entity, array $params = [], \Closure $callback = null)
    {
        $result = $this->make($entity, $params, $callback);

        if(is_array($result)) {
            foreach($result as $entity) {
                $this->om->persist($entity);
            }
        } else {
            $this->om->persist($result);
        }

        $this->om->flush();

        return $result;
    }

    /**
     * @param $times
     * @return $this
     */
    public function times($times)
    {
        $this->times = $times;

        return $this;
    }
}