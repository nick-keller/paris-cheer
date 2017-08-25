<?php

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Document\DocumentGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DocumentDataTransformerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(DocumentGenerator::class)) {
            return;
        }

        $definition = $container->findDefinition(DocumentGenerator::class);

        $taggedServices = $container->findTaggedServiceIds('app.document_data_transformer');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addTransformer', array(new Reference($id)));
        }
    }
}
