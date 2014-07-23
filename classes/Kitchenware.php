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
		$objCategory = \KitchenwareModel::findByProtected('');

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
				$objSet = \KitchenwareSetModel::findPublishedByPid($objCategory->id);

				if ($objSet !== null)
				{
					while ($objSet->next())
					{
						$arrPages[] = $this->getLink($objSet, $strUrl);
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
}
