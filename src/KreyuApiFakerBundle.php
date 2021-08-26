<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle;

use Kreyu\Bundle\ApiFakerBundle\DependencyInjection\Compiler\ConfigurationCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KreyuApiFakerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationCompilerPass);
    }
}
