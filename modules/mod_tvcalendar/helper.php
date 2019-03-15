<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_ROOT . '/components/com_tvshows/helpers/route.php';

abstract class ModTvcalendarHelper {

	public static function getAjax(){
		$app = jFactory::getApplication();
		$jinpit = $app->input;
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/json');
		JResponse::setHeader('Content-Disposition','attachment;filename=result.json');

		$start = $jinpit->get('start', null, 'STRING');
		$end = $jinpit->get('end', null, 'STRING');

		$result = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('s.season_number', 's.id', 's.alias', 's.title', 'f.next_episode_time', 'f.alias', 'f.title'), array('season_number', 'id', 'alias', 'title', 'start', 'film_alias', 'film_title')))
		    ->from($db->quoteName('#__tvshows_season', 's'))
		    ->join('INNER', $db->quoteName('#__tvshows_film', 'f') . ' ON (' . $db->quoteName('f.next_episode_season_number') . ' = ' . $db->quoteName('s.season_number'). ' AND '. $db->quoteName('f.id') . ' = ' . $db->quoteName('s.film') .')')
			->where($db->quoteName('f.next_episode_time') . ' <> '. $db->quote($db->getNullDate()))
		    ->where($db->quoteName('f.next_episode_time') . ' <= '. $db->quote(JFactory::getDate($end)->toSql()))
		    ->where($db->quoteName('f.next_episode_time') . ' >= '. $db->quote(JFactory::getDate($start)->toSql()))
			->where($db->quoteName('s.season_number') . ' > 0');
			//->where($db->quoteName('f.next_episode_time') . ' = '.$db->quote('2018-09-12 00:00:00'));
		$db->setQuery($query);
		$result['events'] = $db->loadObjectList();
		//var_dump($result['events']);

		if(count($result['events'])){
			foreach($result['events']  as $k => $v){
				$date = new JDate($result['events'][$k]->start);
				$result['events'][$k]->start = $date->format('Y-m-d');
				$result['events'][$k]->url = JRoute::_(TvshowsHelperRoute::getSeasonRoute(null, $result['events'][$k]->alias, $result['events'][$k]->film_alias));
				$result['events'][$k]->title = $v->film_title . ' | S' . $v->season_number;
			}
		}

		echo json_encode($result);
		$app->close();
	}

	/**
	 * Get a list of tags
	 *
	 * @param   Registry  &$params  Module parameters
	 *
	 * @return  array
	 */
	public static function getEvents($params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('id', 'alias', 'title', 'next_episode_time'), array('id', 'alias', 'title', 'start')))
		    ->from($db->quoteName('#__tvshows_film'))
		    ->where($db->quoteName('next_episode_time') . ' <= '. $db->quote(JFactory::getDate($params->endTime)->toSql()))
		    ->where($db->quoteName('next_episode_time') . ' >= '. $db->quote(JFactory::getDate($params->startTime)->toSql()));
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;
	}
}
