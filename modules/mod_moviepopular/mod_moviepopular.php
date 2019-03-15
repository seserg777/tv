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
JLoader::register('ModMoviepopularHelper', __DIR__ . '/helper.php');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$predefined_list_films = $params->get('movies', null);

$cacheparams = new stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'ModMoviepopularHelper';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

if(isset($predefined_list_films) && !empty($predefined_list_films) && count($predefined_list_films)){
	$cacheparams->method = 'getItemsMovies';
	$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
	require JModuleHelper::getLayoutPath('mod_moviepopular', $params->get('layout', 'moviescarousel'));
} else {
	$cacheparams->method = 'getListMovies';
	$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
	require JModuleHelper::getLayoutPath('mod_moviepopular', $params->get('layout', 'moviescarousel'));
}