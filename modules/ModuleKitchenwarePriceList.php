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
 * Class ModuleKitchenwareList
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class ModuleKitchenwarePriceList extends \ModuleKitchenware
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_kitchenware_pricelist';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['kitchenware_pricelist'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$objKitchenware = $this->Database->prepare("SELECT * FROM tl_kitchenware WHERE id=?")->execute($this->kitchenware);

		$objKitchenwareSet = $this->Database->prepare("SELECT * FROM tl_kitchenware_set WHERE published=1 AND pid=? ORDER BY sorting")->execute($this->kitchenware);

		// Return if no products were found
		if (!$objKitchenwareSet->numRows)
		{
			$this->Template = new \FrontendTemplate('mod_kitchwenware_set_empty');
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['emptyKichenwareSet'];
			return;
		}

		$strLink = '';

		// Generate a jumpTo link
		if ($objKitchenware->jumpTo > 0)
		{
			$objJump = \PageModel::findByPk($objKitchenware->jumpTo);

			if ($objJump !== null)
			{
				$strLink = $this->generateFrontendUrl($objJump->row(), ($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/%s' : '/items/%s'));
			}
		}

		$arrKitchenwareList = array();

		while ($objKitchenwareSet->next())
		{

			$strImage = '';
			$objImage = \FilesModel::findByPk($objKitchenwareSet->image);

			// Add photo image
			if ($objImage !== null)
			{
				$strImage = \Image::getHtml(\Image::get($objImage->path, '300', '300', 'center_center'));
			}

			$arrKitchenwareList[] = array
			(
				'title' => $objKitchenwareSet->title,
				'model' => $objKitchenwareSet->model,
				'image' => $strImage,
				'link'  => strlen($strLink) ? sprintf($strLink, $objKitchenwareSet->alias) : ''
			);
		}

		$this->Template->kitchenwarelist = $arrKitchenwareList;

	}
}
