<?php

namespace Francis\Bundle\ReCaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const DEFAULT_SITE_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    const DEFAULT_SECRET_KEY = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('francis_recaptcha');

        $rootNode
            ->children()
                ->scalarNode('site_key')
                    ->cannotBeEmpty()
                    ->treatNullLike(self::DEFAULT_SITE_KEY)
                    ->defaultValue(self::DEFAULT_SITE_KEY)
                ->end()
                ->scalarNode('secret_key')
                    ->cannotBeEmpty()
                    ->treatNullLike(self::DEFAULT_SECRET_KEY)
                    ->defaultValue(self::DEFAULT_SECRET_KEY)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
