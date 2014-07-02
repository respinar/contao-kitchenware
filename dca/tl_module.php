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

$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_menu']    = '{title_legend},name,headline,type;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_list']    = '{title_legend},name,headline,type;{kitchenware},kitchenware_category;{config_legend},kitchenware_featured,kitchenware_productModule;{template_legend},numberOfItems,perPage,imgSize,productClass;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_set']     = '{title_legend},name,headline,type;{template_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_related'] = '{title_legend},name,headline,type;{template_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_category'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_category'],
	'exclude'              => true,
	'inputType'            => 'radio',
	'foreignKey'           => 'tl_kitchenware_category.title',
	'eval'                 => array('multiple'=>false,'mandatory'=>true),
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
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_productModule'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_productModule'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_kitchenware', 'getProductModules'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['productClass'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['productClass'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

class tl_module_kitchenware extends Backend
{

	/**
	 * Get all product detail modules and return them as array
	 * @return array
	 */
	public function getProductModules()
	{
		$arrModules = array();
		$objModules = $this->Database->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='kitchenware_product' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

}

