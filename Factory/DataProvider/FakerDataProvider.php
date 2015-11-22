<?php

namespace Fludio\FactrineBundle\Factory\DataProvider;

use Faker\Factory;

class FakerDataProvider implements DataProviderInterface
{
    const REGEX_PROPS = '/(?<=\$)\w*.*[^\)]$/m';
    const REGEX_METHODS = '/\w+(?=\()/';

    public function getCallableName()
    {
        return 'faker';
    }

    public function getProviderInstance()
    {
        $faker = new Factory();
        return $faker->create();
    }

    public function getProviderCallables()
    {
        $refl = new \ReflectionClass($this->getProviderInstance());
        $doc = $refl->getDocComment();
        preg_match_all(self::REGEX_PROPS, $doc, $properties);
        preg_match_all(self::REGEX_METHODS, $doc, $methods);

        return array_merge($properties[0], $methods[0]);
    }

    public function getIntegerDefault()
    {
        return 'randomNumber';
    }

    public function getStringDefault()
    {
        return 'sentence';
    }

    public function getDateDefault()
    {
        return 'dateTimeBetween';
    }

    public function getBooleanDefault()
    {
        return 'boolean';
    }
}
