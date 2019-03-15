<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
JFormHelper::loadFieldClass('list');

/**
 * Form field for Film items.
 *
 * @package		Tvshows
 * @subpackage	Fields
 */
class JFormFieldFilm extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'film';

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
		$query->select("a.id, a.title")->from("#__tvshows_film as a");

		// Implement View Level Access
		$user = JFactory::getUser();
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
		}

		$db->setQuery($query);
		
		foreach ($db->loadObjectList() as $item)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', $item->id, $item->title);

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
?>