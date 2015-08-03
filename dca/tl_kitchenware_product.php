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
 * Table tl_kitchenware_product
 */
$GLOBALS['TL_DCA']['tl_kitchenware_product'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_kitchenware_category',
		'ctable'                      => array('tl_kitchenware_piece','tl_kitchenware_type'),
		'enableVersioning'            => true,
		'onload_callback'             => array
		(
			array('tl_kitchenware_product', 'showSelectbox'),
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
				'alias' => 'index',
				'title' => 'index'
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
			'headerFields'            => array('title','language','jumpTo','protected'),
			'panelLayout'             => 'search,limit',
			'child_record_callback'   => array('tl_kitchenware_product', 'generateItemRow')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'types' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['types'],
				'href'                => 'table=tl_kitchenware_type',
				'icon'                => 'system/modules/kitchenware/assets/type.png'
			),
			'pieces' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['pieces'],
				'href'                => 'table=tl_kitchenware_piece',
				'icon'                => 'system/modules/kitchenware/assets/piece.png'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_kitchenware_product', 'toggleIcon')
			),
			'feature' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['feature'],
				'icon'                => 'featured.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleFeature(this,%s)"',
				'button_callback'     => array('tl_kitchenware_product', 'iconFeature')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addEnclosure','package','published'),
		'default'                     => '{title_legend},title,alias,model,date,featured;{image_legend},singleSRC;{package_legend},package;{certificate_legend},isiri,irifdo;{warranty_legend},warranty;{features_legend},features;{description_legend:hide},description;{enclosure_legend:hide},addEnclosure;{publish_legend},published'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'addEnclosure'                => 'enclosure',
		'package'                     => 'pieces',
		'published'                   => 'start,stop'
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
			'foreignKey'              => 'tl_kitchenware_category.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'languageMain' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['languageMain'],
			'exclude'                 => false,
			'inputType'               => 'select',
			'options_callback'        => array('tl_kitchenware_product', 'getMasterCategory'),
			'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'alias' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'rgxp'=>'alias','unique'=>true,'maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'model' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['model'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['date'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'origin' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['origin'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'price' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['price'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'package' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['package'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'m12 w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'pieces' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['pieces'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'irifdo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['irifdo'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'isiri' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['isiri'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'warranty' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['warranty'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'features' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['features'],
			'exclude'                 => true,
			'sorting'                 => true,
			'inputType'               => 'listWizard',
			'eval'                    => array(),
			'sql'                     => "blob NULL",
		),
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio','mandatory'=>true, 'files'=>true, 'filesOnly'=>true, 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
			'sql'                     => "binary(16) NULL"
		),
		'description' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['description'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
			'sql'                     => "text NULL"
		),
		'addEnclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['addEnclosure'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'enclosure' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['enclosure'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('multiple'=>true, 'fieldType'=>'checkbox', 'filesOnly'=>true, 'isDownloads'=>true, 'extensions'=>Config::get('allowedDownload'), 'mandatory'=>true),
			'sql'                     => "blob NULL"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true,'submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'featured' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_kitchenware_product']['featured'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array
 */
class tl_kitchenware_product extends Backend
{

    /**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_news
	 */
	public function checkPermission()
	{

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set the root IDs
		if (!is_array($this->User->Kitchenware) || empty($this->User->Kitchenware))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->Kitchenware;
		}

		$id = strlen(Input::get('id')) ? Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
				if (!strlen(Input::get('pid')) || !in_array(Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to create Kitchenware items in Kitchenware archive ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array(Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' Kitchenware product ID "'.$id.'" to Kitchenware category ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
			case 'feature':
				$objArchive = $this->Database->prepare("SELECT pid FROM tl_kitchenware_category WHERE id=?")
											 ->limit(1)
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid Kitchenware category ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objArchive->pid, $root))
				{
					$this->log('Not enough permissions to '.Input::get('act').' Kitchenware product ID "'.$id.'" of Kitchenware category ID "'.$objArchive->pid.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access Kitchenware category ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objArchive = $this->Database->prepare("SELECT id FROM tl_kitchenware_product WHERE pid=?")
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid Kitchenware category ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objArchive->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen(Input::get('act')))
				{
					$this->log('Invalid command "'.Input::get('act').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access Kitchenware category ID ' . $id, __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}

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
		if (is_array($GLOBALS['TL_DCA']['tl_kitchenware_product']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_kitchenware_product']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_kitchenware_product SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_kitchenware_product', $intId);

	}

	public function iconFeature($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('sid')))
		{
			$this->toggleFeature($this->Input->get('sid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		//if (!$this->User->isAdmin && !$this->User->hasAccess('tl_prices::published', 'alexf'))
		//{
		//	return '';
		//}

		$href .= '&amp;sid='.$row['id'].'&amp;state='.($row['featured'] ? '' : 1);

		if (!$row['featured'])
		{
			$icon = 'featured_.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}



	public function toggleFeature($intId, $blnFeature)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'feature');
		//$this->checkPermission();

		// Check permissions to feature
		//if (!$this->User->hasAccess('tl_news::featured', 'alexf'))
		//{
		//	$this->log('Not enough permissions to feature/unfeature news item ID "'.$intId.'"', __METHOD__, TL_ERROR);
		//	$this->redirect('contao/main.php?act=error');
		//}

		$objVersions = new Versions('tl_kitchenware_product', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_news']['fields']['featured']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_news']['fields']['featured']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $this);
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_kitchenware_product SET tstamp=". time() .", featured='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_kitchenware_product.id='.$intId.'" has been created'.$this->getParentEntries('tl_kitchenware_product', $intId), __METHOD__, TL_GENERAL);

	}

	/**
	 * Get records from the master category
	 *
	 * @param	DataContainer
	 * @return	array
	 * @link	http://www.contao.org/callbacks.html#options_callback
	 */
	public function getMasterCategory(DataContainer $dc)
	{
		$sameDay = $GLOBALS['TL_LANG']['tl_kitchenware_product']['sameDay'];
		$otherDay = $GLOBALS['TL_LANG']['tl_kitchenware_product']['otherDay'];

		$arrItems = array($sameDay => array(), $otherDay => array());
		$objItems = $this->Database->prepare("SELECT * FROM tl_kitchenware_product WHERE pid=(SELECT tl_kitchenware_category.master FROM tl_kitchenware_category LEFT OUTER JOIN tl_kitchenware_product ON tl_kitchenware_product.pid=tl_kitchenware_category.id WHERE tl_kitchenware_product.id=?) ORDER BY date DESC")->execute($dc->id);

		$dayBegin = strtotime('0:00', $dc->activeRecord->date);

		while( $objItems->next() )
		{
			if (strtotime('0:00', $objItems->date) == $dayBegin)
			{
				$arrItems[$sameDay][$objItems->id] = $objItems->title . ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objItems->time) . ')';
			}
			else
			{
				$arrItems[$otherDay][$objItems->id] = $objItems->title . ' (' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $objItems->time) . ')';
			}
		}

		return $arrItems;
	}


	/**
	 * Show the select menu only on slave archives
	 *
	 * @param	DataContainer
	 * @return	void
	 * @link	http://www.contao.org/callbacks.html#onload_callback
	 */
	public function showSelectbox(DataContainer $dc)
	{
		if($this->Input->get('act') == "edit")
		{
			$objCategory = $this->Database->prepare("SELECT tl_kitchenware_category.* FROM tl_kitchenware_category LEFT OUTER JOIN tl_kitchenware_product ON tl_kitchenware_product.pid=tl_kitchenware_category.id WHERE tl_kitchenware_product.id=?")
										 ->limit(1)
										 ->execute($dc->id);

			if($objCategory->numRows && $objCategory->master > 0)
			{
				$GLOBALS['TL_DCA']['tl_kitchenware_product']['palettes']['default'] = preg_replace('@([,|;])(alias[,|;])@','$1languageMain,$2', $GLOBALS['TL_DCA']['tl_kitchenware_product']['palettes']['default']);
				$GLOBALS['TL_DCA']['tl_kitchenware_product']['fields']['title']['eval']['tl_class'] = 'w50';
				$GLOBALS['TL_DCA']['tl_kitchenware_product']['fields']['alias']['eval']['tl_class'] = 'clr w50';
			}
		}
		else if($this->Input->get('act') == "editAll")
		{
			$GLOBALS['TL_DCA']['tl_kitchenware_product']['palettes']['regular'] = preg_replace('@([,|;]{1}language)([,|;]{1})@','$1,languageMain$2', $GLOBALS['TL_DCA']['tl_kitchenware_product']['palettes']['regular']);
		}
	}

}
