<?php

namespace Fludio\FactrineBundle\Factory\DataProvider;

use Faker\Factory;

class FakerDataProvider implements DataProviderInterface
{
    const REGEX_PROPS = '/(?<=\$)\w*.*[^\)]$/m';
    const REGEX_METHODS = '/\w+(?=\()/';

    /**
     * @var string
     */
    private $locale;

    /**
     * @param $locale
     */
    public function __construct($locale = Factory::DEFAULT_LOCALE)
    {
        $this->locale = $locale;
    }

    /**
     * The string under which faker will be registered
     * as a variable in expression language
     *
     * @return string
     */
    public function getCallableName()
    {
        return 'faker';
    }

    /**
     * @return \Faker\Generator
     */
    public function getProviderInstance()
    {
        $faker = new Factory();
        return $faker->create($this->locale);
    }

    /**
     * Return all callable methods and properties of the library
     *
     * @return array
     */
    public function getProviderCallables()
    {
        $refl = new \ReflectionClass($this->getProviderInstance());
        $doc = $refl->getDocComment();
        preg_match_all(self::REGEX_PROPS, $doc, $properties);
        preg_match_all(self::REGEX_METHODS, $doc, $methods);

        return array_merge($properties[0], $methods[0]);
    }

    /**
     * @return string
     */
    public function getIntegerDefault()
    {
        return 'randomNumber';
    }

    /**
     * @return string
     */
    public function getStringDefault()
    {
        return 'sentence';
    }

    /**
     * @return string
     */
    public function getDateDefault()
    {
        return 'dateTimeBetween';
    }

    /**
     * @return string
     */
    public function getBooleanDefault()
    {
        return 'boolean';
    }
}
