<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for seasons.
 *
 * @package     Tvshows
 * @subpackage  Models
 */
class TvshowsModelSeasons extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'a.title', 'title',
				'a.alias', 'alias',
				'a.checked_out', 'checked_out',
				'a.checked_out_time', 'checked_out_time',
				'a.published', 'published',
				'a.created', 'created',
				'a.ordering', 'ordering',
				'a.hits', 'hits','state'
			);
		}
		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'title', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

		// Set filter state for publish state
        $published = $app->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        $this->setState('filter.published', $published);		// Set filter state for film
		$film = $this->getUserStateFromRequest($this->context.'.filter.film', 'filter_film', '');
		$this->setState('filter.film', $film);
		

		// Load the parameters.
		$params = JComponentHelper::getParams('com_tvshows');
		$active = $menu->getActive();
		empty($active) ? null : $params->merge($active->params);
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}
	
	public function search(){
		$db = $this->getDbo();
		$search = $this->getState('filter.search');
		
		if(stripos($search, 'title:') === 0){
			$search = $db->quote('%' . $db->escape(substr($search, strlen('title:')), true) . '%');
		}
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('a.id', 'a.title', 'a.alias', 'film.title', 'film.alias', 'film.images', 'a.episode_count'), array('id', 'title', 'alias', 'film_title', 'film_alias', 'film_images', 'episode_count')))			
			->from('#__tvshows_season AS a')
			->join('LEFT', '#__tvshows_film AS film ON film.id = a.film')
			->where('film.title LIKE ' . $search)
			->order('a.title ASC');
		$db->setQuery($query);
		$list = $db->loadObjectList();
		
		if(count($list)){
			foreach($list as $k => $v){
				$list[$k]->link = jRoute::_(TvshowsHelperRoute::getSeasonRoute(null, $v->alias, $v->film_alias));
				$v->film_images = json_decode($v->film_images);
				$list[$k]->image = $v->film_images[array_rand($v->film_images)];
				$list[$k]->image = ltrim($list[$k]->image, '/');
			}
		}
		
		//echo '<pre>';var_dump($query);
		return $list;
	}
	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Get database object
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__tvshows_season AS a');		//Join over films
		$query->select('film.rate_imdb AS ' . $db->quote('film_rate_imdb'))
			->join('LEFT', '#__tvshows_film AS film ON film.id = a.film');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id = a.checked_out');

		// Filter by search
		$search = $this->getState('filter.search');
		//var_dump($search);
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen('id:')));
			}
			elseif (stripos($search, 'title:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('title:')), true) . '%');
				$query->where('(a.title LIKE ' . $search);
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				
			}
		}
		
		// Filter by published state.
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			// Only show items with state 'published' / 'unpublished'
			$query->where('(a.published IN (0, 1))');
		}		// Filter by film
		$film = $this->getState('filter.film');
		if ($film != "")
		{
			$query->where('a.film = ' . $db->quote($db->escape($film)));
		}

		
		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'title');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));
		
		return $query;
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		if ($items = parent::getItems()) {
			//Do any procesing on fields here if needed
		}

		return $items;
	}
}
?>