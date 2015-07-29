<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   Kitchenware
 * @author    Hamid Abbaszadeh
 * @license   GNU/LGPL
 * @copyright 2015
 */

/**
 * Add palettes to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_list']      = '{title_legend},name,headline,type;
                                                                        {kitchenware_legend},kitchenware_categories;
                                                                        {template_legend},kitchenware_detailModule,customTpl,kitchenware_featured;
                                                                        {config_legend},kitchenware_metaFields;
                                                                        {kitchenware_legend},kitchenware_sortBy,numberOfItems,perPage,skipFirst;
                                                                        {product_legend},product_template,imgSize,product_perRow,product_Class;
                                                                        {protected_legend:hide},protected;
                                                                        {expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['kitchenware_detail']    = '{title_legend},name,headline,type;
                                                                        {kitcehenware_legend},kitchenware_categories;
                                                                        {config_legend},kitchenware_metaFields;
                                                                        {product_legend},product_template,imgSize,fullsize;
                                                                        {type_legend},type_show,type_template,type_perRow,type_Class,type_imgSize;
                                                                        {element_legend},element_show,element_template,element_perRow,element_Class,element_imgSize;
                                                                        {related_legend},related_show,related_template,related_perRow,related_Class,related_imgSize;
                                                                        {protected_legend:hide},protected;
                                                                        {expert_legend:hide},guests,cssID,space';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_categories'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_categories'],
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
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_metaFields'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_metaFields'],
	'default'                 => array('date'),
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'options'                 => array('date','price','rating'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('multiple'=>true),
	'sql'                     => "varchar(255) NOT NULL default ''"
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
$GLOBALS['TL_DCA']['tl_module']['fields']['kitchenware_sortBy'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kitchenware_sortBy'],
	'default'                 => 'custom',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('custom','title_asc', 'title_desc', 'date_asc', 'date_desc'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(16) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['product_Class'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['product_Class'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['product_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['product_template'],
	'default'                 => 'set_full',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_kitchenware', 'getProductTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['product_perRow'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['product_perRow'],
	'default'              => '4',
	'exclude'              => true,
	'inputType'            => 'select',
	'options'              => array('1','2','3','4','6','12'),
	'eval'                 => array('tl_class'=>'w50'),
    'sql'                  => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['element_show'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['element_show'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array(),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['element_template'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['element_template'],
	'default'              => 'product_element',
	'exclude'              => true,
	'inputType'            => 'select',
	'options_callback'     => array('tl_module_kitchenware', 'getProductTemplates'),
	'eval'                 => array('tl_class'=>'w50'),
    'sql'                  => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['element_perRow'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['element_perRow'],
	'default'              => '4',
	'exclude'              => true,
	'inputType'            => 'select',
	'options'              => array('1','2','3','4','6','12'),
	'eval'                 => array('tl_class'=>'w50'),
    'sql'                  => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['element_Class'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['element_Class'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['element_imgSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['element_imgSize'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => System::getImageSizes(),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['type_show'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['type_show'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array(),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['type_template'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['type_template'],
	'default'              => 'product_type',
	'exclude'              => true,
	'inputType'            => 'select',
	'options_callback'     => array('tl_module_kitchenware', 'getProductTemplates'),
	'eval'                 => array('tl_class'=>'w50'),
    'sql'                  => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['type_Class'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['type_Class'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['type_imgSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['type_imgSize'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => System::getImageSizes(),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['type_perRow'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['type_perRow'],
	'default'              => '4',
	'exclude'              => true,
	'inputType'            => 'select',
	'options'              => array('1','2','3','4','6','12'),
	'eval'                 => array('tl_class'=>'w50'),
    'sql'                  => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['related_show'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['related_show'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array(),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['related_template'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['related_template'],
	'default'              => 'product_related',
	'exclude'              => true,
	'inputType'            => 'select',
	'options_callback'     => array('tl_module_kitchenware', 'getProductTemplates'),
	'eval'                 => array('tl_class'=>'w50'),
    'sql'                  => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['related_Class'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['related_Class'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['related_perRow'] = array
(
	'label'                => &$GLOBALS['TL_LANG']['tl_module']['related_perRow'],
	'default'              => '4',
	'exclude'              => true,
	'inputType'            => 'select',
	'options'              => array('1','2','3','4','6','12'),
	'eval'                 => array('tl_class'=>'w50'),
    'sql'                  => "varchar(64) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['related_imgSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['related_imgSize'],
	'exclude'                 => true,
	'inputType'               => 'imageSize',
	'options'                 => System::getImageSizes(),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('rgxp'=>'digit', 'nospace'=>true, 'helpwizard'=>true, 'tl_class'=>'w50'),
	'sql'                     => "varchar(64) NOT NULL default ''"
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
		$objArchives = $this->Database->execute("SELECT * FROM tl_kitchenware_category ORDER BY title");

		while ($objArchives->next())
		{
			//if ($this->User->hasAccess($objArchives->id, 'news'))
			//{
				$arrArchives[$objArchives->id] = $objArchives->title . ' [' . $objArchives->language . ']';
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
	public function getProductTemplates()
	{
		return $this->getTemplateGroup('kitchenware_product_');
	}

}
