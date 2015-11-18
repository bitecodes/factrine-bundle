<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\DataProvider;

use Faker\Factory;

class FakerDataProvider extends DataProvider
{
    public function getCallableName()
    {
        return 'faker';
    }

    public function getProviderInstance()
    {
        $faker = new Factory();
        return $faker->create();
    }
}