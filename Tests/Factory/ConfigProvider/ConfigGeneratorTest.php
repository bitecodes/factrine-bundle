<?php


namespace Fludio\FactrineBundle\Tests\Factory\ConfigProvider;

use Fludio\FactrineBundle\Factory\DataProvider\FakerDataProvider;
use Fludio\FactrineBundle\Factory\Util\DataGuesser;
use Fludio\FactrineBundle\Factory\ConfigProvider\ConfigGenerator;
use Fludio\FactrineBundle\Tests\Dummy\app\AppKernel;
use Fludio\FactrineBundle\Tests\Dummy\TestCase;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Address;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Hobby;

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

        $this->assertEquals(11, count($configs));
        $this->assertArrayHasKey(Hobby::class, $configs);
        $this->assertEquals(3, count($configs[Hobby::class]));
    }

    /** @test */
    public function it_generates_an_entry_for_id_fields_that_are_not_auto_generated()
    {
        $configs = $this->generator->generate();

        $this->assertArrayHasKey('id', $configs[Address::class]);
    }
}