<?php


namespace Fludio\DoctrineEntityFactoryBundle\Tests\Factory\ConfigProvider;

use Fludio\DoctrineEntityFactoryBundle\Factory\DataProvider\FakerDataProvider;
use Fludio\DoctrineEntityFactoryBundle\Factory\Util\DataGuesser;
use Fludio\DoctrineEntityFactoryBundle\Factory\ConfigProvider\ConfigGenerator;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\app\AppKernel;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestCase;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Address;

class ConfigGeneratorTest extends TestCase
{
    /**
     * @var AppKernel
     */
    protected $kernel;
    /**
     * @var ConfigGenerator
     */
    protected $generator;

    public function setUp()
    {
        parent::setUp();

        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();

        $dataProvider = new FakerDataProvider();
        $guesser = new DataGuesser($dataProvider);
        $this->generator = new ConfigGenerator($this->em, $guesser, $this->kernel);
    }

    /** @test */
    public function it_generates_an_array_of_configs_for_all_entities()
    {
        $configs = $this->generator->generate();
        
        $this->assertEquals(9, count($configs));
        $this->assertArrayHasKey(Address::class, $configs);
        $this->assertEquals(3, count($configs[Address::class]));
    }
}