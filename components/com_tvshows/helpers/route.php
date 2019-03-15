<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
//die('qqq');
/**
 * Tvshows Component Route Helper
 *
 * @package     Tvshows
 * @subpackage  Helpers
 */
abstract class TvshowsHelperRoute
{
	protected static $lookup;

	protected static $lang_lookup = array();

	public static function getCategoryRoute($catid, $language = 0, $layout = "")
	{
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;
			$category = $catid;
		}
		else
		{
			$id = (int) $catid;
			$category = JCategories::getInstance('Tvshows')->get($id);
		}

		if ($id < 1 || !($category instanceof JCategoryNode))
		{
			$link = '';
		}
		else
		{
			$needles = array();

			// Create the link
			$link = 'index.php?option=com_tvshows&view=category&id='.$id;

			$catids = array_reverse($category->getPath());
			$needles['category'] = $catids;
			$needles['categories'] = $catids;
				
			if ($layout != "")
			{
				$link .= "&layout=" . $layout;
			}

			if ($item = self::_findItem($needles))
			{
				$link .= '&Itemid='.$item;
			}
		}

		return $link;
	}

	/**
	 * @param   integer  The route of the content item
	 */
	public static function getFilmRoute($id, $alias, $catid = 0,$language = 0)
	{
		$needles = array(
			'film'  => array((int) $id)
		);

		if(strripos($id, ':') != false){
			$id = explode(':', $id);
			$alias = $id[1];
			unset($id);
		}

		//Create the link
		$link = 'index.php?option=com_tvshows&view=film';
		if(isset($id) && !empty($id)){
			$link .= '&id='. $id;
			//var_dump($id);
		}
		if(isset($alias) && !empty($alias)){
			$link .= '&alias='. $alias;
		}
		if ((int) $catid > 1)
		{
			$categories = JCategories::getInstance('Tvshows');
			$category = $categories->get((int) $catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			//echo 'qqq';
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	 * @param   integer  The route of the content item
	 */
	public static function getSeasonRoute($id, $alias, $film, $catid = 0, $language = 0)
	{

		$needles = array(
			'season'  => array((int) $id)
		);
		
		/*if(strripos($alias, ':') === false){
			$alias = $alias.':'.$id;
		}*/
		
		//Create the link
		$link = 'index.php?option=com_tvshows&view=season';

		if(isset($id) && !empty($id)){
			$link .= '&id='.$id;
		}
		if(isset($film) && !empty($film)){
			//var_dump($film);
			$link .= '&film='.$film;
		}	
		if(isset($alias) && !empty($alias)){
			//var_dump($alias);
			$link .= '&alias='.$alias;
		}		

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$language	= isset($needles['language']) ? $needles['language'] : '*';
		$type	= isset($needles['type']) ? $needles['type'] : 'films';

		//echo '<pre>';var_dump($needles);

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language]))
		{
			self::$lookup[$language] = array();

			$component	= JComponentHelper::getComponent('com_tvshows');

			$attributes = array('component_id');
			$values = array($component->id);

			if ($language != '*')
			{
				$attributes[] = 'language';
				$values[] = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					if (!isset(self::$lookup[$language][$view]))
					{
						self::$lookup[$language][$view] = array();
					}

					if (isset($item->query['id']))
					{

						// here it will become a bit tricky
						// language != * can override existing entries
						// language == * cannot override existing entries
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*')
						{
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$language][$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$language][$view][(int) $id]))
						{
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}
		}

		// Check if the active menuitem matches the requested language
		$active = $menus->getActive();
		if(!isset($active) || !array_key_exists($active->query['view'], $needles)){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
			    ->select($db->quoteName('id'))
			    ->from($db->quoteName('#__menu'))
			    ->where($db->quoteName('link') . ' LIKE '. $db->quote('index.php?option=com_tvshows&view='.$type))
			    ->where($db->quoteName('menutype') . ' <> '. $db->quote('main'));
			$db->setQuery($query);
			$result = $db->loadResult();
			if($result){
				return $result;
			}
		} else {
			if ($active && ($language == '*' || in_array($active->language, array('*', $language)) || !JLanguageMultilang::isEnabled()))
			{
				return $active->id;
			}
		}

		// If not found, return language specific home link
		$default = $menus->getDefault($language);
		return !empty($default->id) ? $default->id : null;
	}
	
	/**
	 * @param   integer  The route of the content item
	 */
	public static function getMovieRoute($id, $alias, $catid = 0,$language = 0)
	{
		$needles = array(
			'movie'  => array((int) $id),
			'type' => 'movies'
		);

		if(strripos($id, ':') != false){
			$id = explode(':', $id);
			$alias = $id[1];
			unset($id);
		}

		//Create the link
		$link = 'index.php?option=com_tvshows&view=movie';
		if(isset($id) && !empty($id)){
			$link .= '&id='. $id;
			//var_dump($id);
		}
		if(isset($alias) && !empty($alias)){
			$link .= '&alias='. $alias;
		}
		if ((int) $catid > 1)
		{
			$categories = JCategories::getInstance('Tvshows');
			$category = $categories->get((int) $catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			//echo 'qqq';
			$link .= '&Itemid='.$item;
		}
		
		//var_dump($link);
		return $link;
	}
}
