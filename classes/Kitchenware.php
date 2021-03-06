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
 * Class Kitchenware
 *
 * @copyright  2014
 * @author     Hamid Abbaszadeh
 * @package    Devtools
 */
class Kitchenware extends \Frontend
{

	/**
	 * Add news items to the indexer
	 * @param array
	 * @param integer
	 * @param boolean
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$time = time();
		$arrProcessed = array();

		// Get all news archives
		$objCategory = \KitchenwareCategoryModel::findByProtected('');

		// Walk through each archive
		if ($objCategory !== null)
		{
			while ($objCategory->next())
			{
				// Skip news archives without target page
				if (!$objCategory->jumpTo)
				{
					continue;
				}

				// Skip news archives outside the root nodes
				if (!empty($arrRoot) && !in_array($objCategory->jumpTo, $arrRoot))
				{
					continue;
				}

				// Get the URL of the jumpTo page
				if (!isset($arrProcessed[$objCategory->jumpTo]))
				{
					$objParent = \PageModel::findWithDetails($objCategory->jumpTo);

					// The target page does not exist
					if ($objParent === null)
					{
						continue;
					}

					// The target page has not been published (see #5520)
					if (!$objParent->published || ($objParent->start != '' && $objParent->start > $time) || ($objParent->stop != '' && $objParent->stop < $time))
					{
						continue;
					}

					// The target page is exempt from the sitemap (see #6418)
					if ($blnIsSitemap && $objParent->sitemap == 'map_never')
					{
						continue;
					}

					// Set the domain (see #6421)
					$domain = ($objParent->rootUseSSL ? 'https://' : 'http://') . ($objParent->domain ?: \Environment::get('host')) . TL_PATH . '/';

					// Generate the URL
					$arrProcessed[$objCategory->jumpTo] = $domain . $this->generateFrontendUrl($objParent->row(), ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ?  '/%s' : '/items/%s'), $objParent->language);
				}

				$strUrl = $arrProcessed[$objCategory->jumpTo];

				// Get the items
				$objProduct = \KitchenwareProductModel::findPublishedByPid($objCategory->id);

				if ($objProduct !== null)
				{
					while ($objProduct->next())
					{
						$arrPages[] = $this->getLink($objProduct, $strUrl);
					}
				}
			}
		}

		return $arrPages;
	}

    /**
	 * Return the link of a news article
	 * @param object
	 * @param string
	 * @param string
	 * @return string
	 */
	protected function getLink($objItem, $strUrl, $strBase='')
	{
		return $strBase . sprintf($strUrl, (($objItem->alias != '' && !\Config::get('disableAlias')) ? $objItem->alias : $objItem->id));
	}

	/**
	 * Translate the URL parameters using the changelanguage module hook
	 *
	 * @param	array
	 * @param	string
	 * @param	array
	 * @return	array
	 * @see		ModuleChangeLanguage::compile()
	 */
	public function translateUrlParameters($arrGet, $strLanguage, $arrRootPage)
	{
		// Set the item from the auto_item parameter
		if ($GLOBALS['TL_CONFIG']['useAutoItem'] && isset($_GET['auto_item']))
		{
			$this->Input->setGet('items', $this->Input->get('auto_item'));
		}

		$strItem = $this->Input->get('items');

        if ($strItem != '')
        {
        	$objProduct = $this->Database->prepare("SELECT tl_kitchenware_product.*, tl_kitchenware_category.master FROM tl_kitchenware_product LEFT OUTER JOIN tl_kitchenware_category ON tl_kitchenware_product.pid=tl_kitchenware_category.id WHERE tl_kitchenware_product.id=? OR tl_kitchenware_product.alias=?")
        							  ->limit(1)
        							  ->execute((int)$strItem, $strItem);

        	// We found a product item!!
        	if ($objProduct->numRows)
        	{
        		$id = ($objProduct->master > 0) ? $objProduct->languageMain : $objProduct->id;
        		$objItem = $this->Database->prepare("SELECT tl_kitchenware_product.id, tl_kitchenware_product.alias FROM tl_kitchenware_product LEFT OUTER JOIN tl_kitchenware_category ON tl_kitchenware_product.pid=tl_kitchenware_category.id WHERE tl_kitchenware_category.language=? AND (tl_kitchenware_product.id=? OR languageMain=?)")->execute($strLanguage, $id, $id);

				if ($objItem->numRows)
				{
					$arrGet['url']['items'] = $objItem->alias ? $objItem->alias : $objItem->id;
				}
        	}
        }

		return $arrGet;
	}
}
