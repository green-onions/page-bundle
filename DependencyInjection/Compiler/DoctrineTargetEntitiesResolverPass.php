<?php
namespace Aropixel\PageBundle\DependencyInjection\Compiler;

use Aropixel\PageBundle\Entity\PageInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;


class DoctrineTargetEntitiesResolverPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {

        try {
            $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        } catch (InvalidArgumentException $exception) {
            return;
        }

        $pageClass = $container->getParameter('aropixel_page.entity');
        $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [PageInterface::class, $pageClass, []]);

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_subscriber')) {
            $resolveTargetEntityListener->addTag('doctrine.event_subscriber', ['event' => 'loadClassMetadata']);
        }

    }

}