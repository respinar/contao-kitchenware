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
namespace Kitchenware;


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
		$objCategory = \KitchenwareModel::findMultipleByIds($arrCategories);
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
	protected function parseSet($objSet, $blnAddCategory=false, $strClass='', $intCount=0)
	{
		global $objPage;

		$objTemplate = new \FrontendTemplate($this->set_template);
		$objTemplate->setData($objSet->row());

		$objTemplate->class = (($this->setClass != '') ? ' ' . $this->setClass : '') . $strClass;
		$objTemplate->elementClass = $this->elementClass;
		$objTemplate->colorClass   = $this->colorClass;

		$objTemplate->title       = $objSet->title;
		$objTemplate->code        = $objSet->code;
		$objTemplate->warranty    = $objSet->warranty;
		$objTemplate->base        = $objSet->base;
		$objTemplate->lids        = $objSet->lids;
		$objTemplate->handle      = $objSet->handle;
		$objTemplate->surface     = $objSet->surface;
		$objTemplate->features    = deserialize($objSet->features);
		$objTemplate->description = $objSet->description;

		$objTemplate->link        = $this->generateSetUrl($objSet, $blnAddCategory);
		$objTemplate->more        = $this->generateLink($GLOBALS['TL_LANG']['MSC']['moredetail'], $objSet, $blnAddCategory, true);

		$objTemplate->elements    = $this->parseElement($objSet);
		$objTemplate->colors      = $this->parseColor($objSet);

		$objTemplate->category    = $objSet->getRelated('pid');

		$objTemplate->count = $intCount; // see #5708
		$objTemplate->text = '';

		$objTemplate->date = \Date::parse($objPage->datimFormat, $objSet->date);
		$objTemplate->datetime = date('Y-m-d\TH:i:sP', $objSet->date);

		$objTemplate->addImage = false;

		// Add an image
		if ($objSet->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($objSet->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($objSet->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303)
				$arrSet = $objSet->row();

				// Override the default image size
				if ($this->imgSize != '')
				{
					$size = deserialize($this->imgSize);

					if ($size[0] > 0 || $size[1] > 0 || is_numeric($size[2]))
					{
						$arrSet['size'] = $this->imgSize;
					}
				}

				$arrSet['singleSRC'] = $objModel->path;
				$arrSet['fullsize'] = $this->fullsize;
				$strLightboxId = 'lightbox[lb' . $this->id . ']';
 				$this->addImageToTemplate($objTemplate, $arrSet,null,$strLightboxId);
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
	protected function parseSets($objSets, $blnAddCategory=false)
	{
		$limit = $objSets->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrSets = array();

		while ($objSets->next())
		{
			$arrSets[] = $this->parseSet($objSets, $blnAddCategory, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
		}

		return $arrSets;
	}


	/**
	 * Return the meta fields of a news Set as array
	 * @param object
	 * @return array
	 */
	protected function getMetaFields($objSet)
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
					$return['date'] = \Date::parse($objPage->datimFormat, $objSet->date);
					break;

				case 'author':
					if (($objAuthor = $objSet->getRelated('author')) !== null)
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
					if ($objSet->noComments || $objSet->source != 'default')
					{
						break;
					}
					$intTotal = \CommentsModel::countPublishedBySourceAndParent('tl_news', $objSet->id);
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
	protected function generateSetUrl($objItem, $blnAddCategory=false)
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
	protected function generateLink($strLink, $objSet, $blnAddCategory=false, $blnIsReadMore=false)
	{

		return sprintf('<a href="%s" title="%s">%s%s</a>',
						$this->generateSetUrl($objSet, $blnAddCategory),
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objSet->title), true),
						$strLink,
						($blnIsReadMore ? ' <span class="invisible">'.$objSet->title.'</span>' : ''));

	}

	/**
	 * Generate a elements as array
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function parseElement($objSet)
	{
		$objElement = \KitchenwareElementModel::findPublishedByPid($objSet->id);

		if ($objElement == null)
		{
			return;
		}

		$arrElement = array();

		$size = deserialize($this->elementImageSize);

		while($objElement->next())
		{
			$strImage = '';
			$objImage = \FilesModel::findByPk($objElement->singleSRC);

			// Add photo image
			if ($objImage !== null)
			{
			$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]),$objElement->title);
			}

			$arrElement[] = array
			(
				'title'       => $objElement->title,
				'model'       => $objElement->model,
				'dimensions'  => $objElement->dimensions,
				'capacity'    => $objElement->capacity,
				'description' => $objElement->description,
				'image'       => $strImage,
			);
		}

		return $arrElement;

	}

	/**
	 * Generate a colors as array
	 * @param string
	 * @param object
	 * @param boolean
	 * @param boolean
	 * @return string
	 */
	protected function parseColor($objSet)
	{
		$objColor = \KitchenwareColorModel::findPublishedByPid($objSet->id);

		if ($objColor == null)
		{
			return;
		}

		$arrColor = array();

		$size = deserialize($this->colorImageSize);

		while($objColor->next())
		{
			$strImage = '';
			$objImage = \FilesModel::findByPk($objColor->singleSRC);

			// Add photo image
			if ($objImage !== null)
			{
			$strImage = \Image::getHtml(\Image::get($objImage->path, $size[0], $size[1], $size[2]),$objColor->title);
			}

			$arrColor[] = array
			(
				'title'       => $objColor->title,
				'image'       => $strImage,
			);
		}

		return $arrColor;

	}


}
