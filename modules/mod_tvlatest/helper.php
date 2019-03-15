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

class ModTvlatestHelper
{

	public static function getList(&$params)
	{
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_tvshows/models', 'TvshowsModel');

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();
		$keywords = explode(',', $document->getMetaData('keywords'));

		$model = JModelLegacy::getInstance('Seasons', 'TvshowsModel', array('ignore_request' => true));
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));
		$model->setState('filter.published', 1);
		$model->setState('list.ordering', 'a.modified');
		$model->setState('list.direction', 'DESC');

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
}
