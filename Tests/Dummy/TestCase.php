<?php

namespace Fludio\DoctrineEntityFactoryBundle\Tests\Dummy;

use Fludio\DoctrineEntityFactoryBundle\Factory\DataProvider\FakerDataProvider;
use Fludio\DoctrineEntityFactoryBundle\Factory\Factory;
use Fludio\DoctrineEntityFactoryBundle\Factory\ConfigProvider\ConfigLoader;
use Fludio\DoctrineEntityFactoryBundle\Factory\ConfigProvider\YamlConfigProvider;
use Fludio\DoctrineEntityFactoryBundle\Factory\Util\EntityBuilder;
use Fludio\DoctrineEntityFactoryBundle\Factory\Util\ValueFactory;
use PHPUnit_Framework_Error;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\Yaml\Parser;

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
            'Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity'
        );

        $this->em = $this->testDb->createEntityManager();

        $configDir = __DIR__ . '/Config';

        $loader = new ConfigLoader([$configDir]);

        $configProvider = new YamlConfigProvider($loader);
        $fakerDataProvider = new FakerDataProvider();

        $valueFactory = new ValueFactory($configProvider, [$fakerDataProvider]);

        $entityBuilder = new \Fludio\DoctrineEntityFactoryBundle\Factory\EntityBuilder\EntityBuilder($this->em);
        $this->factory = new Factory($this->em, $entityBuilder, $valueFactory);
    }

    /**
     * @return Exception
     */
    protected function assertThrows($func, $exceptionType = '\Exception')
    {
        try {
            $func();
        } catch (Exception $e) {
        }
        if (!isset($e)) {
            $this->fail("Expected $exceptionType but nothing was thrown");
        }
        if ($e instanceof PHPUnit_Framework_Error) {
            $this->fail('Expected exception but got a PHP error: ' . $e->getMessage());
        }
        if (!($e instanceof $exceptionType)) {
            $this->fail("Excpected $exceptionType but " . get_class($e) . " was thrown");
        }
        return $e;
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