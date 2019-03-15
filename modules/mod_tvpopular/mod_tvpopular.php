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
JLoader::register('ModTvpopularHelper', __DIR__ . '/helper.php');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$type = $params->get('type', 0);
$predefined_list_films = $params->get('films', null);
$predefined_list_seasons = $params->get('seasons', null);
//var_dump($type);

$cacheparams = new stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'ModTvpopularHelper';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

if(!$type){
	//echo '000';
	if(isset($predefined_list_seasons) && !empty($predefined_list_seasons) && count($predefined_list_seasons)){
		//echo '333';
		$cacheparams->method = 'getItemsSeasons';
		$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
		//var_dump($list);
		//$list1 = ModTvpopularHelper::getItemsSeasons($predefined_list_seasons);
		require JModuleHelper::getLayoutPath('mod_tvpopular', $params->get('layout', 'seasonscarousel'));
	} else {
		//echo '444';
		$cacheparams->method = 'getListSeasons';
		$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
		//$list = ModTvpopularHelper::getListSeasons($params);
		require JModuleHelper::getLayoutPath('mod_tvpopular', $params->get('layout', 'seasonscarousel'));
	}
} else {
	if(isset($predefined_list_films) && !empty($predefined_list_films) && count($predefined_list_films)){
		//echo '111';
		$cacheparams->method = 'getItemsFilms';
		$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
		//$list = ModTvpopularHelper::getItemsFilms($predefined_list_films);
		
		require JModuleHelper::getLayoutPath('mod_tvpopular', $params->get('layout', 'filmscarousel'));
		
	} else {
		//echo '222';
		
		$cacheparams->method = 'getListFilms';
		$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
		
		//$list = ModTvpopularHelper::getListFilms($params);
		require JModuleHelper::getLayoutPath('mod_tvpopular', $params->get('layout', 'filmscarousel'));
	}
}