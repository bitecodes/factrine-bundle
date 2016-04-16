<?php


namespace BiteCodes\FactrineBundle\Factory\ConfigProvider;

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

            $this->escapeQuotes($yaml);
            $fileConfig = $this->yaml->parse($yaml);
            $this->unescapeQuotes($fileConfig);

            $config = array_merge($config, $fileConfig);
        }

        return $config;
    }

    /**
     * Escape quotes before parsing
     *
     * @param $string
     */
    private function escapeQuotes(&$string)
    {
        $string = preg_replace('/["\']/', "\'", $string);
    }

    /**
     * Unescape the quotes after parsing
     *
     * @param $array
     */
    private function unescapeQuotes(&$array)
    {
        array_walk_recursive($array, function(&$value) {
            $value = preg_replace("/\\\'/", "'", $value);
        });
    }
}
