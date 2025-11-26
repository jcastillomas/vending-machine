<?php

declare(strict_types=1);

namespace VM\Shared\Infrastructure\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class MessageFactoryCompilerPass implements CompilerPassInterface
{
    private const MESSAGE_TAG = 'message';
    private const MESSAGE_FACTORY_SERVICE = 'message.message_factory';

    public function __construct()
    {
    }

    public function process(ContainerBuilder $container): void
    {
        $this->configureMessageFactoryMessagesMap($container);
    }

    private function configureMessageFactoryMessagesMap(ContainerBuilder $container): void
    {
        $messageFactory = $this->findMessageFactoryService($container);
        $messages = $this->findMessages($container);
        $messageFactory->addMethodCall('addMessagesToMap', [array_keys($messages)]);
    }

    private function findMessageFactoryService(ContainerBuilder $container): Definition
    {
        return $container->findDefinition(self::MESSAGE_FACTORY_SERVICE);
    }

    private function findMessages(ContainerBuilder $container): array
    {
        return $container->findTaggedServiceIds(self::MESSAGE_TAG);
    }
}
