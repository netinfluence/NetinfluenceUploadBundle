<?php

namespace Netinfluence\UploadBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FormThemePass
 */
class FormThemePass implements CompilerPassInterface
{
    /**
     * Add our form types themes to Twig
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $formThemes = $container->getParameter('twig.form.resources');

        $formThemes[] = 'NetinfluenceUploadBundle:Form:dropzone.html.twig';

        $container->setParameter('twig.form.resources', $formThemes);
    }
}
