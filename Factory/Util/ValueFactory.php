<?php


namespace Fludio\DoctrineEntityFactoryBundle\Factory\Util;


use Fludio\DoctrineEntityFactoryBundle\Factory\ConfigProvider\ConfigProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ValueFactory
{
    /**
     * @var array
     */
    protected $config;
    /**
     * @var ExpressionLanguage
     */
    protected $language;
    /**
     * @var array
     */
    protected $providerValues;

    public function __construct(ConfigProvider $config, array $dataProviders)
    {
        $this->config = $config->getConfig();
        $this->language = new ExpressionLanguage();
        $this->providerValues = $this->getDataProviderValues($dataProviders);
    }

    public function getValue($entity, $field)
    {
        $data = $this->config[$entity][$field];

        return $this->language->evaluate($data, $this->providerValues);
    }

    public function getAllValues($entity, $parent = null)
    {
        $data = [];

        foreach($this->config[$entity] as $field => $expression) {
            if($parent == $expression) {
                continue;
            }

            if(class_exists($expression)) {
                $data[$field] = $this->getAllValues($expression, $entity);
            } else {
                $data[$field] = $this->getValue($entity, $field);
            }

        }

        return $data;
    }

    protected function getDataProviderValues(array $dataProviders)
    {
        $providerValues = [];

        foreach($dataProviders as $provider) {
            $providerValues[$provider->getCallableName()] = $provider->getProviderInstance();
        }

        return $providerValues;
    }
}
