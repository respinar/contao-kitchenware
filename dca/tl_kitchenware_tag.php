<?php

/**
 * Table tl_kitchenware_tag
 */
$GLOBALS['TL_DCA']['tl_kitchenware_tag'] = array
(

	// Config
	'config' => array
	(
		'doNotCopyRecords'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'tid' => 'index',
				'tag' => 'index'
			)
		)
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tag' => array
		(
			'sql'                     => "varchar(100) NOT NULL default ''"
		)
	)
);

