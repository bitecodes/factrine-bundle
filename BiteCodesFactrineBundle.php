<?php

namespace BiteCodes\FactrineBundle;

use BiteCodes\FactrineBundle\DependencyInjection\BiteCodesFactrineExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BiteCodesFactrineBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new BiteCodesFactrineExtension();
        }

        return $this->extension;
    }
}
