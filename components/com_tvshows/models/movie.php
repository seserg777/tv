<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Item Model for film.
 *
 * @package     Tvshows
 * @subpackage  Models
 */
class TvshowsModelMovie extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_TVSHOWS';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_tvshows.movie';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->published != -2)
			{
				return false;
			}
			

			$user = JFactory::getUser();
			return $user->authorise('core.delete', $this->typeAlias . '.' . (int) $record->id);
		}
	}		

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable    A JTable object.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('id');
		$this->setState($this->getName() . '.id', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_tvshows');
		$this->setState('params', $params);
	}
	
	/**
	 * Alias for JTable::getInstance()
	 *
	 * @param   string  $type    The type (name) of the JTable class to get an instance of.
	 * @param   string  $prefix  An optional prefix for the table class name.
	 * @param   array   $config  An optional array of configuration values for the JTable object.
	 *
	 * @return  mixed    A JTable object if found or boolean false if one could not be found.
	 */
	public function getTable($type = 'Movie', $prefix = 'TvshowsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR.'/models/fields');

		JForm::addRulePath(JPATH_COMPONENT_ADMINISTRATOR.'/models/rules');		
		
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form = $this->loadForm($this->typeAlias, $this->name, $options);
		
		if(empty($form))
		{
			return false;
		}

		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 */
	protected function loadFormData()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState($this->option . '.edit.' . $this->name . '.data', array());
		
		if(empty($data))
		{
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('movie.id');

		if (isset($this->_item) && $this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{

			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('a.*')
					->from('#__tvshows_movies AS a');

				// Join on category table.
				//$query->select('s.title AS season_title, s.alias AS season_alias, s.id AS season_id')
				//	->join('LEFT', '#__tvshows_season AS s on s.film = a.id');

				// Join on category table.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access')
					->join('LEFT', '#__categories AS c on c.id = a.catid');

				// Join over the categories to get parent category titles
				$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
					->join('LEFT', '#__categories as parent ON parent.id = c.parent_id')
					->where('a.id = ' . (int) $pk);##{start_publishing}##

				// Filter by start and end dates.
				$nullDate = $db->quote($db->getNullDate());
				$nowDate = $db->quote(JFactory::getDate()->toSql());

				// Filter by published state.
				$published = $this->getState('filter.published');
				$archived = $this->getState('filter.archived');
				if (is_numeric($published))
				{
					$query->where('(a.published = ' . (int) $published . ' OR a.published =' . (int) $archived . ')');
					$query->where('(c.published = ' . (int) $published . ' OR c.published =' . (int) $archived . ')');
				}

				$db->setQuery($query);

				$data = $db->loadObject();

				if (empty($data))
				{
					JError::raiseError(404, JText::_('COM_TVSHOWS_ERROR_FEED_NOT_FOUND'));
				}

				// Convert parameter fields to objects.
				$registry = new JRegistry;
				$data->params = clone $this->getState('params');
				$data->params->merge($registry);

				$registry = new JRegistry;
				$registry->loadString($data->metadata);
				$data->metadata = $registry;

				//$registry = new JRegistry;
				//$registry->loadString($data->images);
				//$data->images = $registry;
				$data->images = json_decode($data->images);

				$this->_item[$pk] = $data;

				/*$query = $db->getQuery(true)
					->select($db->quoteName(array('s.id', 's.title', 's.alias', 's.bage', 's.links', 's.main_image', 's.episode_count', 's.published', 's.season_number')))
					->from($db->quoteName('#__tvshows_season', 's'))
					->where($db->quoteName('s.film') .' = '. $db->quote($pk))
					->where($db->quoteName('s.published') .' = 1');
				$db->setQuery($query);
				$seasons = $db->loadObjectList();*/

			  	$this->_item[$pk]->tags = new JHelperTags;
			  	//$this->_item[$pk]->tags->getTagIds($pk, 'com_tvshows.movie');
				$this->_item[$pk]->tags->getItemTags('com_tvshows.movie', $pk);

				//$this->_item[$pk]->seasons = $seasons;

			}
			catch (Exception $e)
			{
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}



		return $this->_item[$pk];
	}
	
	/**
	 * Increment the hit counter for the item.
	 *
	 * @param   integer  $pk  Optional primary key of the item to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 */
	public function hit($pk = 0)
	{
		$input = JFactory::getApplication()->input;
		$hitcount = $input->getInt('hitcount', 1);

		if ($hitcount)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('movie.id');

			$table = $this->getTable();
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}
	
	public function getNeighbors(){
		$item = $this->getItem();
		if(isset($item->id) && !empty($item->id)){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
			    ->select('*')
			    ->from($db->quoteName('#__tvshows_movies'))
				->where($db->quoteName('published') . ' = 1')
			    ->where($db->quoteName('id') . ' <> '.$db->quote((int)$item->id));
			$db->setQuery($query);
			$query->order('RAND() LIMIT 3');
			return $db->loadObjectList();
			//echo '<pre>';var_dump($results);
		}

		return array();
	}
}
?>