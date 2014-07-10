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
	'Kitchenware\Kitchenware'                => 'system/modules/kitchenware/classes/Kitchenware.php',

	// Models
	'Kitchenware\KitchenwareElementModel'    => 'system/modules/kitchenware/models/KitchenwareElementModel.php',
	'Kitchenware\KitchenwareTagModel'        => 'system/modules/kitchenware/models/KitchenwareTagModel.php',
	'Kitchenware\KitchenwareSetModel'        => 'system/modules/kitchenware/models/KitchenwareSetModel.php',
	'Kitchenware\KitchenwareModel'           => 'system/modules/kitchenware/models/KitchenwareModel.php',
	'Kitchenware\KitchenwareRatingModel'     => 'system/modules/kitchenware/models/KitchenwareRatingModel.php',

	// Modules
	'Kitchenware\ModuleKitchenwareRelated'   => 'system/modules/kitchenware/modules/ModuleKitchenwareRelated.php',
	'Kitchenware\ModuleKitchenwarePriceList' => 'system/modules/kitchenware/modules/ModuleKitchenwarePriceList.php',
	'Kitchenware\ModuleKitchenwareList'      => 'system/modules/kitchenware/modules/ModuleKitchenwareList.php',
	'Kitchenware\ModuleKitchenwareMenu'      => 'system/modules/kitchenware/modules/ModuleKitchenwareMenu.php',
	'Kitchenware\ModuleKitchenwareDetail'    => 'system/modules/kitchenware/modules/ModuleKitchenwareDetail.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_kitchenware_detail'    => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_set_empty' => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_menu'      => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_list'      => 'system/modules/kitchenware/templates/modules',
	'mod_kitchenware_related'   => 'system/modules/kitchenware/templates/modules',
));
