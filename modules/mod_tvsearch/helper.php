<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_ROOT . '/components/com_tvshows/helpers/route.php';
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_tvshows/models');

class ModTvsearchHelper {

	public static function getList() {
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_tvshows/models', 'TvshowsModel');

		$app      = JFactory::getApplication();
		$jinput = $app->input;
		$search = $jinput->get('s', null, 'STRING');
		
		$model = JModelLegacy::getInstance('Seasons', 'TvshowsModel', array('ignore_request' => true));
		$model->setState('filter.search', 'title:'.$search);
		$seasonsResult = $model->search();
		
		$model = JModelLegacy::getInstance('Movies', 'TvshowsModel', array('ignore_request' => true));
		$model->setState('filter.search', 'title:'.$search);
		$moviesResult = $model->search();
		
		$result = array_merge($seasonsResult, $moviesResult);
		
		return $result;
	}
}