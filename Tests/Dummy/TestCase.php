<?php

namespace BiteCodes\FactrineBundle\Tests\Dummy;

use BiteCodes\FactrineBundle\Factory\DataProvider\FakerDataProvider;
use BiteCodes\FactrineBundle\Factory\EntityBuilder\EntityBuilder;
use BiteCodes\FactrineBundle\Factory\Factory;
use BiteCodes\FactrineBundle\Factory\ConfigProvider\ConfigLoader;
use BiteCodes\FactrineBundle\Factory\ConfigProvider\YamlConfigProvider;
use BiteCodes\FactrineBundle\Factory\Util\PersistenceHelper;
use BiteCodes\FactrineBundle\Factory\Util\ValueFactory;
use Doctrine\ORM\EntityManager;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestDb
     */
    protected $testDb;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Factory
     */
    protected $factory;

    public function setUp()
    {
        parent::setUp();

        $here = dirname(__FILE__);

        $this->testDb = new TestDb(
            $here . '/TestEntity',
            $here . '/TestProxy',
            'BiteCodes\FactrineBundle\Tests\Dummy\TestEntity'
        );

        $this->em = $this->testDb->createEntityManager();

        $configDir = __DIR__ . '/Config';

        $loader = new ConfigLoader([$configDir]);

        $configProvider = new YamlConfigProvider($loader);
        $fakerDataProvider = new FakerDataProvider();

        $valueFactory = new ValueFactory($configProvider, [$fakerDataProvider]);

        $persistanceHelper = new PersistenceHelper($this->em);

        $entityBuilder = new EntityBuilder($this->em);
        $this->factory = new Factory($entityBuilder, $valueFactory, $persistanceHelper);
    }

    protected function seeInDatabase($entity, $criteria)
    {
        $count = $this->getDatabaseCount($entity, $criteria);

        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes [%s].', $entity, json_encode($criteria)
        ));

        return $this;
    }

    protected function seeNotInDatabase($entity, $criteria)
    {
        $count = $this->getDatabaseCount($entity, $criteria);

        $this->assertEquals(0, $count, sprintf(
            'Found row in database table [%s] that matched attributes [%s].', $entity, json_encode($criteria)
        ));

        return $this;
    }

    protected function getDatabaseCount($entity, $criteria)
    {
        $qb = $this->em
            ->createQueryBuilder()
            ->select('COUNT(e)')
            ->from($entity, 'e');

        foreach($criteria as $field => $value) {
            $qb->andWhere("e.{$field} = :{$field}")->setParameter($field, $value);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}