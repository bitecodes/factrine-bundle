<?php

namespace Fludio\FactrineBundle\Factory;

use Fludio\FactrineBundle\Factory\EntityBuilder\EntityBuilder;
use Fludio\FactrineBundle\Factory\Util\PersistenceHelper;
use Fludio\FactrineBundle\Factory\Util\ValueFactory;

class Factory
{
    /**
     * @var int
     */
    protected $times = 1;
    /**
     * @var EntityBuilder
     */
    private $entityBuilder;
    /**
     * @var ValueFactory
     */
    private $valueFactory;
    /**
     * @var PersistenceHelper
     */
    private $persistenceHelper;

    /**
     * @param EntityBuilder $entityBuilder
     * @param ValueFactory $valueFactory
     * @param PersistenceHelper $persistenceHelper
     */
    public function __construct(
        EntityBuilder $entityBuilder,
        ValueFactory $valueFactory,
        PersistenceHelper $persistenceHelper
    )
    {
        $this->entityBuilder = $entityBuilder;
        $this->valueFactory = $valueFactory;
        $this->persistenceHelper = $persistenceHelper;
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
        $isSingular = $this->times == 1;

        $dataSet = $this->values($entity, $params);

        if($isSingular) {
            $dataSet = [$dataSet];
        }

        foreach($dataSet as $data) {
            $result[] = $this->entityBuilder->createEntity($entity, $data, $callback);
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
                $this->persistenceHelper->persist($entity);
            }
        } else {
            $this->persistenceHelper->persist($result);
        }

        return $result;
    }

    /**
     * @param $entity
     * @param array $params
     * @return array
     */
    public function values($entity, array $params = [])
    {
        $result = [];
        $loops = $this->times;
        $this->times = 1;

        for($i = 1; $i <= $loops; $i++) {
            $fakeValues = $this->valueFactory->getAllValues($entity);

            $result[] = array_merge($fakeValues, $params);
        }

        return count($result) > 1 ? $result : array_pop($result);
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
