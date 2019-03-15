<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');
//JLoader::register('TagsModelTags', JPATH_BASE . '/components/com_tags/models/tags.php');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_tags/models', 'TagsModel');

/**
 * Helper for mod_tags_similar
 *
 * @since  3.1
 */
abstract class ModTagsallHelper {

	/**
	 * Get a list of tags
	 *
	 * @param   Registry  &$params  Module parameters
	 *
	 * @return  array
	 */
	public static function getList($params)
	{
		$model = JModelLegacy::getInstance('Tags', 'TagsModel');
		return $model->getItems();
	}
}
