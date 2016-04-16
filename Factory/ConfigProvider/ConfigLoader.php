<?php

namespace BiteCodes\FactrineBundle\Factory\ConfigProvider;

class ConfigLoader
{
    /**
     * @var array
     */
    private $directories;

    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    public function getFiles()
    {
        $files = [];

        foreach($this->directories as $dir) {
            if ($handle = opendir($dir)) {
                while (false !== ($entry = readdir($handle))) {
                    if(substr($entry, -3, 3) == 'yml') {
                        $files[] = $dir . DIRECTORY_SEPARATOR . $entry;
                    }
                }
            }
        }

        return $files;
    }
}
