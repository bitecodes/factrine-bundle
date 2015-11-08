<?php

namespace Fluido\DoctrineEntityFactoryBundle\Tests\Factory;

use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestCase;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Address;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\User;

class FactoryTest extends TestCase
{
    /** @test */
    public function it_creates_an_entity()
    {
        $address = $this->factory->make(Address::class);

        $this->assertInstanceOf('Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Address', $address);
    }

    /** @test */
    public function it_creates_an_entity_with_a_one_to_one_unidirectional_association()
    {
        /** @var User $user */
        $user = $this->factory->make(User::class);

        $this->assertInstanceOf('Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Address', $user->getAddress());
    }

    /** @test */
    public function it_creates_an_entity_with_a_many_to_one_bidirectional_association()
    {
        /** @var User $user */
        $user = $this->factory->make(User::class);

        $this->assertInstanceOf('Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Hobby', $user->getHobbies()->first());
    }

    /** @test */
    public function it_allows_for_setting_field_values()
    {
        /** @var User $user */
        $user = $this->factory->make(User::class, [
            'firstName' => 'John',
            'lastName' => 'Doe'
        ]);

        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
    }

    /** @test */
    public function it_sets_field_values_of_associations()
    {
        /** @var User $user */
        $user = $this->factory->make(User::class, [
            'address.street' => 'Main St. 10',
            'address.city' => 'New York'
        ]);

        $this->assertEquals('Main St. 10', $user->getAddress()->getStreet());
        $this->assertEquals('New York', $user->getAddress()->getCity());
    }

    /** @test */
    public function it_creates_multiple_models()
    {
        $users = $this->factory->times(3)->make(User::class);

        $this->assertEquals(3, count($users));
    }

    public function it_works_with_dot_notation()
    {
        $params = [
            'firstName' => 'Thomas',
            'address.street' => 'Hauptstr. 10',
            'address.city' => 'Dresden',
            'address.location.lat' => 50
        ];

        $result = $this->factory->prepareParams($params);

        $this->assertEquals([
            'firstName' => 'Thomas',
            'address' => [
                'street' => 'Hauptstr. 10',
                'city' => 'Dresden',
                'location' => [
                    'lat' => 50
                ]
            ]
        ], $result);
    }
}
