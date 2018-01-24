<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2018 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Reads and writes events
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $tstamp
 * @property string  $title
 * @property string  $alias
 * @property integer $author
 * @property boolean $addTime
 * @property integer $startTime
 * @property integer $endTime
 * @property integer $startDate
 * @property integer $endDate
 * @property string  $location
 * @property string  $address
 * @property string  $teaser
 * @property boolean $addImage
 * @property string  $singleSRC
 * @property string  $alt
 * @property string  $size
 * @property string  $imagemargin
 * @property string  $imageUrl
 * @property boolean $fullsize
 * @property string  $caption
 * @property string  $floating
 * @property boolean $recurring
 * @property string  $repeatEach
 * @property integer $repeatEnd
 * @property integer $recurrences
 * @property boolean $addEnclosure
 * @property string  $enclosure
 * @property string  $source
 * @property integer $jumpTo
 * @property integer $articleId
 * @property string  $url
 * @property boolean $target
 * @property string  $cssClass
 * @property boolean $noComments
 * @property boolean $published
 * @property string  $start
 * @property string  $stop
 *
 * @method static CalendarEventsModel|null findById($id, array $opt=array())
 * @method static CalendarEventsModel|null findByPk($id, array $opt=array())
 * @method static CalendarEventsModel|null findByIdOrAlias($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneBy($col, $val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByPid($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByTstamp($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByTitle($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByAlias($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByAuthor($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByAddTime($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByStartTime($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByEndTime($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByStartDate($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByEndDate($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByLocation($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByAddress($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByTeaser($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByAddImage($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneBySingleSRC($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByAlt($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneBySize($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByImagemargin($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByImageUrl($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByFullsize($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByCaption($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByFloating($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByRecurring($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByRepeatEach($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByRepeatEnd($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByRecurrences($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByAddEnclosure($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByEnclosure($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneBySource($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByJumpTo($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByArticleId($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByUrl($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByTarget($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByCssClass($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByNoComments($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByPublished($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByStart($val, array $opt=array())
 * @method static CalendarEventsModel|null findOneByStop($val, array $opt=array())
 *
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByPid($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByTstamp($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByTitle($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByAlias($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByAuthor($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByAddTime($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByStartTime($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByEndTime($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByStartDate($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByEndDate($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByLocation($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByAddress($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByTeaser($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByAddImage($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findBySingleSRC($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByAlt($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findBySize($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByImagemargin($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByImageUrl($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByFullsize($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByCaption($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByFloating($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByRecurring($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByRepeatEach($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByRepeatEnd($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByRecurrences($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByAddEnclosure($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByEnclosure($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findBySource($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByJumpTo($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByArticleId($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByUrl($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByTarget($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByCssClass($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByNoComments($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByPublished($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByStart($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findByStop($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findMultipleByIds($val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findBy($col, $val, array $opt=array())
 * @method static Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, array $opt=array())
 * @method static integer countByPid($val, array $opt=array())
 * @method static integer countByTstamp($val, array $opt=array())
 * @method static integer countByTitle($val, array $opt=array())
 * @method static integer countByAlias($val, array $opt=array())
 * @method static integer countByAuthor($val, array $opt=array())
 * @method static integer countByAddTime($val, array $opt=array())
 * @method static integer countByStartTime($val, array $opt=array())
 * @method static integer countByEndTime($val, array $opt=array())
 * @method static integer countByStartDate($val, array $opt=array())
 * @method static integer countByEndDate($val, array $opt=array())
 * @method static integer countByLocation($val, array $opt=array())
 * @method static integer countByAddress($val, array $opt=array())
 * @method static integer countByTeaser($val, array $opt=array())
 * @method static integer countByAddImage($val, array $opt=array())
 * @method static integer countBySingleSRC($val, array $opt=array())
 * @method static integer countByAlt($val, array $opt=array())
 * @method static integer countBySize($val, array $opt=array())
 * @method static integer countByImagemargin($val, array $opt=array())
 * @method static integer countByImageUrl($val, array $opt=array())
 * @method static integer countByFullsize($val, array $opt=array())
 * @method static integer countByCaption($val, array $opt=array())
 * @method static integer countByFloating($val, array $opt=array())
 * @method static integer countByRecurring($val, array $opt=array())
 * @method static integer countByRepeatEach($val, array $opt=array())
 * @method static integer countByRepeatEnd($val, array $opt=array())
 * @method static integer countByRecurrences($val, array $opt=array())
 * @method static integer countByAddEnclosure($val, array $opt=array())
 * @method static integer countByEnclosure($val, array $opt=array())
 * @method static integer countBySource($val, array $opt=array())
 * @method static integer countByJumpTo($val, array $opt=array())
 * @method static integer countByArticleId($val, array $opt=array())
 * @method static integer countByUrl($val, array $opt=array())
 * @method static integer countByTarget($val, array $opt=array())
 * @method static integer countByCssClass($val, array $opt=array())
 * @method static integer countByNoComments($val, array $opt=array())
 * @method static integer countByPublished($val, array $opt=array())
 * @method static integer countByStart($val, array $opt=array())
 * @method static integer countByStop($val, array $opt=array())
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class CalendarEventsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_calendar_events';


	/**
	 * Find a published event from one or more calendars by its ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of calendar IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return CalendarEventsModel|null The model or null if there is no event
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = !is_numeric($varId) ? array("$t.alias=?") : array("$t.id=?");
		$arrColumns[] = "$t.pid IN(" . implode(',', array_map('intval', $arrPids)) . ")";

		if (!static::isPreviewMode($arrOptions))
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		return static::findOneBy($arrColumns, $varId, $arrOptions);
	}


	/**
	 * Find events of the current period by their parent ID
	 *
	 * @param integer $intPid     The calendar ID
	 * @param integer $intStart   The start date as Unix timestamp
	 * @param integer $intEnd     The end date as Unix timestamp
	 * @param array   $arrOptions An optional options array
	 *
	 * @return Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null A collection of models or null if there are no events
	 */
	public static function findCurrentByPid($intPid, $intStart, $intEnd, array $arrOptions=array())
	{
		$t = static::$strTable;
		$intStart = (int) $intStart;
		$intEnd = (int) $intEnd;

		$arrColumns = array("$t.pid=? AND (($t.startTime>=$intStart AND $t.startTime<=$intEnd) OR ($t.endTime>=$intStart AND $t.endTime<=$intEnd) OR ($t.startTime<=$intStart AND $t.endTime>=$intEnd) OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$intStart) AND $t.startTime<=$intEnd))");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.startTime";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find published events with the default redirect target by their parent ID
	 *
	 * @param integer $intPid     The calendar ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null A collection of models or null if there are no events
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='default'");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order']  = "$t.startTime DESC";
		}

		return static::findBy($arrColumns, $intPid, $arrOptions);
	}


	/**
	 * Find upcoming events by their parent IDs
	 *
	 * @param array   $arrIds     An array of calendar IDs
	 * @param integer $intLimit   An optional limit
	 * @param array   $arrOptions An optional options array
	 *
	 * @return Model\Collection|CalendarEventsModel[]|CalendarEventsModel|null A collection of models or null if there are no events
	 */
	public static function findUpcomingByPids($arrIds, $intLimit=0, array $arrOptions=array())
	{
		if (empty($arrIds) || !\is_array($arrIds))
		{
			return null;
		}

		$t = static::$strTable;
		$time = \Date::floorToMinute();

		// Get upcoming events using endTime instead of startTime (see #3917)
		$arrColumns = array("($t.endTime>=$time OR ($t.recurring='1' AND ($t.recurrences=0 OR $t.repeatEnd>=$time))) AND $t.pid IN(" . implode(',', array_map('intval', $arrIds)) . ") AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'");

		if ($intLimit > 0)
		{
			$arrOptions['limit'] = $intLimit;
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.startTime";
		}

		return static::findBy($arrColumns, null, $arrOptions);
	}
}
