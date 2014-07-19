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
class ModuleKitchenwareDetail extends \Module
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

		// Return if there are no items
		if (!\Input::get('items'))
		{
			return '';
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{

		$objKitchenwareSet = $this->Database->prepare("SELECT * FROM tl_kitchenware_set WHERE alias=?")->execute(\Input::get('items'));

		$objKitchenwareElement = $this->Database->prepare("SELECT * FROM tl_kitchenware_element WHERE published=1 AND pid=?")->execute($objKitchenwareSet->id);

		$this->Template->title       = $objKitchenwareSet->title;
		$this->Template->code        = $objKitchenwareSet->code;
		$this->Template->warranty    = $objKitchenwareSet->warranty;
		$this->Template->warranty    = $objKitchenwareSet->warranty;
		$this->Template->base        = $objKitchenwareSet->base;
		$this->Template->lids        = $objKitchenwareSet->lids;
		$this->Template->handle      = $objKitchenwareSet->handle;
		$this->Template->surface     = $objKitchenwareSet->surface;
		$this->Template->colors      = $objKitchenwareSet->colors;
		$this->Template->features    = deserialize($objKitchenwareSet->features);
		$this->Template->description = $objKitchenwareSet->description;
		$this->Template->standard    = $objKitchenwareSet->standard;

		if ($this->kitchenware_price) {
			$this->Template->price   = number_format($objKitchenwareSet->price);
		}

		$strImage = '';
		$objImage = \FilesModel::findByPk($objKitchenwareSet->singleSRC);

		// Add photo image
		if ($objImage !== null)
		{
			$size = deserialize($this->imgSize);
			$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]),$objKitchenwareSet->title);
		}

		$this->Template->image = $strImage;
		$this->Template->imagepath = $objImage->path;


		$arrKitchenwareElement = array();

		$size = deserialize($this->itemImageSize);

		while($objKitchenwareElement->next())
		{
			$strImage = '';
			$objImage = \FilesModel::findByPk($objKitchenwareElement->singleSRC);

			// Add photo image
			if ($objImage !== null)
			{
				$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]),$objKitchenwareElement->title);
			}

			$arrKitchenwareElement[] = array
			(
				'title'       => $objKitchenwareElement->title,
				'model'       => $objKitchenwareElement->model,
				'dimensions'  => $objKitchenwareElement->dimensions,
				'capacity'    => $objKitchenwareElement->capacity,
				'description' => $objKitchenwareElement->description,
				'image'       => $strImage,
			);
		}

		$this->Template->elements = $arrKitchenwareElement;

	}
}
