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
 * Class ModuleKitchenwareMenu
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Kitchenware
 */
class ModuleKitchenwareMenu extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_kitchenware_menu';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['kitchenware_list'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Show the customers detail if an item has been selected
		if ($this->customers_detailModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->customers_detailModule, $this->strColumn);
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$objKitchenware = $this->Database->prepare("SELECT * FROM tl_kitchenware")->execute();

		//$objKitchenwareSet = $this->Database->prepare("SELECT * FROM tl_kitchenware_set WHERE published=1 ORDER BY sorting")->execute();

		// Return if no Catalog were found
		if (!$objKitchenware->numRows)
		{
			$this->Template = new \FrontendTemplate('mod_kitchenware_menu_empty');
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyKitchenwareMenu'];
			return;
		}



		$arrKitchenwareMenu = array();

		// Generate Kitchenware Menu
		while ($objKitchenware->next())
		{

			$strLink = '';
			// Generate a jumpTo link
			if ($objKitchenware->jumpTo > 0)
			{
				$objJump = \PageModel::findByPk($objKitchenware->jumpTo);
				if ($objJump !== null)
				{
					$strLink = $this->generateFrontendUrl($objJump->row());
				}
			}

			$arrKitchenwareMenu[] = array
			(
				'title' => $objKitchenware->title,
				'link'  => $strLink,
			);
		}

		$this->Template->kitchenwaremenu = $arrKitchenwareMenu;

	}
}
