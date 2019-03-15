<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Tvshows helper class.
 *
 * @package     Tvshows
 * @subpackage  Helpers
 */
class TvshowsHelper
{
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_TVSHOWS_SUBMENU_MOVIE'), 
			'index.php?option=com_tvshows&view=movies', 
			$vName == 'movies'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_TVSHOWS_SUBMENU_FILM'), 
			'index.php?option=com_tvshows&view=films', 
			$vName == 'films'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_TVSHOWS_SUBMENU_SEASON'), 
			'index.php?option=com_tvshows&view=seasons', 
			$vName == 'seasons'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES'), 
			'index.php?option=com_categories&extension=com_tvshows', 
			$vName == 'categories'
		);
	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_tvshows';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}