<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tvshows/tables');
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
/**
 * Item Model for season.
 *
 * @package     Tvshows
 * @subpackage  Models
 */
class TvshowsModelSeason extends JModelAdmin
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
	public $typeAlias = 'com_tvshows.season';

	protected $id;

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
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing item.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', $this->typeAlias . '.' . (int) $record->id);
		}
		// Default to component settings if neither article nor category known.
		else
		{
			return parent::canEditState($this->option);
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
	public function getTable($type = 'Season', $prefix = 'TvshowsTable', $config = array())
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
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();

		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());

				return false;
			}
		}

		// Convert to the \JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = ArrayHelper::toObject($properties, '\JObject');

		if (property_exists($item, 'params'))
		{
			$registry = new Registry($item->params);
			$item->params = $registry->toArray();
		}
		
		if(!$item)
		{			
			throw new Exception('Failed to load item');
		} else {
			require_once JPATH_ROOT.'/components/com_tvshows/helpers/season.php';
			
			$item->images = json_decode($item->images);
			$item->videos = json_decode($item->videos);
			$this->id = $pk;
			
			if(isset($item->film) && !empty($item->film)){
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query
				    ->select('*')
				    ->from($db->quoteName('#__tvshows_film'))
				    ->where($db->quoteName('id') . ' = '.$db->quote((int)$item->film));
				$db->setQuery($query);
				$result = $db->loadObject();

				if($result){
					$query = $db->getQuery(true)
						->select($db->quoteName(array('s.id', 's.title', 's.alias', 's.bage', 's.links', 's.main_image', 's.episode_count')))
						->from($db->quoteName('#__tvshows_season', 's'))
						->where($db->quoteName('s.film') .' = '. $db->quote((int)$item->film));
						//->where($db->quoteName('s.published') .' = 1');
					$db->setQuery($query);
					$seasons = $db->loadObjectList();
				
					$item->film = $result;
					if($seasons){
						$item->film->seasons = $seasons;
					}
				}
			}
			
			if(isset($item->links) && !empty($item->links)){				
				$item->links = TvshowsHelperSeason::handleEpisodes($item->links);
			}
		}

		if (!$item->id)
		{
		}
		
		return $item;
	}

	public function getEpisodes(){
		$item = $this->getItem();
		//echo '<pre>';var_dump($item->season_number);
		if(
			isset($item->film->id) && !empty($item->film->id) &&
			isset($item->season_number) && !empty($item->season_number)
		){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
			    ->select('*')
			    ->from($db->quoteName('#__tvshows_episodes'))
			    ->where($db->quoteName('tv_id') . ' = '.$db->quote((int)$item->film->id))
			    ->where($db->quoteName('season_number') . ' = '.$db->quote((int)$item->season_number))
				->order($db->quoteName('episode_number') . ' ASC');
			$db->setQuery($query);
			//$query->order('RAND() LIMIT 4');
			return $db->loadAssocList();
			//echo '<pre>';var_dump($results);
		}

		return array();
	}
	
	public function getNeighbors(){
		$item = $this->getItem();
		if(isset($item->film->id) && !empty($item->film->id)){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
			    ->select('*')
			    ->from($db->quoteName('#__tvshows_season'))
			    ->where($db->quoteName('film') . ' = '.$db->quote((int)$item->film->id))
				->where($db->quoteName('published') . ' = 1')
			    ->where($db->quoteName('id') . ' <> '.$db->quote((int)$item->id))
				->order($db->quoteName('season_number') . ' ASC');
			$db->setQuery($query);
			//$query->order('RAND() LIMIT 4');
			return $db->loadObjectList();
			//echo '<pre>';var_dump($results);
		}

		return array();
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
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('season.id');

			$table = $this->getTable();
			$table->load($pk);
			$table->hit($pk);
		}

		return true;
	}
}
?>