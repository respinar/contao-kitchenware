<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   Kitchenware
 * @author    Hamid Abbaszadeh
 * @license   GNU/LGPL
 * @copyright 2014
 */


/**
 * Namespace
 */
namespace Kitchenware;


/**
 * Class ModuleKitchenwareSet
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModuleKitchenwareDetail extends \ModuleKitchenware
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_kitchenware_detail';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['kitchenware_detail'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && $GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			\Input::setGet('items', \Input::get('auto_item'));
		}

		$this->kitchenware_categories = $this->sortOutProtected(deserialize($this->kitchenware_categories));

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{

		global $objPage;

		$this->Template->sets = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

		$objKitchenwareSet = \KitchenwareSetModel::findPublishedByParentAndIdOrAlias(\Input::get('items'),$this->kitchenware_categories);

		$arrKitchenwareSet = $this->parseSet($objKitchenwareSet);

		$objPage->pageTitle   = strip_tags(strip_insert_tags($objKitchenwareSet->title));
		$objPage->description = strip_tags(strip_insert_tags($objKitchenwareSet->description));
		$GLOBALS['TL_KEYWORDS'] .= (($GLOBALS['TL_KEYWORDS'] != '') ? ', ' : '') . strip_tags(strip_insert_tags($objKitchenwareSet->keywords));
		//$objPage->keywords    = 'hello';//strip_tags(strip_insert_tags($objKitchenwareSet->keywords));

		$this->Template->sets = $arrKitchenwareSet;

	}
}
