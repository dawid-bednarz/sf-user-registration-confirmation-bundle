<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\UserRegistrationConfirmationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const TOKEN_EXPIRED_TIME_NODE = 'tokenExpiredTime';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(UserRegistrationConfirmationExtension::ALIAS);
        $this->confirmationEmail($rootNode);
        $this->tokenExpiredTime($rootNode);
        $this->operationLimit($rootNode);

        return $treeBuilder;
    }

    private function tokenExpiredTime(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
            ->scalarNode(self::TOKEN_EXPIRED_TIME_NODE)
            ->isRequired()
            ->end();
    }

    private function confirmationEmail(ArrayNodeDefinition $rootNode): void
    {
        $confirmationEmail = $rootNode
            ->children()
            ->arrayNode('confirmation_mail')
            ->isRequired()
            ->children();
        $confirmationEmail
            ->scalarNode('email')
            ->end();
        $confirmationEmail
            ->scalarNode('template')
            ->end();
    }

    private function operationLimit(ArrayNodeDefinition $rootNode): void
    {
        $repeatedOperationLimit = $rootNode
            ->children()
            ->arrayNode('operation_limit_per_account')
            ->addDefaultsIfNotSet()
            ->children();
        $repeatedOperationLimit
            ->scalarNode('allowed')
            ->defaultValue(1)
            ->end();
        $repeatedOperationLimit
            ->scalarNode('on_time')
            ->defaultValue('PT10M')
            ->end();
        $repeatedOperationLimit
            ->scalarNode('for_time')
            ->defaultValue('PT30M')
            ->end();
    }
}