<?php
/**
 * @version      5.1.1 13.04.2025
 * @author       MAXXmarketing GmbH
 * @package      Jshopping
 * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
 * @license      GNU/GPL
 */

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Jshoppingproducts\Product_canonical\Extension\Product_canonical;

defined('_JEXEC') or die;

return new class () implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.3.0
	 */
	public function register(Container $container)
	{
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$plugin     = new Product_canonical(
					$container->get(DispatcherInterface::class),
					(array) PluginHelper::getPlugin('jshoppingproducts', 'product_canonical')
				);
				$plugin->setApplication(Factory::getApplication());

				return $plugin;
			}
		);
	}
};