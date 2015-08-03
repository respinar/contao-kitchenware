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
namespace kitchenware;


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

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['mod_kitchenware_detail'][0]) . ' ###';
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

		$this->Template->product = '';
		$this->Template->referer = 'javascript:history.go(-1)';
		$this->Template->back =     $GLOBALS['TL_LANG']['MSC']['goBack'];

		$this->Template->txt_types  = $GLOBALS['TL_LANG']['MSC']['types'];
		$this->Template->txt_pieces = $GLOBALS['TL_LANG']['MSC']['pieces'];


		// Generate products
		$objKitchenwareProduct = \KitchenwareProductModel::findPublishedByParentAndIdOrAlias(\Input::get('items'),$this->kitchenware_categories);

		$arrKitchenwareProduct = $this->parseProduct($objKitchenwareProduct);

		$objPage->pageTitle   = strip_tags(strip_insert_tags($objKitchenwareProduct->title));
		$objPage->description = strip_tags(strip_insert_tags($objKitchenwareProduct->description));

		$this->Template->product = $arrKitchenwareProduct;


		// Generate pieces
		if ($this->piece_show)
		{
			$objKitchenwarePieces = \KitchenwarePieceModel::findPublishedByPid($objKitchenwareProduct->id);

			if ($objKitchenwarePieces !== null)
			{
				$this->Template->pieces = $this->parsePieces($objKitchenwarePieces);
			}
		}

		// Generate types
		if ($this->type_show)
		{
			$objKitchenwareTypes = \KitchenwareTypeModel::findPublishedByPid($objKitchenwareProduct->id);

			if ($objKitchenwareTypes !== null)
			{
				$this->Template->types = $this->parseTypes($objKitchenwareTypes);
			}
		}

	}
}
