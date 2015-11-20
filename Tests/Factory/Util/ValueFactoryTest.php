<?php


namespace Fludio\DoctrineEntityFactoryBundle\Tests\Factory\Util;

use Fludio\DoctrineEntityFactoryBundle\Factory\ConfigProvider\ConfigLoader;
use Fludio\DoctrineEntityFactoryBundle\Factory\ConfigProvider\YamlConfigProvider;
use Fludio\DoctrineEntityFactoryBundle\Factory\DataProvider\FakerDataProvider;
use Fludio\DoctrineEntityFactoryBundle\Factory\Util\ValueFactory;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestCase;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Phone;

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