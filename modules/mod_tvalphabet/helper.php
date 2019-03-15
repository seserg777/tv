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
/**
 * Helper for mod_banners
 *
 * @since  1.5
 */
class ModTvalphabetHelper
{
	public static function getAjax(){
		JSession::checkToken('get') or die( 'Invalid Token' );
		$app = jFactory::getApplication();
		$jinpit = $app->input;
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/json');
		JResponse::setHeader('Content-Disposition','attachment;filename=result.json');

		$letter = $jinpit->get('letter', null, 'STRING');

		$result = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
		    ->select($db->quoteName(array('alias', 'title', 'id')))
		    ->from($db->quoteName('#__tvshows_film'));

		if($letter == '0-9'){
			/*$query
				->where($db->quoteName('title') . ' LIKE '. $db->quote('0%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('1%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('2%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('3%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('4%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('5%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('6%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('7%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('8%'),'OR')
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote('9%'));*/
			$query->where($db->qn('title').' REGEXP '. $db->q('^[0-9]'));
		} else {
			$query
				->where($db->quoteName('title') . ' LIKE '. $db->quote(strtolower($letter).'%'))
			    ->where($db->quoteName('title') . ' LIKE '. $db->quote(strtoupper($letter).'%'));
		}
		    
		$query->where($db->quoteName('published').' = 1');
			
		$query->order($db->quoteName('title') . ' ASC');
		$db->setQuery($query);
		$result['films'] = $db->loadObjectList();

		if(count($result['films'])){
			foreach($result['films'] as $k => $film){
				$query = $db->getQuery(true);
				$query
					->select('COUNT(*)')
					->from($db->quoteName('#__tvshows_season'))
					->where($db->quoteName('film') . ' = '. $db->quote((int)$film->id));
				$db->setQuery($query);
				$result['films'][$k]->count = $db->loadResult();

				$result['films'][$k]->link = JRoute::_(TvshowsHelperRoute::getFilmRoute(null, $result['films'][$k]->alias));
				if(substr($result['films'][$k]->link, 0, 1) == '/'){
					$result['films'][$k]->link = ltrim($result['films'][$k]->link, '/');
				}
			}
		}

		//echo new JResponseJson($result);
		echo json_encode($result);
		$app->close();
	}

	/**
	 * Retrieve list of banners
	 *
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return  mixed
	 */
	public static function &getList(&$params)
	{
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_tvshows/models', 'TvshowsModel');

		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();

		$model = JModelLegacy::getInstance('Films', 'TvshowsModel');
		$model->setState('filter.published', 1);

		$films = $model->getItems();

		return $films;
	}
}
