<?php

namespace BiteCodes\FactrineBundle\Seeder;

use BiteCodes\FactrineBundle\Factory\Factory;
use Symfony\Component\DependencyInjection\ContainerAware;

abstract class Seeder extends ContainerAware
{
    /**
     * @var Factory
     */
    protected $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    abstract public function run();
}