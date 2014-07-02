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
 * Table tl_kitchenware_rating
 */
$GLOBALS['TL_DCA']['tl_kitchenware_rating'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_kitchenware_set',
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	'fields' => array
	(
		'id' => array (
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array	(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'pid' => array (
			'foreignKey'              => 'tl_kitchenware_set.id',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'lazy')
		),
		'ip_address' => array	(
			'sql'                     => "varchar(50) NULL"
		),
		'memberid' => array	(
			'sql'                     => "int(10) unsigned NULL"
		),
		'rating' => array	(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'createdat' => array	(
			'sql'                     => "int(10) NOT NULL default '0'"
		)
	)
);
