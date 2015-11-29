<?php

namespace Fludio\FactrineBundle;

use Fludio\FactrineBundle\DependencyInjection\FludioFactrineExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FludioFactrineBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FludioFactrineExtension();
        }

        return $this->extension;
    }
}
