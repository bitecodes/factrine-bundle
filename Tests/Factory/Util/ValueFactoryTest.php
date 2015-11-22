<?php


namespace Fludio\FactrineBundle\Tests\Factory\Util;

use Fludio\FactrineBundle\Factory\ConfigProvider\ConfigLoader;
use Fludio\FactrineBundle\Factory\ConfigProvider\YamlConfigProvider;
use Fludio\FactrineBundle\Factory\DataProvider\FakerDataProvider;
use Fludio\FactrineBundle\Factory\Util\ValueFactory;
use Fludio\FactrineBundle\Tests\Dummy\TestCase;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Phone;

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
}