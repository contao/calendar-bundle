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
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\System;

// Dynamically add the permission check and other callbacks
if (Input::get('do') == 'calendar')
{
	System::loadLanguageFile('tl_calendar_events');

	array_unshift($GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'], array('tl_content_calendar', 'checkPermission'));
	$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_calendar', 'generateFeed');
}

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @property Calendar $Calendar
 *
 * @internal
 */
class tl_content_calendar extends Backend
{
	/**
	 * Check permissions to edit table tl_content
	 *
	 * @param DataContainer $dc
	 */
	public function checkPermission(DataContainer $dc)
	{
		$user = BackendUser::getInstance();

		if ($user->isAdmin)
		{
			return;
		}

		// Set the root IDs
		if (empty($user->calendars) || !is_array($user->calendars))
		{
			$root = array(0);
		}
		else
		{
			$root = $user->calendars;
		}

		// Check the current action
		switch (Input::get('act'))
		{
			case '': // empty
			case 'paste':
			case 'create':
			case 'select':
				// Check access to the news item
				$this->checkAccessToElement($dc->currentPid, $root, true);
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				// Check access to the parent element if a content element is moved
				if (in_array(Input::get('act'), array('cutAll', 'copyAll')))
				{
					$this->checkAccessToElement(Input::get('pid'), $root, Input::get('mode') == 2);
				}

				$objCes = Database::getInstance()
					->prepare("SELECT id FROM tl_content WHERE ptable=? AND pid=?")
					->execute($dc->parentTable, $dc->currentPid);

				$objSession = System::getContainer()->get('request_stack')->getSession();

				$session = $objSession->all();
				$session['CURRENT']['IDS'] = array_intersect((array) $session['CURRENT']['IDS'], $objCes->fetchEach('id'));
				$objSession->replace($session);
				break;

			case 'cut':
			case 'copy':
				// Check access to the parent element if a content element is moved
				$this->checkAccessToElement(Input::get('pid'), $root, Input::get('mode') == 2);
				// no break

			default:
				// Check access to the content element
				$this->checkAccessToElement(Input::get('id'), $root);
				break;
		}
	}

	/**
	 * Check access to a particular content element
	 *
	 * @param integer $id
	 * @param array   $root
	 * @param boolean $blnIsPid
	 *
	 * @throws AccessDeniedException
	 */
	protected function checkAccessToElement($id, $root, $blnIsPid=false)
	{
		if ($blnIsPid)
		{
			$objCalendar = Database::getInstance()
				->prepare("SELECT a.id, n.id AS nid FROM tl_calendar_events n, tl_calendar a WHERE n.id=? AND n.pid=a.id")
				->limit(1)
				->execute($id);
		}
		else
		{
			$objCalendar = Database::getInstance()
				->prepare("SELECT a.id, n.id AS nid FROM tl_content c, tl_calendar_events n, tl_calendar a WHERE c.id=? AND c.pid=n.id AND n.pid=a.id")
				->limit(1)
				->execute($id);
		}

		// Invalid ID
		if ($objCalendar->numRows < 1)
		{
			throw new AccessDeniedException('Invalid event content element ID ' . $id . '.');
		}

		// The calendar is not mounted
		if (!in_array($objCalendar->id, $root))
		{
			throw new AccessDeniedException('Not enough permissions to modify article ID ' . $objCalendar->nid . ' in calendar ID ' . $objCalendar->id . '.');
		}
	}

	/**
	 * Check for modified calendar feeds and update the XML files if necessary
	 */
	public function generateFeed()
	{
		$objSession = System::getContainer()->get('request_stack')->getSession();
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

		$calendar = new Calendar();

		foreach ($session as $id)
		{
			$calendar->generateFeedsByCalendar($id);
		}

		if ($request)
		{
			$request->attributes->set('_scope', $origScope);
		}

		$objSession->set('calendar_feed_updater', null);
	}
}
