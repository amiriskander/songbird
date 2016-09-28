<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
* This is the class that loads and manages your bundle configuration
*
* To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
*/
class AppExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * http://symfony.com/doc/current/bundles/prepend_extension.html
     */
	public function prepend(ContainerBuilder $container)
	{
		// doctrine config
		$doctrine = [];
		$doctrine['orm']['resolve_target_entities']['Bpeh\NestablePageBundle\Model\PageBase'] = 'AppBundle\Entity\Page';
		$doctrine['orm']['resolve_target_entities']['Bpeh\NestablePageBundle\Model\PageMetaBase'] = 'AppBundle\Entity\PageMeta';
		$container->prependExtensionConfig('doctrine', $doctrine);
		// fos config
		$fosuser = [];
		$fosuser['db_driver'] = 'orm';
		$fosuser['firewall_name'] = 'main';
		$fosuser['user_class'] = 'AppBundle\Entity\User';
		$container->prependExtensionConfig('fos_user', $fosuser);
		# Nestable Page Configuration
		$page = [];
		$page['page_entity'] = 'AppBundle\Entity\Page';
		$page['pagemeta_entity'] = 'AppBundle\Entity\PageMeta';
		$page['page_form_type'] = 'AppBundle\Form\PageType';
		$page['pagemeta_form_type'] = 'AppBundle\Form\PageMetaType';
		$container->prependExtensionConfig('bpeh_nestable_page', $page);
	}
}