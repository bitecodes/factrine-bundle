<?php


namespace BiteCodes\FactrineBundle\Tests\Factory\Util;

use BiteCodes\FactrineBundle\Factory\ConfigProvider\ConfigLoader;
use BiteCodes\FactrineBundle\Factory\ConfigProvider\YamlConfigProvider;
use BiteCodes\FactrineBundle\Factory\DataProvider\FakerDataProvider;
use BiteCodes\FactrineBundle\Factory\Util\ValueFactory;
use BiteCodes\FactrineBundle\Tests\Dummy\TestCase;
use BiteCodes\FactrineBundle\Tests\Dummy\TestEntity\Address;
use BiteCodes\FactrineBundle\Tests\Dummy\TestEntity\House;
use BiteCodes\FactrineBundle\Tests\Dummy\TestEntity\Phone;

class ValueFactoryTest extends TestCase
{
    /**
     * @var ValueFactory
     */
    protected $valueFactory;

    public function setUp()
    {
        parent::setUp();

        $configDir = __DIR__ . '/../../Dummy/Config';

        $loader = new ConfigLoader([$configDir]);

        $configProvider = new YamlConfigProvider($loader);
        $fakerDataProvider = new FakerDataProvider();

        $this->valueFactory = new ValueFactory($configProvider, [$fakerDataProvider]);
    }

    /** @test */
    public function it_generates_values_for_entities()
    {
        $values = $this->valueFactory->getAllValues(Phone::class);

        $this->assertArrayHasKey('number', $values);
        $this->assertArrayHasKey('apps', $values);
        $this->assertArrayHasKey('title', $values['apps']);
    }

    /** @test */
    public function it_handles_array_values()
    {
        $values = $this->valueFactory->getAllValues(Address::class);

        $this->assertEquals(['Bath room', 'Sleeping room'], $values['roomes']);
    }

    /** @test */
    public function it_returns_an_empty_array_if_not_config_exists()
    {
        $values = $this->valueFactory->getAllValues(House::class);

        $this->assertEquals([], $values);
    }

}