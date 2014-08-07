<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Kitchenware
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Kitchenware',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'Kitchenware\KitchenwareModel'           => 'system/modules/kitchenware/models/KitchenwareModel.php',
	'Kitchenware\KitchenwareSetModel'        => 'system/modules/kitchenware/models/KitchenwareSetModel.php',
	'Kitchenware\KitchenwareElementModel'    => 'system/modules/kitchenware/models/KitchenwareElementModel.php',

	// Modules
	'Kitchenware\ModuleKitchenwarePriceList' => 'system/modules/kitchenware/modules/ModuleKitchenwarePriceList.php',
	'Kitchenware\ModuleKitchenwareRelated'   => 'system/modules/kitchenware/modules/ModuleKitchenwareRelated.php',
	'Kitchenware\ModuleKitchenwareDetail'    => 'system/modules/kitchenware/modules/ModuleKitchenwareDetail.php',
	'Kitchenware\ModuleKitchenwareList'      => 'system/modules/kitchenware/modules/ModuleKitchenwareList.php',
	'Kitchenware\ModuleKitchenware'          => 'system/modules/kitchenware/modules/ModuleKitchenware.php',

	// Classes
	'Kitchenware\Kitchenware'                => 'system/modules/kitchenware/classes/Kitchenware.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_kitchenware_list'    => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_detail'  => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_related' => 'system/modules/kitchenware/templates/modules',
	'set_full'                => 'system/modules/kitchenware/templates/sets',
	'set_short'               => 'system/modules/kitchenware/templates/sets',
));
