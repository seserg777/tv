<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for films.
 *
 * @package     Tvshows
 * @subpackage  Models
 */
class TvshowsModelMovies extends JModelList
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
				'a.rate_imdb', 'rate_imdb',
				'a.alias', 'alias',
				'a.checked_out', 'checked_out',
				'a.checked_out_time', 'checked_out_time',
				'a.catid', 'catid', 'category_id', 'category_title',
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
        $this->setState('filter.published', $published);

		// Set filter state for category
		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);		// Set filter state for season
		$season = $this->getUserStateFromRequest($this->context.'.filter.season', 'filter_season', '');
		$this->setState('filter.season', $season);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_tvshows');
		$active = $menu->getActive();
		empty($active) ? null : $params->merge($active->params);
		$this->setState('params', $params);		

		// List state information.
		parent::populateState($ordering, $direction);
		
        $this->setState('list.limit', 0);
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
		$id .= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
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
		$query->select('a.*, CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug')->from('#__tvshows_movies AS a');
			
		// Join over the categories.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the categories to get parent category titles
		$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
			->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen('id:')));
			}
			elseif (stripos($search, 'rate_imdb:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('rate_imdb:')), true) . '%');
				$query->where('(a.rate_imdb LIKE ' . $search);
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
		}
		
		// Filter by category
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId))
		{
			$query->where('a.catid = '.(int) $categoryId);
		}
		
		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'rate_imdb');
		$order = $this->getState('list.direction', 'ASC');
		$limit = $this->getState($this->context.'list.limit');
		$query->order($db->escape($sort).' '.$db->escape($order));
		$query->setLimit($limit);
		//$query->setLimit(30);
	
		//echo '<pre>';var_dump($query);
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
	
	public function search(){
		$db = $this->getDbo();
		$search = $this->getState('filter.search');
		
		if(stripos($search, 'title:') === 0){
			$search = $db->quote('%' . $db->escape(substr($search, strlen('title:')), true) . '%');
		}
		
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(array('id', 'title', 'alias', 'images'), array('id', 'title', 'alias', 'film_images')))			
			->from('#__tvshows_movies')
			->where('title LIKE ' . $search)
			->order('title ASC');
		$db->setQuery($query);
		$list = $db->loadObjectList();
		
		if(count($list)){
			foreach($list as $k => $v){
				$list[$k]->link = jRoute::_(TvshowsHelperRoute::getMovieRoute($v->id, $v->alias));
				$v->film_images = json_decode($v->film_images);
				$list[$k]->image = $v->film_images[array_rand($v->film_images)];
				$list[$k]->image = ltrim($list[$k]->image, '/');
			}
		}
		
		//echo '<pre>';var_dump($query);
		return $list;
	}
	
	public function getTitles(){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName('title'))
			->from($db->quoteName('#__tvshows_movies'))
			->where($db->quoteName('published') . ' = 1 ');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}
?>