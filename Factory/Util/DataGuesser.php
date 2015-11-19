<?php


namespace Fludio\DoctrineEntityFactoryBundle\Factory\Util;

use Fludio\DoctrineEntityFactoryBundle\Factory\DataProvider\FakerDataProvider;

class DataGuesser
{
    /**
     * @var FakerDataProvider
     */
    protected $dataProvider;

    /**
     * DataGuesser constructor.
     * // TODO exchange for interface or array of providers
     * @param FakerDataProvider $faker
     */
    public function __construct(FakerDataProvider $faker)
    {
        $this->dataProvider = $faker;
    }

    /**
     * Return an expression for the provider
     *
     * @param $mapping
     * @return string
     */
    public function guess($mapping)
    {
        $method = $this->guessByProviderCallables($mapping['fieldName']);

        if(!$method) {
            $method = $this->getByType($mapping['type']);
        }

        return $this->dataProvider->getCallableName() . '.' . $method;
    }

    /**
     * Try to find a callable that might match
     *
     * @param $fieldName
     * @return bool
     */
    protected function guessByProviderCallables($fieldName)
    {
        $options = [];

        foreach($this->dataProvider->getProviderCallables() as $callable) {
            if(strpos($callable, $fieldName) !== false) {
                $options[] = $callable;
            }
        }

        foreach($options as $option) {
            if (strcasecmp($option, $fieldName) == 0) {
                return $option;
            }
        }

        if(!empty($options)) {
            return $options[0];
        }

        return false;
    }

    /**
     * Get some defaults
     *
     * @param $type
     * @return string
     */
    protected function getByType($type)
    {
        switch($type) {
            case 'integer':
            case 'smallint':
            case 'bigint':
            case 'decimal':
            case 'float':
                $method = $this->dataProvider->getIntegerDefault();
                break;
            case 'text':
            case 'string':
                $method = $this->dataProvider->getStringDefault();
                break;
            case 'datetime':
            case 'date':
                $method = $this->dataProvider->getDateDefault();
                break;
            case 'boolean':
                $method = $this->dataProvider->getBooleanDefault();
                break;
            default:
                $method = '?';
        }

        return $method;
    }
}
