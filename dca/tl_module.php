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
 * Add palettes to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_list']      = '{title_legend},name,headline,type;{category_legend},kitchenware_categories;{config_legend},kitchenware_featured,kitchenware_detailModule;{meta_legend},set_template,kitchenware_price;{template_legend},numberOfItems,perPage,imgSize,setClass;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_detail']    = '{title_legend},name,headline,type;{category_legend},kitchenware_categories;{template_legend:hide},set_template,imgSize,fullsize;{item_legend},colorImageSize,colorClass,elementImageSize,elementClass;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_categories'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_category'],
	'exclude'              => true,
	'inputType'            => 'checkbox',
	'options_callback'        => array('tl_module_kitchenware', 'getKitchenwareCategory'),
	'eval'                 => array('multiple'=>true,'mandatory'=>true),
    'sql'                  => "blob NULL"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_featured'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_featured'],
	'default'                 => 'all',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('all', 'featured', 'unfeatured'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(20) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_price'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_price'],
	'exclude'                 => true,
	'flag'                    => 1,
	'inputType'               => 'checkbox',
	'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_detailModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_detailModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_kitchenware', 'getDetailModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['setClass'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['setClass'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['elementClass'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['elementClass'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['elementImageSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['elementImageSize'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => $GLOBALS['TL_CROP'],
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['colorClass'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['colorClass'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['colorImageSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['colorImageSize'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => $GLOBALS['TL_CROP'],
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['set_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['set_template'],
	'default'                 => 'set_full',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_kitchenware', 'getSetTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);

class tl_module_kitchenware extends Backend
{

	/**
	 * Get all news archives and return them as array
	 * @return array
	 */
	public function getKitchenwareCategory()
	{
		//if (!$this->User->isAdmin && !is_array($this->User->news))
		//{
		//	return array();
		//}

		$arrArchives = array();
		$objArchives = $this->Database->execute("SELECT id, title FROM tl_kitchenware_category ORDER BY title");

		while ($objArchives->next())
		{
			//if ($this->User->hasAccess($objArchives->id, 'news'))
			//{
				$arrArchives[$objArchives->id] = $objArchives->title;
			//}
		}

		return $arrArchives;
	}

	/**
	 * Get all product detail modules and return them as array
	 * @return array
	 */
	public function getDetailModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='kitchenware_detail' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

	/**
	 * Return all set templates as array
	 * @return array
	 */
	public function getSetTemplates()
	{
		return $this->getTemplateGroup('product_');
	}

}

