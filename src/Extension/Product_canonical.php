<?php
/**
 * @version      5.1.1 13.04.2025
 * @author       MAXXmarketing GmbH
 * @package      Jshopping
 * @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
 * @license      GNU/GPL
 */

namespace Joomla\Plugin\Jshoppingproducts\Product_canonical\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Component\Jshopping\Site\Helper\Helper;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Uri\Uri;

use function defined;

defined('_JEXEC') or die('Restricted access');

class Product_canonical extends CMSPlugin implements SubscriberInterface
{
	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onBeforeDisplayProduct'         => 'onBeforeDisplayProduct',
			'onListProductUpdateDataProduct' => 'onListProductUpdateDataProduct',
		];
	}

	/**
	 * @param   Event  $event
	 *
	 * @see   \Joomla\Component\Jshopping\Site\Controller\ProductController::display
	 *
	 * @since 1.0.0
	 */
	public function onBeforeDisplayProduct(Event $event): void
	{
		/**
		 * @var $product
		 */
		[$product] = array_values($event->getArguments());
		$app             = $this->getApplication();
		$document        = $app->getDocument();
		$maincategory_id = $product->getCategory();
		$product_id      = $app->getInput()->getInt('product_id');
		$category_id     = $app->getInput()->getInt('category_id');

		$uri         = Uri::getInstance();
		$liveurlhost = $uri->toString(['scheme', 'host', 'port']);

		if ($category_id != $maincategory_id || $this->params->get('hidemain', '1') == 0)
		{
			$url = $liveurlhost . Helper::SEFLink(
					'index.php?option=com_jshopping&controller=product&task=view&category_id=' . $maincategory_id . '&product_id=' . $product_id
				);
			$document->addCustomTag('<link rel="canonical" href="' . $url . '"/>');
		}
	}

	/**
	 * @param   Event  $event
	 *
	 * @see   Helper::listProductUpdateData
	 *
	 * @since 1.0.0
	 */
	public function onListProductUpdateDataProduct(Event $event): void
	{
		[$products, $key, $value, $use_userdiscount] = array_values($event->getArguments());

		if ($this->params->get('dis_generate_main_url', '1'))
		{
			$products[$key]->main_category_id = 0;
		}

		$event->setArgument(0, $products);
	}
}