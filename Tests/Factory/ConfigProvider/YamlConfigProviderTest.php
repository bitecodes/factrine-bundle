<?php

namespace Fludio\FactrineBundle\Tests\Factory\ConfigProvider;

use Fludio\FactrineBundle\Factory\ConfigProvider\ConfigLoader;
use Fludio\FactrineBundle\Factory\ConfigProvider\YamlConfigProvider;

class YamlConfigProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $config;

    public function setUp()
    {
        $loader = new ConfigLoader([__DIR__]);
        $configProvider = new YamlConfigProvider($loader);
        $this->config = $configProvider->getConfig();
    }

    /** @test */
    public function it_allows_string_values_in_double_quotes()
    {
        $this->assertArraySubset(['street' => "'Main Street 10'"], $this->config['My\Address']);
    }

    /** @test */
    public function it_allows_string_values_in_single_quotes()
    {
        $this->assertArraySubset(['city' => "'New York'"], $this->config['My\Address']);
    }

    /** @test */
    public function it_keeps_quotes_in_strings()
    {
        $this->assertArraySubset(['streetNumber' => "faker.randomElement(['10','12'])"], $this->config['My\Address']);
    }

    /** @test */
    public function it_allows_booleans()
    {
        $this->assertArraySubset(['cozy' => true], $this->config['My\Address']);
    }

    /** @test */
    public function it_allows_numbers()
    {
        $this->assertArraySubset(['floor' => 12], $this->config['My\Address']);
    }

    /** @test */
    public function it_allows_arrays()
    {
        $this->assertArraySubset(['roommates' => ["'John'", "'Marie'"]], $this->config['My\Address']);
    }
}
