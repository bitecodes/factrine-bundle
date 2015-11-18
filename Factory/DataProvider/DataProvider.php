<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\DataProvider;

abstract class DataProvider
{
    abstract public function getCallableName();

    abstract public function getProviderInstance();
}