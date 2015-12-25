<?php

namespace Fludio\FactrineBundle\Tests\Factory\EntityBuilder;

use Doctrine\Common\Collections\ArrayCollection;
use Fludio\FactrineBundle\Factory\EntityBuilder\EntityBuilder;
use Fludio\FactrineBundle\Tests\Dummy\TestCase;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Address;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\App;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Category;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\EmailAddress;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Group;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Hobby;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Job;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Phone;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\User;

/**
 * Class EntityBuilderTest
 * @package Fludio\FactrineBundle\Tests\Factory\EntityBuilder
 */
class EntityBuilderTest extends TestCase
{
    /**
     * @var EntityBuilder
     */
    protected $builder;

    public function setUp()
    {
        parent::setUp();

        $this->builder = new EntityBuilder($this->em);
    }

    /** @test */
    public function it_creates_an_entity_without_params()
    {
        $user = $this->builder->createEntity(User::class);

        $this->assertInstanceOf(User::class, $user);
        $this->assertNull($user->getAddress());
        $this->assertNull($user->getPhone());
        $this->assertNull($user->getJob());
        $this->assertNull($user->getSpouse());
        $this->assertInstanceOf(ArrayCollection::class, $user->getParents());
        $this->assertInstanceOf(ArrayCollection::class, $user->getChildren());
        $this->assertInstanceOf(ArrayCollection::class, $user->getEmailAddresses());
        $this->assertInstanceOf(ArrayCollection::class, $user->getHobbies());
        $this->assertInstanceOf(ArrayCollection::class, $user->getGroups());
        $this->assertEquals(0, $user->getParents()->count());
        $this->assertEquals(0, $user->getChildren()->count());
        $this->assertEquals(0, $user->getEmailAddresses()->count());
        $this->assertEquals(0, $user->getHobbies()->count());
        $this->assertEquals(0, $user->getGroups()->count());
    }

    /**
     * @test
     * @expectedException Doctrine\ORM\Mapping\MappingException
     */
    public function it_throws_an_exception_if_the_entity_does_not_exist()
    {
        $this->builder->createEntity(TestCase::class);
    }

    /** @test */
    public function it_sets_values_to_fields()
    {
        $user = $this->builder->createEntity(User::class, [
            'firstName' => 'Thomas'
        ]);

        $this->assertEquals('Thomas', $user->getFirstName());
    }
    

    /** @test */
    public function it_handles_many_to_one_unidirectional()
    {
        $user = $this->builder->createEntity(User::class, [
            'address.city' => 'Paris'
        ]);

        $this->assertInstanceOf(Address::class, $user->getAddress());
        $this->assertEquals('Paris', $user->getAddress()->getCity());
    }

    /** @test */
    public function it_handles_one_to_one_unidirectional()
    {
        $user = $this->builder->createEntity(User::class, [
            'phone.number' => '0171123456'
        ]);

        $this->assertInstanceOf(Phone::class, $user->getPhone());
        $this->assertEquals('0171123456', $user->getPhone()->getNumber());
    }

    /** @test */
    public function it_handles_one_to_one_bidirectional()
    {
        $user = $this->builder->createEntity(User::class, [
            'job.title' => 'Programmer'
        ]);

        $this->assertInstanceOf(Job::class, $user->getJob());
        $this->assertInstanceOf(User::class, $user->getJob()->getUser());
        $this->assertEquals($user, $user->getJob()->getUser());
        $this->assertEquals('Programmer', $user->getJob()->getTitle());

    }

    /** @test */
    public function it_handles_one_to_one_self_referencing()
    {
        $user = $this->builder->createEntity(User::class, [
            'spouse.firstName' => 'Jane'
        ]);

        $this->assertInstanceOf(User::class, $user->getSpouse());
        $this->assertInstanceOf(User::class, $user->getSpouse()->getSpouse());
        $this->assertEquals($user, $user->getSpouse()->getSpouse());
    }

    /** @test */
    public function it_handles_one_to_many_unidirectional()
    {
        $user = $this->builder->createEntity(User::class, [
            'emailAddresses.email' => 'some@mail.com'
        ]);

        $this->assertEquals(1, $user->getEmailAddresses()->count());
        $this->assertInstanceOf(EmailAddress::class, $user->getEmailAddresses()->first());
        $this->assertEquals('some@mail.com', $user->getEmailAddresses()->first()->getEmail());
    }

    /** @test */
    public function it_handles_one_to_many_bidirectional()
    {
        $user = $this->builder->createEntity(User::class, [
            'hobbies.name' => 'Music'
        ]);

        $this->assertEquals(1, $user->getHobbies()->count());
        $this->assertInstanceOf(Hobby::class, $user->getHobbies()->first());
        $this->assertEquals($user, $user->getHobbies()->first()->getUser());
        $this->assertEquals('Music', $user->getHobbies()->first()->getName());
    }

    /** @test */
    public function it_handles_one_to_many_self_referencing()
    {
        $category = $this->builder->createEntity(Category::class, [
            'name' => 'Sport',
            'children.name' => 'Basketball',
            'parent.name' => 'Activities'
        ]);
        

        $this->assertInstanceOf(Category::class, $category->getChildren()->first());
        $this->assertInstanceOf(Category::class, $category->getParent());
        $this->assertEquals(1, $category->getChildren()->count());
        // TODO Have a look why it is not the same
        // $this->assertEquals($category, $category->getChildren()->first()->getParent());
        $this->assertEquals('Sport', $category->getName());
        $this->assertEquals('Basketball', $category->getChildren()->first()->getName());
        $this->assertEquals('Activities', $category->getParent()->getName());
    }

    /** @test */
    public function it_handles_many_to_many_unidirectional()
    {
        $user = $this->builder->createEntity(User::class, [
            'groups.title' => 'TestGroup'
        ]);

        $this->assertEquals(1, $user->getGroups()->count());
        $this->assertInstanceOf(Group::class, $user->getGroups()->first());
        $this->assertEquals('TestGroup', $user->getGroups()->first()->getTitle());
    }

    /** @test */
    public function it_handles_many_to_many_bidirectional()
    {
        $phone = $this->builder->createEntity(Phone::class, [
            'apps.title' => 'FlappyBird'
        ]);

        $this->assertEquals(1, $phone->getApps()->count());
        $this->assertInstanceOf(App::class, $phone->getApps()->first());
        $this->assertEquals($phone, $phone->getApps()[0]->getPhones()->first());
        $this->assertEquals('FlappyBird', $phone->getApps()->first()->getTitle());
    }

    /** @test */
    public function it_handles_many_to_many_self_referencing()
    {
        $user = $this->builder->createEntity(User::class, [
            'parents.firstName' => 'Abe',
            'firstName' => 'Homer',
            'children.firstName' => 'Bart'
        ]);

        $this->assertEquals(1, $user->getParents()->count());
        $this->assertEquals(1, $user->getChildren()->count());
        $this->assertEquals($user, $user->getParents()->first()->getChildren()->first());
        $this->assertEquals($user, $user->getChildren()->first()->getParents()->first());
        $this->assertEquals('Abe', $user->getParents()->first()->getFirstName());
        $this->assertEquals('Homer', $user->getFirstName());
        $this->assertEquals('Bart', $user->getChildren()->first()->getFirstName());
    }

    /** @test */
    public function it_allows_to_add_multiple_entities_for_one_to_many_unidirectional_associations()
    {
        $user = $this->builder->createEntity(User::class, [
            'emailAddresses.0.email' => 'one@mail.de',
            'emailAddresses.1.email' => 'two@mail.de'
        ]);

        $this->assertEquals(2, $user->getEmailAddresses()->count());
        $this->assertEquals('one@mail.de', $user->getEmailAddresses()[0]->getEmail());
        $this->assertEquals('two@mail.de', $user->getEmailAddresses()[1]->getEmail());
    }

    /** @test */
    public function it_allows_to_add_multiple_entities_for_one_to_many_bidirectional_associations()
    {
        $user = $this->builder->createEntity(User::class, [
            'hobbies.0.name' => 'Music',
            'hobbies.1.name' => 'Basketball'
        ]);

        $this->assertEquals(2, $user->getHobbies()->count());
        $this->assertEquals('Music', $user->getHobbies()[0]->getName());
        $this->assertEquals('Basketball', $user->getHobbies()[1]->getName());
    }

    /** @test */
    public function it_allows_to_add_multiple_entities_for_one_to_many_selfreferential_associations()
    {
        $cateogory = $this->builder->createEntity(Category::class, [
            'name' => 'Sport',
            'children.0.name' => 'Basketball',
            'children.1.name' => 'Soccer',
        ]);

        $this->assertEquals(2, $cateogory->getChildren()->count());
        $this->assertEquals('Sport', $cateogory->getName());
        $this->assertEquals('Basketball', $cateogory->getChildren()[0]->getName());
        $this->assertEquals('Soccer', $cateogory->getChildren()[1]->getName());
    }

    /** @test */
    public function it_allows_to_add_multiple_entities_for_many_to_many_unidirectional_associations()
    {
        $user = $this->builder->createEntity(User::class, [
            'groups.0.title' => 'Admin',
            'groups.1.title' => 'Editor'
        ]);

        $this->assertEquals(2, $user->getGroups()->count());
        $this->assertEquals('Admin', $user->getGroups()[0]->getTitle());
        $this->assertEquals('Editor', $user->getGroups()[1]->getTitle());
    }

    /** @test */
    public function it_allows_to_add_multiple_entities_for_many_to_many_bidirectional_associations()
    {
        $phone = $this->builder->createEntity(Phone::class, [
            'apps.0.title' => 'FlappyBird',
            'apps.1.title' => 'Spotify'
        ]);
        
        $this->assertEquals(2, $phone->getApps()->count());
        $this->assertEquals($phone, $phone->getApps()[0]->getPhones()->first());
        $this->assertEquals($phone, $phone->getApps()[1]->getPhones()->first());
        $this->assertEquals('FlappyBird', $phone->getApps()[0]->getTitle());
        $this->assertEquals('Spotify', $phone->getApps()[1]->getTitle());
    }

    /** @test */
    public function it_allows_to_add_multiple_entities_for_many_to_many_selfreferential_associations()
    {
        $homer = $this->builder->createEntity(User::class, [
            'children.0.firstName' => 'Bart',
            'children.1.firstName' => 'Lisa'
        ]);

        $this->assertEquals(2, $homer->getChildren()->count());
        $this->assertEquals('Bart', $homer->getChildren()[0]->getFirstName());
        $this->assertEquals('Lisa', $homer->getChildren()[1]->getFirstName());
    }

    /** @test */
    public function it_allows_0_as_a_value()
    {
        $hobby = $this->builder->createEntity(Hobby::class, [
            'priority' => 0
        ]);

        $this->assertTrue(0 === $hobby->getPriority());
    }

    /** @test */
//    public function it_creates_associations_for_both_entities()
//    {
//        $app = $this->builder->createEntity(App::class);
//        $phone = $this->builder->createEntity(Phone::class);
//
//        $this->assertEquals($app, $phone->getApps()->first());
//        $this->assertEquals($phone, $app->getPhones()->toArray()[0]);
//    }
}