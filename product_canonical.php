<?php
defined('_JEXEC') or die('Restricted access');
class plgJshoppingProductsProduct_canonical extends JPlugin{
    
    function __construct(&$subject, $config){
        parent::__construct($subject, $config);
    } 

    public function onBeforeDisplayProduct(&$product){
        $document = \JFactory::getDocument();
        $maincategory_id = $product->getCategory();
        $product_id = \JFactory::getApplication()->input->getInt('product_id');
        $category_id = \JFactory::getApplication()->input->getInt('category_id');
		
		$uri = \JURI::getInstance();        
        $liveurlhost = $uri->toString(array("scheme",'host', 'port'));

		if ($category_id != $maincategory_id || $this->params->get('hidemain', '1') == 0) {
            $url = $liveurlhost.\JSHelper::SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$maincategory_id.'&product_id='.$product_id);
            $document->addCustomTag('<link rel="canonical" href="'.$url.'"/>');
        }
    }
    
    public function onListProductUpdateDataProduct(&$products, &$key, &$value, &$use_userdiscount) {       
        if ($this->params->get('dis_generate_main_url', '1')) {
            $products[$key]->main_category_id = 0;
        }
    }
}