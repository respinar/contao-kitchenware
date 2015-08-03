<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace kitchenware;


/**
 * Class ModuleKitchenware
 *
 * Parent class for kitchenware modules.
 * @copyright  Hamid Abbaszadeh 2014
 * @author     Hamid Abbaszadeh <https://respinar.com>
 * @package    Kitchenware
 */
abstract class ModuleKitchenware extends \Module
{

	/**
	 * URL cache array
	 * @var array
	 */
	private static $arrUrlCache = array();


	/**
	 * Sort out protected archives
	 * @param array
	 * @return array
	 */
	protected function sortOutProtected($arrCategories)
	{
		if (BE_USER_LOGGED_IN || !is_array($arrCategories) || empty($arrCategories))
		{
			return $arrCategories;
		}

		$this->import('FrontendUser', 'User');
		$objCategory = \KitchenwareCategoryModel::findMultipleByIds($arrCategories);
		$arrCategories = array();

		if ($objCategory !== null)
		{
			while ($objCategory->next())
			{
				if ($objCategory->protected)
				{
					if (!FE_USER_LOGGED_IN)
					{
						continue;
					}

					$groups = deserialize($objCategory->groups);

					if (!is_array($groups) || empty($groups) || !count(array_intersect($groups, $this->User->groups)))
					{
						continue;
					}
				}

				$arrCategories[] = $objCategory->id;
			}
		}

		return $arrCategories;
	}


	/**
	 * Parse an item and return it as string
	 * @param object
	 * @param boolean
	 * @param string
	 * @param integer
	 * @return string
	 */
	protected function parseProduct($objProduct, $blnAddCategory=false, $strClass='', $intCount=0)
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->product_template);
		$objTemplate->setData($objProduct->row());

		$objTemplate->class = (($this->product_Class != '') ? ' ' . $this->product_Class : '') . $strClass;

		if (time() - $objProduct->date < 2592000) {
			$objTemplate->new_product = true;
		}

		$objTemplate->features    = deserialize($objProduct->features);

		$objTemplate->link        = $this->generateProductUrl($objProduct, $blnAddCategory);

		$objTemplate->category    = $objProduct->getRelated('pid');

		$objTemplate->txt_features = $GLOBALS['TL_LANG']['MSC']['txt_features'];

		$objTemplate->count = $intCount; // see #5708

		$objTemplate->date = \Date::parse($objPage->datimFormat, $objProduct->date);
		$objTemplate->datetime = date('Y-m-d\TH:i:sP', $objProduct->date);

		$objTemplate->addImage = false;

		// Add an image
		if ($objProduct->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objProduct->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objProduct->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrProduct = $objProduct->row();

				// Override the default image size
				if ($this->imgSize != '')
				{
					$size = deserialize($this->imgSize);

					if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
					{
						$arrProduct['size'] = $this->imgSize;
					}
				}

				$arrProduct['singleSRC'] = $objModel->path;
				$arrProduct['fullsize'] = $this->fullsize;
				$strLightboxId = 'lightbox[lb' . $this->id . ']';
 				$this->addImageToTemplate($objTemplate, $arrProduct,null,$strLightboxId);
			}
		}

		return $objTemplate->parse();
	}


	/**
	 * Parse one or more items and return them as array
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parseProducts($objProducts, $blnAddCategory=false)
	{
		$limit = $objProducts->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrProducts = array();

		while ($objProducts->next())
		{
			$arrProducts[] = $this->parseProduct($objProducts, $blnAddCategory, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % $this->product_perRow) == 0) ? ' last_col' : '') . ((($count % $this->product_perRow) == 1) ? ' first_col' : ''), $count);
		}

		return $arrProducts;
	}


	/**
	 * Return the meta fields of a news Set as array
	 * @param object
	 * @return array
	 */
	protected function getMetaFields($objProduct)
	{
		$meta = deserialize($this->news_metaFields);

		if (!is_array($meta))
		{
			return array();
		}

		global $objPage;
		$return = array();

		foreach ($meta as $field)
		{
			switch ($field)
			{
				case 'date':
					$return['date'] = \Date::parse($objPage->datimFormat, $objProduct->date);
					break;

				case 'author':
					if (($objAuthor = $objProduct->getRelated('author')) !== null)
					{
						if ($objAuthor->google != '')
						{
							$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' <a href="https://plus.google.com/' . $objAuthor->google . '" rel="author" target="_blank">' . $objAuthor->name . '</a>';
						}
						else
						{
							$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
						}
					}
					break;

				case 'comments':
					if ($objProduct->noComments || $objProduct->source != 'default')
					{
						break;
					}
					$intTotal = \CommentsModel::countPublishedBySourceAndParent('tl_news', $objProduct->id);
					$return['ccount'] = $intTotal;
					$return['comments'] = sprintf($GLOBALS['TL_LANG']['MSC']['commentCount'], $intTotal);
					break;
			}
		}

		return $return;
	}


	/**
	 * Generate a URL and return it as string
	 * @param object
	 * @param boolean
	 * @return string
	 */
	protected function generateProductUrl($objItem, $blnAddCategory=false)
	{
		$strCacheKey = 'id_' . $objItem->id;

		// Load the URL from cache
		if (isset(self::$arrUrlCache[$strCacheKey]))
		{
			return self::$arrUrlCache[$strCacheKey];
		}

		// Initialize the cache
		self::$arrUrlCache[$strCacheKey] = null;

		// Link to the default page
		if (self::$arrUrlCache[$strCacheKey] === null)
		{
			$objPage = \PageModel::findByPk($objItem->getRelated('pid')->jumpTo);

			if ($objPage === null)
			{
				self::$arrUrlCache[$strCacheKey] = ampersand(\Environment::get('request'), true);
			}
			else
			{
				self::$arrUrlCache[$strCacheKey] = ampersand($this->generateFrontendUrl($objPage->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/' : '/items/') . ((!\Config::get('disableAlias') && $objItem->alias != '') ? $objItem->alias : $objItem->id)));
			}

		}

		return self::$arrUrlCache[$strCacheKey];
	}


	/**
	 * Generate a link and return it as string
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function generateLink($strLink, $objProduct, $blnAddCategory=false, $blnIsReadMore=false)
	{

		return sprintf('<a href="%s" title="%s">%s%s</a>',
						$this->generateProductUrl($objProduct, $blnAddCategory),
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objProduct->title), true),
						$strLink,
						($blnIsReadMore ? ' <span class="invisible">'.$objProduct->title.'</span>' : ''));

	}

	/**
	 * Parse one or more items and return them as array
	 * @param object
	 * @param boolean
	 * @return array
	 */
	protected function parsePieces($objPieces)
	{
		$limit = $objPieces->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrPieces = array();

		while ($objPieces->next())
		{
			$arrPieces[] = $this->parsePiece($objPieces, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % $this->piece_perRow) == 0) ? ' last_col' : '') . ((($count % $this->piece_perRow) == 1) ? ' first_col' : ''), $count);
		}

		return $arrPieces;
	}

	/**
	 * Generate a pieces as array
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function parsePiece($objPiece)
	{
		if ($objPiece == null)
		{
			return;
		}

		$objTemplate = new \FrontendTemplate($this->piece_template);
		$objTemplate->setData($objPiece->row());

		$objTemplate->class = (($this->piece_Class != '') ? ' ' . $this->piece_Class : '') . $strClass;


		$objTemplate->addImage = false;

		// Add an image
		if ($objPiece->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objPiece->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objPiece->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrPiece = $objPiece->row();

				// Override the default image size
				if ($this->piece_imgSize != '')
				{
					$size = deserialize($this->piece_imgSize);

					if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
					{
						$arrPiece['size'] = $this->piece_imgSize;
					}
				}

				$arrPiece['singleSRC'] = $objModel->path;
				$arrPiece['fullsize'] = $this->fullsize;
				$strLightboxId = 'lightbox[lb' . $this->id . ']';
 				$this->addImageToTemplate($objTemplate, $arrPiece,null,$strLightboxId);
			}
		}

		return $objTemplate->parse();
	}

	/**
	 * Generate a colors as array
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function parseTypes($objTypes)
	{
		$limit = $objTypes->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrTypes = array();

		while ($objTypes->next())
		{
			$arrTypes[] = $this->parseType($objTypes, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % $this->type_perRow) == 0) ? ' last_col' : '') . ((($count % $this->type_perRow) == 1) ? ' first_col' : ''), $count);
		}

		return $arrTypes;

	}

	/**
	 * Generate a colors as array
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function parseType($objType)
	{

		if ($objType == null)
		{
			return;
		}

		$objTemplate = new \FrontendTemplate($this->type_template);
		$objTemplate->setData($objType->row());

		$objTemplate->class = (($this->type_Class != '') ? ' ' . $this->type_Class : '') . $strClass;


		$objTemplate->addImage = false;

		// Add an image
		if ($objType->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objType->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objType->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrType = $objType->row();

				// Override the default image size
				if ($this->type_imgSize != '')
				{
					$size = deserialize($this->type_imgSize);

					if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
					{
						$arrType['size'] = $this->type_imgSize;
					}
				}

				$arrType['singleSRC'] = $objModel->path;
				$arrType['fullsize'] = $this->fullsize;
				$strLightboxId = 'lightbox[lb' . $this->id . ']';
 				$this->addImageToTemplate($objTemplate, $arrType,null,$strLightboxId);
			}
		}

		return $objTemplate->parse();


	}

	/**
	 * Generate a colors as array
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function parseRelateds($objProducts)
	{

	}

	/**
	 * Generate a colors as array
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function parseRelated($objProduct)
	{


	}


}
