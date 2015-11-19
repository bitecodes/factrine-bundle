<?php


namespace Fludio\DoctrineEntityFactoryBundle\Factory\ConfigProvider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Fludio\DoctrineEntityFactoryBundle\Factory\Util\DataGuesser;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

class ConfigGenerator
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Bundle[]
     */
    protected $bundles;
    /**
     * @var DataGuesser
     */
    private $guesser;

    public function __construct(EntityManager $em, DataGuesser $guesser, Kernel $kernel)
    {
        $this->em = $em;
        $this->guesser = $guesser;
        $this->bundles = $this->getUserBundles($kernel);
    }

    /**
     * @return array
     */
    public function generate()
    {
        $config = [];

        foreach($this->bundles as $bundle) {

            $metadata = $this->getBundleMetadata($bundle);

            foreach($metadata as $meta) {
                $config[$meta->getName()] = $this->getConfig($meta);
            }
        }

        return $config;
    }

    /**
     * @param Kernel $kernel
     * @return array
     */
    protected function getUserBundles(Kernel $kernel)
    {
        $bundles = [];
        
        /** @var Bundle $bundle */
        foreach($kernel->getBundles() as $bundle) {
            if (strpos($bundle->getPath(), '/src/') !== false) {
                $bundles[] = $bundle;
            }
        }

        return $bundles;
    }

    /**
     * @param Bundle $bundle
     * @return ClassMetadata[]
     */
    protected function getBundleMetadata(Bundle $bundle)
    {
        $bundleMetadata = [];

        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        /** @var ClassMetadata $meta */
        foreach($metadata as $meta) {
            if(strpos($meta->getName(), $bundle->getNamespace()) !== false) {
                $bundleMetadata[] = $meta;
            }
        }

        return $bundleMetadata;
    }

    /**
     * @param ClassMetadata $meta
     * @return array
     */
    private function getConfig(ClassMetadata $meta)
    {
        $result = [];

        foreach($meta->fieldMappings as $mapping) {
            if($meta->isIdentifier($mapping['fieldName'])) {
                continue;
            }

            $result[$mapping['fieldName']] = $this->guesser->guess($mapping);
        }

        return $result;
    }
}
