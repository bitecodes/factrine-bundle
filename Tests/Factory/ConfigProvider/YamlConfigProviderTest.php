<?php

namespace Fludio\FactrineBundle\Tests\Factory\ConfigProvider;

use Fludio\FactrineBundle\Factory\ConfigProvider\ConfigLoader;
use Fludio\FactrineBundle\Factory\ConfigProvider\YamlConfigProvider;
use PHPUnit_Framework_MockObject_MockObject;

class YamlConfigProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var YamlConfigProvider
     */
    protected $configProvider;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $loader;

    public function setUp()
    {
        $loader = new ConfigLoader([__DIR__]);
        $this->configProvider = new YamlConfigProvider($loader);
    }

    /** @test */
    public function it_allows_string_values_in_double_quotes()
    {
        $config = $this->configProvider->getConfig();

        $this->assertArraySubset(['street' => "'Main Street 10'"], $config['My\Address']);
    }

    /** @test */
    public function it_allows_string_values_in_single_quotes()
    {
        $config = $this->configProvider->getConfig();

        $this->assertArraySubset(['city' => "'New York'"], $config['My\Address']);
    }

    /** @test */
    public function it_keeps_quotes_in_strings()
    {
        $config = $this->configProvider->getConfig();

        $this->assertArraySubset(['streetNumber' => "faker.randomElement(['10','12'])"], $config['My\Address']);
    }
}
