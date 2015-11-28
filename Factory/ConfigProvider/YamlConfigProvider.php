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
            $yaml = file_get_contents($file);

            $this->encodeQuotes($yaml);
            $fileConfig = $this->yaml->parse($yaml);
            $this->decodeQuotes($fileConfig);

            $config = array_merge($config, $fileConfig);
        }

        return $config;
    }

    private function encodeQuotes(&$string)
    {
        $string = preg_replace('/["\']/', "\'", $string);
    }

    private function decodeQuotes(&$fileConfig)
    {
        array_walk_recursive($fileConfig, function(&$value) {
            $value = preg_replace("/\\\'/", "'", $value);
        });
    }
}
