<?php


namespace Fludio\DoctrineEntityFactoryBundle\Factory\Metadata;

use Symfony\Component\Yaml\Parser;

class YamlConfigProvider extends ConfigProvider
{
    /**
     * @var Parser
     */
    private $yaml;
    /**
     * @var ConfigLoader
     */
    private $loader;

    public function __construct(Parser $yaml, ConfigLoader $loader)
    {
        $this->yaml = $yaml;
        $this->loader = $loader;
    }

    public function getConfig()
    {
        $files = $this->loader->getFiles();

        $config = [];

        foreach($files as $file) {
            $fileConfig = $this->yaml->parse(file_get_contents($file));
            $config = array_merge($config, $fileConfig);
        }

        return $config;
    }
}