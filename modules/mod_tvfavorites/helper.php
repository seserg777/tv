<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
//require_once JPATH_ROOT . '/components/com_tvshows/models/season.php';
//jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_tvshows/models');
//use Joomla\CMS\MVC\Model\BaseDatabaseModel;

abstract class ModTvfavoritesHelper {
	
	public static function getList($params)
	{
		$seasons_ids = $params->get('films', null);
		$seasons = array();

		if(count($seasons_ids)){
			$model = JModelLegacy::getInstance('Season', 'TvshowsModel');
			foreach ($seasons_ids as $key => $value) {
				$model->setState('season.id', $value);
				$model->setState('filter.published', 1);
				$item = $model->getItem($value);
				if($item->published){
					$seasons[] = $item;
				}
			}
		}

		return $seasons;
	}
}
