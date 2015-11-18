<?php

namespace Fluido\DoctrineEntityFactoryBundle\Tests\Factory;

use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestCase;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Address;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\Hobby;
use Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity\User;

class FactoryTest extends TestCase
{
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
    public function it_creates_multiple_models()
    {
        $users = $this->factory->times(3)->make(User::class);

        $this->assertEquals(3, count($users));
    }
}
