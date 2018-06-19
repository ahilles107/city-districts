<?php

declare(strict_types=1);

namespace App\DependencyInjection\ContainerBuilder;

use App\Fetcher\ChainFetcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FetcherCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ChainFetcher::class)) {
            return;
        }

        $definition = $container->findDefinition(ChainFetcher::class);

        $taggedServices = $container->findTaggedServiceIds(
            'fetchers.add'
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addFetcher',
                [new Reference($id)]
            );
        }
    }
}
