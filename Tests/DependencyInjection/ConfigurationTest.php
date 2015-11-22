<?php

namespace Fludio\FactrineBundle\Tests\DependencyInjection;

use Symfony\Component\Yaml\Parser;

class Configuration extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function services_file_is_formatted_correctly()
    {
        $yaml = new Parser();
        $servicesYaml = file_get_contents(__DIR__ . './../../Resources/config/services.yml');
        $services = $yaml->parse($servicesYaml, true);

        $this->assertTrue(is_array($services));
    }
}