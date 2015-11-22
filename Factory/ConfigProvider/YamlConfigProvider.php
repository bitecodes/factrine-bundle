<?php


namespace Fludio\FactrineBundle\Factory\ConfigProvider;

use Symfony\Component\Yaml\Parser;

class YamlConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Parser
     */
    private $yaml;
    /**
     * @var ConfigLoader
     */
    private $loader;

    public function __construct(ConfigLoader $loader)
    {
        $this->yaml = new Parser();
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
