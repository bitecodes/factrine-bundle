<?php

namespace Fludio\DoctrineEntityFactoryBundle\Tests\Dummy;

use Fludio\DoctrineEntityFactoryBundle\Factory\Factory;
use Fludio\DoctrineEntityFactoryBundle\Factory\Metadata\ConfigLoader;
use Fludio\DoctrineEntityFactoryBundle\Factory\Metadata\YamlConfigProvider;
use Fludio\DoctrineEntityFactoryBundle\Factory\Util\EntityBuilder;
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
     * Public to allow access from the broken 5.3 closures.
     *
     * @var Factory
     */
    public $factory;

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

        $directories = [
            __DIR__ . '/Config'
        ];

        $faker = new \Faker\Factory();
        $configProvider = new YamlConfigProvider(new Parser(), new ConfigLoader($directories));
        $this->factory = new Factory($this->em, new EntityBuilder($this->em, $faker, $configProvider));
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
}