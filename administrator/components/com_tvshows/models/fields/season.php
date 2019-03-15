<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
JFormHelper::loadFieldClass('list');

/**
 * Form field for Season items.
 *
 * @package		Tvshows
 * @subpackage	Fields
 */
class JFormFieldSeason extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'season';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$options = array();
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array("s.id", "s.title", "f.title"),array("season_id", "season_title", "film_title")))
			->from($db->quoteName("#__tvshows_season", "s"))
			->join('LEFT', $db->quoteName('#__tvshows_film', 'f') . ' ON (' . $db->quoteName('s.film') . ' = ' . $db->quoteName('f.id') . ')')
			->order($db->quoteName('s.film') . ' ASC');

		$db->setQuery($query);
		
		foreach ($db->loadObjectList() as $item)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $item->season_id, $item->film_title.':'.$item->season_title);

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>