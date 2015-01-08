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
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['content'], 1, array
(
	'kitchenware' => array
	(
		'tables' => array('tl_kitchenware_category', 'tl_kitchenware_product','tl_kitchenware_type','tl_kitchenware_element'),
		'icon'   => 'system/modules/kitchenware/assets/icon.png'
	)
));


/**
 * Front end modules
 */

array_insert($GLOBALS['FE_MOD'], 2, array
(
	'kitchenware' => array
	(
		'kitchenware_list'    => 'ModuleKitchenwareList',
		'kitchenware_detail'  => 'ModuleKitchenwareDetail',
	)
));

/**
 * Register hook to add carpets items to the indexer
 */
$GLOBALS['TL_HOOKS']['getSearchablePages'][]     = array('Kitchenware', 'getSearchablePages');

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['translateUrlParameters'][] = array('Kitchenware', 'translateUrlParameters');



/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'kitchenware';
$GLOBALS['TL_PERMISSIONS'][] = 'setp';
