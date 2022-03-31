<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

use Contao\Backend;
use Contao\BackendUser;
use Contao\Calendar;
use Contao\CalendarBundle\Security\ContaoCalendarPermissions;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Image;
use Contao\Input;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

$GLOBALS['TL_DCA']['tl_calendar'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => DC_Table::class,
		'ctable'                      => array('tl_calendar_events'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'markAsCopy'                  => 'title',
		'onload_callback' => array
		(
			array('tl_calendar', 'checkPermission'),
			array('tl_calendar', 'generateFeed')
		),
		'oncreate_callback' => array
		(
			array('tl_calendar', 'adjustPermissions')
		),
		'oncopy_callback' => array
		(
			array('tl_calendar', 'adjustPermissions')
		),
		'onsubmit_callback' => array
		(
			array('tl_calendar', 'scheduleUpdate')
		),
		'oninvalidate_cache_tags_callback' => array
		(
			array('tl_calendar', 'addSitemapCacheInvalidationTag'),
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
			'mode'                    => DataContainer::MODE_SORTED,
			'fields'                  => array('title'),
			'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'feeds' => array
			(
				'href'                => 'table=tl_calendar_feed',
				'class'               => 'header_rss',
				'attributes'          => 'onclick="Backend.getScrollOffset()"',
				'button_callback'     => array('tl_calendar', 'manageFeeds')
			),
			'all' => array
			(
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'href'                => 'table=tl_calendar_events',
				'icon'                => 'edit.svg'
			),
			'editheader' => array
			(
				'href'                => 'act=edit',
				'icon'                => 'header.svg',
				'button_callback'     => array('tl_calendar', 'editHeader')
			),
			'copy' => array
			(
				'href'                => 'act=copy',
				'icon'                => 'copy.svg',
				'button_callback'     => array('tl_calendar', 'copyCalendar')
			),
			'delete' => array
			(
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_calendar', 'deleteCalendar')
			),
			'show' => array
			(
				'href'                => 'act=show',
				'icon'                => 'show.svg'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('protected', 'allowComments'),
		'default'                     => '{title_legend},title,jumpTo;{protected_legend:hide},protected;{comments_legend:hide},allowComments'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'protected'                   => 'groups',
		'allowComments'               => 'notify,sortOrder,perPage,moderate,bbcode,requireLogin,disableCaptcha'
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
			'sql'                     => "int(10) unsigned NOT NULL default 0"
		),
		'title' => array
		(
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'jumpTo' => array
		(
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('mandatory'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default 0",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'protected' => array
		(
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'groups' => array
		(
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true),
			'sql'                     => "blob NULL",
			'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
		),
		'allowComments' => array
		(
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'notify' => array
		(
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('notify_admin', 'notify_author', 'notify_both'),
			'eval'                    => array('tl_class'=>'w50'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_calendar'],
			'sql'                     => "varchar(32) NOT NULL default 'notify_admin'"
		),
		'sortOrder' => array
		(
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('ascending', 'descending'),
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => array('tl_class'=>'w50 clr'),
			'sql'                     => "varchar(32) NOT NULL default 'ascending'"
		),
		'perPage' => array
		(
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'natural', 'tl_class'=>'w50'),
			'sql'                     => "smallint(5) unsigned NOT NULL default 0"
		),
		'moderate' => array
		(
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'bbcode' => array
		(
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'requireLogin' => array
		(
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'disableCaptcha' => array
		(
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @property Calendar $Calendar
 */
class tl_calendar extends Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import(BackendUser::class, 'User');
	}

	/**
	 * Check permissions to edit table tl_calendar
	 *
	 * @throws AccessDeniedException
	 */
	public function checkPermission()
	{
		$bundles = System::getContainer()->getParameter('kernel.bundles');

		// HOOK: comments extension required
		if (!isset($bundles['ContaoCommentsBundle']))
		{
			unset($GLOBALS['TL_DCA']['tl_calendar']['fields']['allowComments']);
		}

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (empty($this->User->calendars) || !is_array($this->User->calendars))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->calendars;
		}

		$GLOBALS['TL_DCA']['tl_calendar']['list']['sorting']['root'] = $root;
		$security = System::getContainer()->get('security.helper');

		// Check permissions to add calendars
		if (!$security->isGranted(ContaoCalendarPermissions::USER_CAN_CREATE_CALENDARS))
		{
			$GLOBALS['TL_DCA']['tl_calendar']['config']['closed'] = true;
			$GLOBALS['TL_DCA']['tl_calendar']['config']['notCreatable'] = true;
			$GLOBALS['TL_DCA']['tl_calendar']['config']['notCopyable'] = true;
		}

		// Check permissions to delete calendars
		if (!$security->isGranted(ContaoCalendarPermissions::USER_CAN_DELETE_CALENDARS))
		{
			$GLOBALS['TL_DCA']['tl_calendar']['config']['notDeletable'] = true;
		}

		$objSession = System::getContainer()->get('session');

		// Check current action
		switch (Input::get('act'))
		{
			case 'select':
				// Allow
				break;

			case 'create':
				if (!$security->isGranted(ContaoCalendarPermissions::USER_CAN_CREATE_CALENDARS))
				{
					throw new AccessDeniedException('Not enough permissions to create calendars.');
				}
				break;

			case 'edit':
			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(Input::get('id'), $root) || (Input::get('act') == 'delete' && !$security->isGranted(ContaoCalendarPermissions::USER_CAN_DELETE_CALENDARS)))
				{
					throw new AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' calendar ID ' . Input::get('id') . '.');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'copyAll':
				$session = $objSession->all();

				if (Input::get('act') == 'deleteAll' && !$security->isGranted(ContaoCalendarPermissions::USER_CAN_DELETE_CALENDARS))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect((array) $session['CURRENT']['IDS'], $root);
				}
				$objSession->replace($session);
				break;

			default:
				if (Input::get('act'))
				{
					throw new AccessDeniedException('Not enough permissions to ' . Input::get('act') . ' calendars.');
				}
				break;
		}
	}

	/**
	 * Add the new calendar to the permissions
	 *
	 * @param $insertId
	 */
	public function adjustPermissions($insertId)
	{
		// The oncreate_callback passes $insertId as second argument
		if (func_num_args() == 4)
		{
			$insertId = func_get_arg(1);
		}

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (empty($this->User->calendars) || !is_array($this->User->calendars))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->calendars;
		}

		// The calendar is enabled already
		if (in_array($insertId, $root))
		{
			return;
		}

		/** @var AttributeBagInterface $objSessionBag */
		$objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');
		$arrNew = $objSessionBag->get('new_records');

		if (is_array($arrNew['tl_calendar']) && in_array($insertId, $arrNew['tl_calendar']))
		{
			// Add the permissions on group level
			if ($this->User->inherit != 'custom')
			{
				$objGroup = $this->Database->execute("SELECT id, calendars, calendarp FROM tl_user_group WHERE id IN(" . implode(',', array_map('\intval', $this->User->groups)) . ")");

				while ($objGroup->next())
				{
					$arrCalendarp = StringUtil::deserialize($objGroup->calendarp);

					if (is_array($arrCalendarp) && in_array('create', $arrCalendarp))
					{
						$arrCalendars = StringUtil::deserialize($objGroup->calendars, true);
						$arrCalendars[] = $insertId;

						$this->Database->prepare("UPDATE tl_user_group SET calendars=? WHERE id=?")
									   ->execute(serialize($arrCalendars), $objGroup->id);
					}
				}
			}

			// Add the permissions on user level
			if ($this->User->inherit != 'group')
			{
				$objUser = $this->Database->prepare("SELECT calendars, calendarp FROM tl_user WHERE id=?")
										   ->limit(1)
										   ->execute($this->User->id);

				$arrCalendarp = StringUtil::deserialize($objUser->calendarp);

				if (is_array($arrCalendarp) && in_array('create', $arrCalendarp))
				{
					$arrCalendars = StringUtil::deserialize($objUser->calendars, true);
					$arrCalendars[] = $insertId;

					$this->Database->prepare("UPDATE tl_user SET calendars=? WHERE id=?")
								   ->execute(serialize($arrCalendars), $this->User->id);
				}
			}

			// Add the new element to the user object
			$root[] = $insertId;
			$this->User->calendars = $root;
		}
	}

	/**
	 * Check for modified calendar feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$objSession = System::getContainer()->get('session');
		$session = $objSession->get('calendar_feed_updater');

		if (empty($session) || !is_array($session))
		{
			return;
		}

		$request = System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request)
		{
			$origScope = $request->attributes->get('_scope');
			$request->attributes->set('_scope', 'frontend');
		}

		$this->import(Calendar::class, 'Calendar');

		foreach ($session as $id)
		{
			$this->Calendar->generateFeedsByCalendar($id);
		}

		if ($request)
		{
			$request->attributes->set('_scope', $origScope);
		}

		$objSession->set('calendar_feed_updater', null);
	}

	/**
	 * Schedule a calendar feed update
	 *
	 * This method is triggered when a single calendar or multiple calendars
	 * are modified (edit/editAll).
	 *
	 * @param DataContainer $dc
	 */
	public function scheduleUpdate(DataContainer $dc)
	{
		// Return if there is no ID
		if (!$dc->id)
		{
			return;
		}

		$objSession = System::getContainer()->get('session');

		// Store the ID in the session
		$session = $objSession->get('calendar_feed_updater');
		$session[] = $dc->id;
		$objSession->set('calendar_feed_updater', array_unique($session));
	}

	/**
	 * Return the manage feeds button
	 *
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $class
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function manageFeeds($href, $label, $title, $class, $attributes)
	{
		return ($this->User->isAdmin || !empty($this->User->calendarfeeds) || !empty($this->User->calendarfeedp)) ? '<a href="' . $this->addToUrl($href) . '" class="' . $class . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . $label . '</a> ' : '';
	}

	/**
	 * Return the edit header button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return System::getContainer()->get('security.helper')->isGranted(ContaoCorePermissions::USER_CAN_EDIT_FIELDS_OF_TABLE, 'tl_calendar') ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.svg/i', '_.svg', $icon)) . ' ';
	}

	/**
	 * Return the copy calendar button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function copyCalendar($row, $href, $label, $title, $icon, $attributes)
	{
		return System::getContainer()->get('security.helper')->isGranted(ContaoCalendarPermissions::USER_CAN_CREATE_CALENDARS) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.svg/i', '_.svg', $icon)) . ' ';
	}

	/**
	 * Return the delete calendar button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function deleteCalendar($row, $href, $label, $title, $icon, $attributes)
	{
		return System::getContainer()->get('security.helper')->isGranted(ContaoCalendarPermissions::USER_CAN_DELETE_CALENDARS) ? '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.svg/i', '_.svg', $icon)) . ' ';
	}

	/**
	 * @param DataContainer $dc
	 *
	 * @return array
	 */
	public function addSitemapCacheInvalidationTag($dc, array $tags)
	{
		$pageModel = PageModel::findWithDetails($dc->activeRecord->jumpTo);

		if ($pageModel === null)
		{
			return $tags;
		}

		return array_merge($tags, array('contao.sitemap.' . $pageModel->rootId));
	}
}
