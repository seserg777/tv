<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/tvshows.php';
require_once JPATH_COMPONENT_SITE.'/helpers/film.php';
require_once JPATH_COMPONENT_SITE.'/helpers/movie.php';
require_once JPATH_COMPONENT_SITE.'/helpers/category.php';
require_once JPATH_COMPONENT_SITE.'/helpers/route.php';

$controller	= JControllerLegacy::getInstance('Tvshows');
$input = JFactory::getApplication()->input;

$lang = JFactory::getLanguage();
$lang->load('joomla', JPATH_ADMINISTRATOR);

try {
	$controller->execute($input->get('task'));
} catch (Exception $e) {
	$controller->setRedirect(JURI::base(), $e->getMessage(), 'error');
}

$controller->redirect();?>