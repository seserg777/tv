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
JLoader::register('ModTvcalendarHelper', __DIR__ . '/helper.php');

/*$params->currentTime = new JDate('now');
$params->currentTime = $params->currentTime->format('Y-m-d');
$params->startTime = date('Y-m-01', strtotime($params->currentTime));
$params->endTime =date('Y-m-t', strtotime($params->currentTime));

$cacheparams = new stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'ModTvcalendarHelper';
$cacheparams->method = 'getEvents';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

$events = JModuleHelper::moduleCache($module, $params, $cacheparams);*/

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_tvcalendar', $params->get('layout', 'default'));
