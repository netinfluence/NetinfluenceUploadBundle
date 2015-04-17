<?php

namespace Netinfluence\UploadBundle;

use Netinfluence\UploadBundle\DependencyInjection\Compiler\FormThemePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
/**
 * Class NetinfluenceUploadBundle
 * Bundle root class
 */
class NetinfluenceUploadBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormThemePass());
    }
}
