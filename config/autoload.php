<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
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
	'kitchenware',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'kitchenware\Kitchenware'                => 'system/modules/kitchenware/classes/Kitchenware.php',

	// Models
	'kitchenware\KitchenwareElementModel'    => 'system/modules/kitchenware/models/KitchenwareElementModel.php',
	'kitchenware\KitchenwareTypeModel'       => 'system/modules/kitchenware/models/KitchenwareTypeModel.php',
	'kitchenware\KitchenwareProductModel'    => 'system/modules/kitchenware/models/KitchenwareProductModel.php',
	'kitchenware\KitchenwareCategoryModel'   => 'system/modules/kitchenware/models/KitchenwareCategoryModel.php',

	// Modules
	'kitchenware\ModuleKitchenwareDetail'    => 'system/modules/kitchenware/modules/ModuleKitchenwareDetail.php',
	'kitchenware\ModuleKitchenware'          => 'system/modules/kitchenware/modules/ModuleKitchenware.php',
	'kitchenware\ModuleKitchenwareList'      => 'system/modules/kitchenware/modules/ModuleKitchenwareList.php',
	'kitchenware\ModuleKitchenwarePriceList' => 'system/modules/kitchenware/modules/ModuleKitchenwarePriceList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_kitchenware_detail' => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_list'   => 'system/modules/kitchenware/templates/modules',
	'product_full'           => 'system/modules/kitchenware/templates/product',
	'product_short'          => 'system/modules/kitchenware/templates/product',
));
