<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\DependencyInjection;

use DawBed\OperationLimitBundle\Service\OperationLimitService;
use DawBed\UserRegistrationConfirmationBundle\Model\Mail\Confirmation;
use DawBed\UserRegistrationConfirmationBundle\Model\OperationLimit;
use DawBed\UserRegistrationConfirmationBundle\Service\ContextFactoryService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class UserRegistrationConfirmationExtension extends Extension implements PrependExtensionInterface
{
    const ALIAS = 'dawbed_user_registration_confirmation_bundle';

    public function prepend(ContainerBuilder $container): void
    {
        $loader = $this->prepareLoader($container);
        $loader->load('packages/context_bundle.yaml');
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->setParameter('bundle_dir', dirname(__DIR__));
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter(Configuration::TOKEN_EXPIRED_TIME_NODE, $config[Configuration::TOKEN_EXPIRED_TIME_NODE]);
        $loader = $this->prepareLoader($container);
        $loader->load('services.yaml');
        $this->prepareConfirmationMail($config['confirmation_mail'], $container);
        $this->prepareOperationLimit($config['operation_limit_per_account'], $container);
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    private function prepareConfirmationMail($config, ContainerBuilder $container)
    {
        $confirmationMailDefinition = new Definition(Confirmation::class, [
            $config['email'],
            $config['template'],
            new Reference('templating')
        ]);
        $confirmationMailDefinition->setPublic(true);
        $container->setDefinition(Confirmation::class, $confirmationMailDefinition);
    }

    private function prepareOperationLimit($config, ContainerBuilder $container)
    {
        $container->setDefinition(OperationLimit::class, new Definition(OperationLimit::class, [
            new Reference(ContextFactoryService::class),
            new Reference(OperationLimitService::class),
            $config['allowed'],
            $config['on_time'],
            $config['for_time']
        ]));
    }

    private function prepareLoader(ContainerBuilder $containerBuilder): YamlFileLoader
    {
        return new YamlFileLoader($containerBuilder, new FileLocator(dirname(__DIR__) . '/Resources/config'));
    }

}