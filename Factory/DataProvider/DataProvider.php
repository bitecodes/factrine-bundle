<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\DataProvider;

interface DataProvider
{
    public function getCallableName();

    public function getProviderInstance();

    public function getProviderCallables();

    public function getIntegerDefault();

    public function getStringDefault();

    public function getDateDefault();

    public function getBooleanDefault();
}
