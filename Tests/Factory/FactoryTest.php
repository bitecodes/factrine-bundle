<?php

namespace Fluido\FactrineBundle\Tests\Factory;

use Fludio\FactrineBundle\Factory\Factory;
use Fludio\FactrineBundle\Tests\Dummy\app\AppKernel;
use Fludio\FactrineBundle\Tests\Dummy\TestCase;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Address;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\App;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Phone;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Treehouse;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\User;

class FactoryTest extends TestCase
{
    /** @test */
    public function it_can_be_retrieved_from_the_service_container()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();

        $factory = $kernel->getContainer()->get('factrine');
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
    public function it_persists_an_entity_with_inheritance_mapping()
    {
        $this->factory->create(Treehouse::class, [
            'treeType' => 'oak'
        ]);

        $this->seeInDatabase(Treehouse::class, [
            'treeType' => 'oak'
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
    public function it_returns_fake_data_for_an_entity()
    {
        $values = $this->factory->values(Address::class);

        $this->assertNotNull($values['street']);
        $this->assertNotNull($values['city']);
        $this->assertNotNull($values['zip']);
    }

    /** @test */
    public function it_returns_fake_data_multiple_times()
    {
        $values = $this->factory->times(2)->values(Address::class);

        $this->assertEquals(2, count($values));
        $this->assertNotNull($values[0]['street']);
        $this->assertNotNull($values[0]['city']);
        $this->assertNotNull($values[0]['zip']);
        $this->assertNotNull($values[1]['street']);
        $this->assertNotNull($values[1]['city']);
        $this->assertNotNull($values[1]['zip']);
    }

    /** @test */
    public function it_returns_fake_data_for_associtations()
    {
        $values = $this->factory->values(User::class);

        $this->assertEquals(4, count($values['address']));
        $this->assertNotNull($values['address']['street']);
    }

    /** @test */
    public function it_allows_to_override_fake_values()
    {
        $values = $this->factory->values(Address::class, ['zip' => '01097']);

        $this->assertEquals('01097', $values['zip']);
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
    public function it_generates_different_data_when_multiple_entities_are_generated()
    {
        $addresses = $this->factory->times(2)->make(Address::class);
        $address1 = $addresses[0];
        $address2 = $addresses[1];

        $data1 = [
            $address1->getStreet(),
            $address1->getCity(),
            $address1->getZip(),
        ];

        $data2 = [
            $address2->getStreet(),
            $address2->getCity(),
            $address2->getZip(),
        ];

        $this->assertNotEquals($data1, $data2);
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

    /** @test */
    public function it_creates_multiple_associations()
    {
        $apps = $this->factory->times(5)->create(App::class);
        $phone = $this->factory->create(Phone::class, ['apps' => $apps]);

        $this->assertEquals(5, $phone->getApps()->count());
        foreach ($phone->getApps() as $app) {
            $this->assertInstanceOf(App::class, $app);
        }
    }
}
