<?php

namespace VM;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use VM\Shared\Infrastructure\Symfony\DependencyInjection\Compiler\MessageFactoryCompilerPass;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessageFactoryCompilerPass());
    }
}
