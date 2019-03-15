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

class ModMovieslatestHelper
{

	public static function getList(&$params)
	{
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_tvshows/models', 'TvshowsModel');

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();
		$keywords = explode(',', $document->getMetaData('keywords'));

		$model = JModelLegacy::getInstance('Movies', 'TvshowsModel', array('ignore_request' => true));
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));
		$model->setState('filter.published', 1);
		$model->setState('list.ordering', 'a.modified');
		$model->setState('list.direction', 'DESC');

		return $model->getItems();
	}
}
