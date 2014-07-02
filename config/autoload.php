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
	// Classes
	'Kitchenware\Kitchenware'              => 'system/modules/kitchenware/classes/Kitchenware.php',

	// Models
	'Kitchenware\KitchenwareCategoryModel' => 'system/modules/kitchenware/models/KitchenwareCategoryModel.php',
	'Kitchenware\KitchenwareSetModel'      => 'system/modules/kitchenware/models/KitchenwareSetModel.php',
	'Kitchenware\KitchenwareProductModel'  => 'system/modules/kitchenware/models/KitchenwareProductModel.php',

	// Modules
	'Kitchenware\ModuleKitchenwareMenu'    => 'system/modules/kitchenware/modules/ModuleKitchenwareMenu.php',
	'Kitchenware\ModuleKitchenwareList'    => 'system/modules/kitchenware/modules/ModuleKitchenwareList.php',
	'Kitchenware\ModuleKitchenwareSet' => 'system/modules/kitchenware/modules/ModuleKitchenwareSet.php',
	'Kitchenware\ModuleKitchenwareRelated' => 'system/modules/kitchenware/modules/ModuleKitchenwareRelated.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_kitchenware_menu'         => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_list'         => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_set'          => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_related'      => 'system/modules/kitchenware/templates/modules',
));
