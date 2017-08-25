<?php

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Program\ProgramEligibilityVoter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProgramEligibilityVoterPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ProgramEligibilityVoter::class)) {
            return;
        }

        $definition = $container->findDefinition(ProgramEligibilityVoter::class);

        $taggedServices = $container->findTaggedServiceIds('app.program');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addProgram', array(new Reference($id)));
        }
    }
}
