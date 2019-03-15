<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class ModTvpopularHelper {

	public static function getListSeasons($params) {
		//echo 'getListSeasons';
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_tvshows/models', 'TvshowsModel');

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();
		$keywords = explode(',', $document->getMetaData('keywords'));

		$model = JModelLegacy::getInstance('Seasons', 'TvshowsModel', array('ignore_request' => true));
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 24));
		$model->setState('filter.published', 1);
		$model->setState('list.direction', 'DESC');
		
		$ordering = $params->get('order_type', 0);
		if($ordering == 1){
			$model->setState('list.ordering', JFactory::getDbo()->getQuery(true)->Rand());
		} else {
			$model->setState('list.ordering', 'a.hits');
		}

		$popular = $model->getItems();

		if(count($popular)){
			foreach($popular as $k => $v){
				if(isset($v->film) && !empty($v->film)){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
					    ->select('*')
					    ->from($db->quoteName('#__tvshows_film'))
					    ->where($db->quoteName('id') . ' = '.$db->quote((int)$v->film));
					$db->setQuery($query);
					$result = $db->loadObject();

					if($result){
						$popular[$k]->film = $result;
					}
				}
			}
		}

		return $popular;
	}
	
	public static function getListFilms(&$params) {
		//echo 'getListFilms';
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_tvshows/models', 'TvshowsModel');

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();
		$keywords = explode(',', $document->getMetaData('keywords'));		

		$model = JModelLegacy::getInstance('Films', 'TvshowsModel', array('ignore_request' => true));
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 24));
		$model->setState('filter.published', 1);
		
		$ordering = $params->get('order_type', 0);
		if($ordering == 1){
			$model->setState('list.ordering', JFactory::getDbo()->getQuery(true)->Rand());
		} else {
			$model->setState('list.ordering', 'a.hits');
		}
		
		$model->setState('list.direction', 'DESC');

		$popular = $model->getItems();
		//echo '<pre>';var_dump($popular);exit;

		return $popular;
	}
	
	public static function getItemsSeasons($params){
		//echo 'getItemsSeasons';
		$ids = $params->get('seasons', null);
		//var_dump($ids);
		$result = array();
		$model = JModelLegacy::getInstance('Season', 'TvshowsModel');
		
		foreach($ids as $id){
			$model->setState('season.id', $id);
			$item = $model->getItem($id);
			
			if($item->published){
				$result[] = $item;
			}
		}
		
		return $result;
	}
	
	public static function getItemsFilms($params){
		//echo 'getItemsFilms';
		$ids = $params->get('films', null);
		$result = array();
		$model = JModelLegacy::getInstance('Film', 'TvshowsModel');
		
		foreach($ids as $id){
			$model->setState('film.id', $id);
			$item = $model->getItem($id);
			if($item->published){
				$result[] = $item;
			}
		}
		
		return $result;
	}
}
