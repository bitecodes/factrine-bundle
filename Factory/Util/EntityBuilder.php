<?php

namespace Fludio\DoctrineEntityFactoryBundle\Factory\Util;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Fludio\DoctrineEntityFactoryBundle\Factory\Metadata\ConfigProvider;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;
use Symfony\Component\Yaml\Parser;

/**
 * Class EntityBuilder
 * @package Fludio\FactoryBundle\Factory\Util
 */
class EntityBuilder
{
    /**
     * @var ObjectManager
     */
    private $om;
    /**
     * @var Factory
     */
    private $faker;
    /**
     * @var Parser
     */
    private $yaml;
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @param ObjectManager $om
     * @param Factory $faker
     * @param ConfigProvider $configProvider
     */
    public function __construct(ObjectManager $om, Factory $faker, ConfigProvider $configProvider)
    {
        $this->om = $om;
        $this->faker = $faker->create();
        $this->configProvider = $configProvider;
    }

    /**
     * @param $entity
     * @param array $params
     * @param null $callback
     * @return array|mixed
     * @throws Exception
     */
    public function createEntity($entity, $params = [], $callback = null)
    {
        $params = $this->prepareParams($params);

        // check if entity exists
        if(!class_exists($entity)) {
            throw new Exception('Class not found');
        }

        $meta = $this->om->getClassMetadata($entity);

        $config = $this->configProvider->getConfig();

        $instance = new $entity;

        foreach($meta->getFieldNames() as $field) {

            if(in_array($field, $meta->getIdentifierFieldNames())) {
                continue;
            }
            if(isset($params[$field])) {
                $value = $params[$field];
            } else {
                $value = $this->getValue($config[$entity][$field]);
            }
            $instance->{'set' . ucwords($field)}($value);
        }

        foreach($meta->getAssociationNames() as $association) {

            if(!isset($config[$entity][$association])) {
                continue;
            }

            if(isset($params[$association])) {
                $v = $params[$association];
            } else {
                $v = [];
            }

            if($meta->isSingleValuedAssociation($association)) {
                $instance->{'set' . ucwords($association)}($this->createEntity($config[$entity][$association], $v));
            } elseif ($meta->isCollectionValuedAssociation($association)) {
                $class = $meta->getAssociationTargetClass($association);
                $name = $this->om->getClassMetadata($class)->getReflectionClass()->getShortName();

                $instance->{'add' . ucwords($name)}($this->createEntity($config[$entity][$association], $v, function($child) use ($instance) {
                    $meta = $this->om->getClassMetadata(get_class($instance));
                    $name = $meta->getReflectionClass()->getShortName();
                    return $child;
                }));
            }
        }

        if(is_callable($callback)) {
            $instance = $callback($instance);
        }

        $result[] = $instance;

        return count($result) > 1 ? $result : array_pop($result);
    }

    /**
     * @param $params
     * @return array
     */
    protected function prepareParams($params)
    {
        $result = [];

        foreach($params as $path => $value) {
            $this->setValue($result, $path, $value);
        }

        return $result;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function getValue($data)
    {
        $language = new ExpressionLanguage();

        return $language->evaluate($data, [
            'faker' => (new Factory())->create()
        ]);

        return $value;
    }

    /**
     * @param $collection
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function setValue(&$collection, $key, $value)
    {
        if (is_null($key)) {
            return $collection = $value;
        }
        // Explode the keys
        $keys = explode('.', $key);
        // Crawl through the keys
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If we're dealing with an object
            if (is_object($collection)) {
                if (!isset($collection->$key) or !is_array($collection->$key)) {
                    $collection->$key = [];
                }
                $collection = &$collection->$key;
                // If we're dealing with an array
            } else {
                if (!isset($collection[$key]) or !is_array($collection[$key])) {
                    $collection[$key] = [];
                }
                $collection = &$collection[$key];
            }
        }
        // Bind final tree on the collection
        $key = array_shift($keys);
        if (is_array($collection)) {
            $collection[$key] = $value;
        } else {
            $collection->$key = $value;
        }
    }
}