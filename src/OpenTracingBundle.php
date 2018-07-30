<?php

namespace Hoten\OpenTracingBundle;

use Hoten\OpenTracingBundle\DependencyInjection\OpenTracingExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpenTracingBundle extends Bundle
{
    public function getContainerExtensionClass()
    {
        return OpenTracingExtension::class;
    }
}
