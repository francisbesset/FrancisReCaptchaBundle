<?php

namespace Francis\Bundle\ReCaptchaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class FrancisReCaptchaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.xml');
        $loader->load('recaptcha.xml');
        $loader->load('validator.xml');

        $container->setParameter('francis_recaptcha.site_key', $config['site_key']);
        $container->setParameter('francis_recaptcha.secret_key', $config['secret_key']);

        $resources = $container->getParameter('twig.form.resources');
        $resources[] = '@FrancisReCaptcha/Form/recaptcha_layout.html.twig';
        $container->setParameter('twig.form.resources', $resources);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'francis_recaptcha';
    }
}
