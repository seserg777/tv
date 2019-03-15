<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
//echo 'qqq';
// Include the tags_similar functions only once
JLoader::register('ModTagsallHelper', __DIR__ . '/helper.php');
require_once JPATH_ROOT . '/components/com_tags/helpers/route.php';

$cacheparams = new stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'ModTagsallHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

$user   = JFactory::getUser();
$groups = $user->getAuthorisedViewLevels();

$list = JModuleHelper::moduleCache($module, $params, $cacheparams);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_tags_all', $params->get('layout', 'default'));
