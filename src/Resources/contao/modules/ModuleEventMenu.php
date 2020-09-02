<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao;

use Patchwork\Utf8;

/**
 * Front end module "event menu".
 *
 * @property bool   $cal_showQuantity
 * @property string $cal_order
 * @property string $cal_format
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class ModuleEventMenu extends ModuleCalendar
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_eventmenu';

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		$request = System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['eventmenu'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		if ($this->cal_format == 'cal_day')
		{
			$this->strTemplate = 'mod_calendar';
			$this->cal_ctemplate = 'cal_mini';
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		switch ($this->cal_format)
		{
			case 'cal_year':
				$this->compileYearlyMenu();
				break;

			default:
			case 'cal_month':
				$this->compileMonthlyMenu();
				break;

			case 'cal_day':
				parent::compile();
				break;
		}
	}

	/**
	 * Generate the yearly menu
	 */
	protected function compileYearlyMenu()
	{
		$arrData = array();
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, 0, min(4294967295, PHP_INT_MAX)); // 1970-01-01 00:00:00 - 2106-02-07 07:28:15

		foreach ($arrAllEvents as $intDay=>$arrDay)
		{
			foreach ($arrDay as $arrEvents)
			{
				$arrData[substr($intDay, 0, 4)] += \count($arrEvents);
			}
		}

		// Sort data
		($this->cal_order == 'ascending') ? ksort($arrData) : krsort($arrData);

		$arrItems = array();
		$count = 0;
		$limit = \count($arrData);

		// Prepare navigation
		foreach ($arrData as $intYear=>$intCount)
		{
			$intDate = $intYear;
			$quantity = sprintf((($intCount < 2) ? $GLOBALS['TL_LANG']['MSC']['entry'] : $GLOBALS['TL_LANG']['MSC']['entries']), $intCount);

			$arrItems[$intYear]['date'] = $intDate;
			$arrItems[$intYear]['link'] = $intYear;
			$arrItems[$intYear]['href'] = $this->strLink . '?year=' . $intDate;
			$arrItems[$intYear]['title'] = StringUtil::specialchars($intYear . ' (' . $quantity . ')');
			$arrItems[$intYear]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
			$arrItems[$intYear]['isActive'] = (Input::get('year') == $intDate);
			$arrItems[$intYear]['quantity'] = $quantity;
		}

		$this->Template->yearly = true;
		$this->Template->items = $arrItems;
		$this->Template->showQuantity = (bool) $this->cal_showQuantity;
	}

	/**
	 * Generate the monthly menu
	 */
	protected function compileMonthlyMenu()
	{
		$arrData = array();
		$arrAllEvents = $this->getAllEvents($this->cal_calendar, 0, min(4294967295, PHP_INT_MAX)); // 1970-01-01 00:00:00 - 2106-02-07 07:28:15

		foreach ($arrAllEvents as $intDay=>$arrDay)
		{
			foreach ($arrDay as $arrEvents)
			{
				$arrData[substr($intDay, 0, 4)][substr($intDay, 4, 2)] += \count($arrEvents);
			}
		}

		// Sort data
		foreach (array_keys($arrData) as $key)
		{
			($this->cal_order == 'ascending') ? ksort($arrData[$key]) : krsort($arrData[$key]);
		}

		($this->cal_order == 'ascending') ? ksort($arrData) : krsort($arrData);

		$arrItems = array();

		// Prepare the navigation
		foreach ($arrData as $intYear=>$arrMonth)
		{
			$count = 0;
			$limit = \count($arrMonth);

			foreach ($arrMonth as $intMonth=>$intCount)
			{
				$intDate = $intYear . $intMonth;
				$intMonth = (int) $intMonth - 1;

				$quantity = sprintf((($intCount < 2) ? $GLOBALS['TL_LANG']['MSC']['entry'] : $GLOBALS['TL_LANG']['MSC']['entries']), $intCount);

				$arrItems[$intYear][$intMonth]['date'] = $intDate;
				$arrItems[$intYear][$intMonth]['link'] = $GLOBALS['TL_LANG']['MONTHS'][$intMonth] . ' ' . $intYear;
				$arrItems[$intYear][$intMonth]['href'] = $this->strLink . '?month=' . $intDate;
				$arrItems[$intYear][$intMonth]['title'] = StringUtil::specialchars($GLOBALS['TL_LANG']['MONTHS'][$intMonth] . ' ' . $intYear . ' (' . $quantity . ')');
				$arrItems[$intYear][$intMonth]['class'] = trim(((++$count == 1) ? 'first ' : '') . (($count == $limit) ? 'last' : ''));
				$arrItems[$intYear][$intMonth]['isActive'] = (Input::get('month') == $intDate);
				$arrItems[$intYear][$intMonth]['quantity'] = $quantity;
			}
		}

		$this->Template->items = $arrItems;
		$this->Template->showQuantity = (bool) $this->cal_showQuantity;
		$this->Template->url = $this->strLink . '?';
		$this->Template->activeYear = Input::get('year');
	}
}

class_alias(ModuleEventMenu::class, 'ModuleEventMenu');
