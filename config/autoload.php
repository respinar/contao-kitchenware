<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
	'kitchenware\KitchenwareCategoryModel'   => 'system/modules/kitchenware/models/KitchenwareCategoryModel.php',
	'kitchenware\KitchenwareProductModel'    => 'system/modules/kitchenware/models/KitchenwareProductModel.php',
	'kitchenware\KitchenwareTypeModel'       => 'system/modules/kitchenware/models/KitchenwareTypeModel.php',
	'kitchenware\KitchenwarePieceModel'      => 'system/modules/kitchenware/models/KitchenwarePieceModel.php',

	// Modules
	'kitchenware\ModuleKitchenware'          => 'system/modules/kitchenware/modules/ModuleKitchenware.php',
	'kitchenware\ModuleKitchenwareDetail'    => 'system/modules/kitchenware/modules/ModuleKitchenwareDetail.php',
	'kitchenware\ModuleKitchenwareList'      => 'system/modules/kitchenware/modules/ModuleKitchenwareList.php',
	'kitchenware\ModuleKitchenwarePriceList' => 'system/modules/kitchenware/modules/ModuleKitchenwarePriceList.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_kitchenware_detail'    => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_list'      => 'system/modules/kitchenware/templates/modules',
	'kitchenware_product_full'  => 'system/modules/kitchenware/templates/product',
	'kitchenware_product_short' => 'system/modules/kitchenware/templates/product',
	'kitchenware_type'          => 'system/modules/kitchenware/templates/type',
	'kitchenware_piece'         => 'system/modules/kitchenware/templates/piece',
));
