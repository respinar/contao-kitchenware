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
 * Table tl_kitchenware_category
 */
$GLOBALS['TL_DCA']['tl_kitchenware_category'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_kitchenware_product'),
		'enableVersioning'            => true,
        'switchToEdit'                => true,
        'onload_callback' => array
		(
			array('tl_kitchenware_category', 'checkPermission')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('language','title'),
			'format'                  => '[%s] %s',
			'label_callback'          => array('tl_kitchenware_category', 'addSetCount')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['edit'],
				'href'                => 'table=tl_kitchenware_product',
				'icon'                => 'edit.gif'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
                'button_callback'     => array('tl_kitchenware_category', 'copyCategory')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
                'button_callback'     => array('tl_kitchenware_category', 'deleteCategory')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('protected'),
		'default'                     => '{title_legend},title,language,master;{redirect_legend},jumpTo;{protected_legend:hide},protected;'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'protected'                   => 'groups',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['language'],
			'exclude'                 => true,
			'search'                  => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>32, 'tl_class'=>'w50'),
			'sql'                     => "varchar(32) NOT NULL default ''"
		),
		'master' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['master'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_kitchenware_category', 'getCategories'),
			'eval'                    => array('includeBlankOption'=>true, 'blankOptionLabel'=>&$GLOBALS['TL_LANG']['tl_kitchenware_category']['isMaster']),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'jumpTo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['jumpTo'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'eager')
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['protected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_category']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		)
	)
);


class tl_kitchenware_category extends Backend {

    /**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

    /**
	 * Check permissions to edit table tl_kitchenware
	 */
	public function checkPermission()
	{

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->kitchenware) || empty($this->User->kitchenware))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->kitchenware;
		}

		$GLOBALS['TL_DCA']['tl_kitchenware_category']['list']['sorting']['root'] = $root;

		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'setp'))
		{
			$GLOBALS['TL_DCA']['tl_kitchenware_category']['config']['closed'] = true;
		}

		// Check current action
		switch (Input::get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array(Input::get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_kitchenware_category']) && in_array(Input::get('id'), $arrNew['tl_kitchenware_category']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT kitchenware, setp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrSetp = deserialize($objUser->setp);

							if (is_array($arrSetp) && in_array('create', $arrSetp))
							{
								$arrKitchenware = deserialize($objUser->kitchenware);
								$arrKitchenware[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user SET kitchenware=? WHERE id=?")
											   ->execute(serialize($arrKitchenware), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT kitchenware, setp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrSetp = deserialize($objGroup->setp);

							if (is_array($arrSetp) && in_array('create', $arrSetp))
							{
								$arrKitchenware = deserialize($objGroup->kitchenware);
								$arrKitchenware[] = Input::get('id');

								$this->Database->prepare("UPDATE tl_user_group SET kitchenware=? WHERE id=?")
											   ->execute(serialize($arrKitchenware), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = Input::get('id');
						$this->User->kitchenware = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'setp')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' kitchenware archive ID "'.Input::get('id').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'setp'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Not enough permissions to '.Input::get('act').' kitchenware archives', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

    /**
	 * Return the copy archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyCategory($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('create', 'setp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteCategory($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('delete', 'setp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}

	public function addSetCount($row, $label) {

        $objChildren = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_kitchenware_product WHERE pid=?")
                ->execute($row['id']);

        $label .= ' <span style="color:#b3b3b3; padding-left:3px;">' . sprintf('[%s ' . $GLOBALS['TL_LANG']['tl_kitchenware_category']['product'] . ']', $objChildren->count) . '</span>';

        return $label;
    }


    /**
	 * Get an array of possible kitchenware categories
	 *
	 * @param	DataContainer
	 * @return	array
	 * @link	http://www.contao.org/callbacks.html#options_callback
	 */
	public function getCategories(DataContainer $dc)
	{
		$arrCategories = array();
		$objCategories = $this->Database->prepare("SELECT * FROM tl_kitchenware_category WHERE language!=? AND id!=? AND master=0 ORDER BY title")->execute($dc->activeRecord->language, $dc->id);

		while( $objCategories->next() )
		{
			$arrCategories[$objCategories->id] = sprintf($GLOBALS['TL_LANG']['tl_kitchenware_category']['isSlave'], $objCategories->title);
		}

		return $arrCategories;
	}

}
