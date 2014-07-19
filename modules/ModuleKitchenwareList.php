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
class ModuleKitchenwareList extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_kitchenware_list';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['customers_detail'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Show the kitchenware detail if an item has been selected
		if ($this->kitchenware_detailModule > 0 && (isset($_GET['items']) || ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))))
		{
			return $this->getFrontendModule($this->kitchenware_detailModule, $this->strColumn);
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$objKitchenware = $this->Database->prepare("SELECT * FROM tl_kitchenware WHERE id=?")->execute($this->kitchenware);

		$this->Template->kitchenwaretitle = $objKitchenware->title;

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

		$size = deserialize($this->imgSize);

		while ($objKitchenwareSet->next())
		{

			$strImage = '';
			$objImage = \FilesModel::findByPk($objKitchenwareSet->singleSRC);

			// Add photo image
			if ($objImage !== null)
			{
				$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]),$objKitchenwareSet->title);
			}

			if ($this->kitchenware_price) {
				$price   = number_format($objKitchenwareSet->price);
			}

			$arrKitchenwareList[] = array
			(
				'title' => $objKitchenwareSet->title,
				'model' => $objKitchenwareSet->model,
				'price' => $price,
				'image' => $strImage,
				'link'  => strlen($strLink) ? sprintf($strLink, $objKitchenwareSet->alias) : ''

			);
		}

		$this->Template->kitchenwarelist = $arrKitchenwareList;

	}
}
