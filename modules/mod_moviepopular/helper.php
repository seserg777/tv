<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class ModMoviepopularHelper {
	
	public static function getListMovies(&$params) {
		//echo 'getListFilms';
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_tvshows/models', 'TvshowsModel');

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();
		$keywords = explode(',', $document->getMetaData('keywords'));		

		$model = JModelLegacy::getInstance('Movies', 'TvshowsModel', array('ignore_request' => true));
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
	
	public static function getItemsMovies($params){
		//echo 'getItemsFilms';
		$ids = $params->get('movies', null);
		$result = array();
		$model = JModelLegacy::getInstance('Movie', 'TvshowsModel');
		
		foreach($ids as $id){
			$model->setState('movie.id', $id);
			$item = $model->getItem($id);
			if($item->published){
				$result[] = $item;
			}
		}
		
		return $result;
	}
}
