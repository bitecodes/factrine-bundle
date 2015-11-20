<?php

namespace Fluido\DoctrineEntityFactoryBundle\Tests\Factory;

use Fludio\DoctrineEntityFactoryBundle\Factory\Factory;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\app\AppKernel;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestCase;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Address;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\User;

class FactoryTest extends TestCase
{
    /** @test */
    public function it_can_be_retrieved_from_the_service_container()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $factory = $kernel->getContainer()->get('fludio_factory.factory');
        $this->assertInstanceOf(Factory::class, $factory);
    }

    /** @test */
    public function it_creates_an_entity()
    {
        $address = $this->factory->make(Address::class);

        $this->assertInstanceOf(Address::class, $address);
    }

    /** @test */
    public function it_persists_an_entity()
    {
        $this->factory->create(Address::class, [
            'street' => 'Main St. 10',
            'city' => 'New York',
            'zip' => '82020'
        ]);

        $this->seeInDatabase(Address::class, [
            'street' => 'Main St. 10'
        ]);
    }

    /** @test */
    public function it_creates_multiple_entities()
    {
        $users = $this->factory->times(3)->make(User::class);

        $this->assertEquals(3, count($users));
    }

    /** @test */
    public function it_persists_multiple_entities()
    {
        $this->factory->times(3)->create(Address::class, [
            'street' => 'Main St. 10',
            'city' => 'New York',
            'zip' => '82020'
        ]);

        $count = $this->getDatabaseCount(Address::class, []);

        $this->assertEquals(3, $count);
    }

    /** @test */
    public function it_adds_fake_data_from_config_files()
    {
        $address = $this->factory->create(Address::class);

        $this->assertNotNull($address->getStreet());
        $this->assertNotNull($address->getCity());
        $this->assertNotNull($address->getZip());
    }

    /** @test */
    public function it_adds_fake_data_to_associated_entites()
    {
        $user = $this->factory->create(User::class);
        $address = $user->getAddress();
        
        $this->assertNotNull($user->getFirstName());
        $this->assertNotNull($address->getStreet());
        $this->assertNotNull($address->getCity());
        $this->assertNotNull($address->getZip());
    }

    /** @test */
    public function it_persists_deeply_nested_associations()
    {
        $user = $this->factory->create(User::class);
        $phone = $user->getPhone();
        $app = $phone->getApps();

        $this->assertEquals(1, $phone->getApps()->count());
        $this->assertNotNull($app->first()->getTitle());
    }
}
