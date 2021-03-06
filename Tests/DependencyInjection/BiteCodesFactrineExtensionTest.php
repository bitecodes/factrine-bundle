<?php

namespace BiteCodes\FactrineBundle\Tests\DependencyInjection;

use BiteCodes\FactrineBundle\DependencyInjection\BiteCodesFactrineExtension;
use BiteCodes\FactrineBundle\BiteCodesFactrineBundle;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class BiteCodesFactrineExtensionTest extends AbstractExtensionTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setParameter('kernel.bundles', [
            BiteCodesFactrineBundle::class
        ]);
    }


    protected function getContainerExtensions()
    {
        return [
            new BiteCodesFactrineExtension()
        ];
    }

    /** @test */
    public function default_values_are_set()
    {
        $this->load();

        $this->assertServiceArgumentExists('factrine.config_provider.config_loader', 0);
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

    protected function assertServiceArgumentExists($service, $index, $value = null)
    {
        if ($value) {
            $this->assertContainerBuilderHasServiceDefinitionWithArgument($service, $index, $value);
        } else {
            $this->assertContainerBuilderHasServiceDefinitionWithArgument($service, $index);
        }
    }
}
