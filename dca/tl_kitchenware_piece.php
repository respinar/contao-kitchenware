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
 * Table tl_kitchenware_piece
 */
$GLOBALS['TL_DCA']['tl_kitchenware_piece'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_kitchenware_product',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting'),
			'headerFields'            => array('title'),
			'panelLayout'             => 'search,limit',
			'child_record_callback'   => array('tl_kitchenware_piece', 'generateItemRow')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_kitchenware_piece', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},title;{image_legend},singleSRC;{specs_legend},dimensions,weight,capacity,material;{description_legend:hide},description;{publish_legend},published'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128,'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'dimensions' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['dimensions'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128,'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'weight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['weight'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128,'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'capacity' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['capacity'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128,'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'material' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['material'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128,'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('mandatory'=>true,'fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
			'sql'                     => "binary(16) NULL"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['description'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
			'sql'                     => "text NULL"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_piece']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array
 */
class tl_kitchenware_piece extends Backend
{

	/**
	 * Generate a song row and return it as HTML string
	 * @param array
	 * @return string
	 */
	public function generateItemRow($arrRow)
	{
		$objImage = \FilesModel::findByPk($arrRow['singleSRC']);

		if ($objImage !== null)
		{
			$strImage = \Image::getHtml(\Image::get($objImage->path, '60', '60', 'center_center'));
		}

		return '<div><div style="float:left; margin-right:10px;">'.$strImage.'</div>'. $arrRow['title'] . '</div>';
	}

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		//if (!$this->User->isAdmin && !$this->User->hasAccess('tl_prices::published', 'alexf'))
		//{
		//	return '';
		//}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}



	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		//$this->checkPermission();

		// Check permissions to publish
		//if (!$this->User->isAdmin && !$this->User->hasAccess('tl_news::published', 'alexf'))
		//{
		//	$this->log('Not enough permissions to publish/unpublish news item ID "'.$intId.'"', 'tl_news toggleVisibility', TL_ERROR);
		//	$this->redirect('contao/main.php?act=error');
		//}

		$this->createInitialVersion('tl_kitchenware_product', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_kitchenware_piece']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_kitchenware_piece']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_kitchenware_piece SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_kitchenware_product', $intId);

	}

}
