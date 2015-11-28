<?php

namespace Fludio\FactrineBundle\Tests\DependencyInjection;

use Fludio\FactrineBundle\DependencyInjection\FludioFactrineExtension;
use Fludio\FactrineBundle\FludioFactrineBundle;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class FludioFactrineExtensionTest extends AbstractExtensionTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setParameter('kernel.bundles', [
            FludioFactrineBundle::class
        ]);
    }


    protected function getContainerExtensions()
    {
        return [
            new FludioFactrineExtension()
        ];
    }

    /** @test */
    public function default_values_are_set()
    {
        $this->load();

        $this->assertServiceArgumentExists('factrine.config_provider.config_loader', 0, []);
        $this->assertServiceArgumentExists('factrine.data_provider.faker_data_provider', 0, 'en_US');
    }

    /** @test */
    public function locale_can_be_changed()
    {
        $this->load([
            'locale' => 'de_DE'
        ]);

        $this->assertServiceArgumentExists('factrine.data_provider.faker_data_provider', 0, 'de_DE');
    }

    protected function assertServiceArgumentExists($service, $index, $value)
    {
        $this->assertContainerBuilderHasServiceDefinitionWithArgument($service, $index, $value);
    }
}
