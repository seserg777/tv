<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.vote
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Vote plugin.
 *
 * @since  1.5
 */
class PlgContentTshowstag extends JPlugin
{
	/**
	 * Application object
	 *
	 * @var    JApplicationCms
	 * @since  3.7.0
	 */
	protected $app;


	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *
	 * @since   3.7.0
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		$this->votingPosition = $this->params->get('position', 'top');
	}

	/**
	 * Displays the voting area when viewing an article and the voting section is displayed before the article
	 *
	 * @param   string   $context  The context of the content being passed to the plugin
	 * @param   object   &$row     The article object
	 * @param   object   &$params  The article params
	 * @param   integer  $page     The 'page' number
	 *
	 * @return  string|boolean  HTML string containing code for the votes if in com_content else boolean false
	 *
	 * @since   1.6
	 */
	public function onContentBeforeDisplay($context, &$row, &$params, $page = 0)
	{
		if($context == 'com_tags.tag'){
			//echo '<pre>';var_dump($row);

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
			    ->select($db->quoteName('main_image'))
			    ->from($db->quoteName('#__tvshows_film'))
			    ->where($db->quoteName('id') . ' = '.$db->quote((int)$row->content_item_id));
			$db->setQuery($query);
			$row->main_image = $db->loadResult();
		}
	}
}