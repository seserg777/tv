<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

use Joomla\CMS\Component\Router\RouterLegacy;

class TvshowsRouter extends RouterLegacy {

	public function __construct($component)
	{
		$this->component = 'Tvshows';
	}

	public function parse(&$segments)
	{
		$function = $this->component . 'ParseRoute';

		if (function_exists($function))
		{
			$total = count($segments);
			if($total > 1){
				$segments[1] = preg_replace('/-/', ':', $segments[1], 1);
			}

			//echo '<pre>';var_dump($segments);//exit;
			return $function($segments);
		}

		return array();
	}
}

/**
 * @param	array	A named array
 * @return	array
 */
function TvshowsBuildRoute(&$query)
{
	//echo 'build';
	
	//echo '<pre>';var_dump($query);
	
	$segments = array();
	if (isset($query['task'])) {
		$segments[] = implode('/',explode('.',$query['task']));
		unset($query['task']);
	}
	if (isset($query['film'])) {
		$segments[] = $query['film'];
		unset($query['film']);
	}
	if (isset($query['id'])) {
		//$segments[] = $query['id'];
		unset($query['id']);
	}
	if (isset($query['alias'])) {
		$segments[] = $query['alias'];
		unset($query['alias']);
	}
	if (isset($query['view'])) {
		//$segments[] = $query['view'];
		unset($query['view']);
	}

	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/test/task/id/Itemid
 *
 * index.php?/test/id/Itemid
 */
function TvshowsParseRoute($segments)
{
	$jinput = jFactory::getApplication()->input;
	$vars = array();
    
	// view is always the first element of the array
	$count = count($segments);

	//echo '<pre>';var_dump($segments);

    if ($count)
	{
		$count--;
		$segment = array_pop($segments) ;
		//var_dump($segment, $vars);
		
		if (is_numeric($segment)) {
			//$vars['id'] = $segment;
			JError::raiseError(404, JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));
		}

		if (is_string($segment)) {

			if(strripos($segment, ':') === false){
				
				if($count){
					//echo '111';
					
					$db = JFactory::getDbo();
					$qry = $db->getQuery(true);
					$qry
						->select($db->quoteName('s.id'))
						->from($db->quoteName('#__tvshows_season', 's'))
						//$qry->where('alias = ' . $db->quote($segment[0].'-'.$segment[1]));
						->join('INNER', $db->quoteName('#__tvshows_film', 'f') . ' ON (' . $db->quoteName('f.alias') . ' = ' . $db->quote($segments[$count-1]) . ')')
						->where('s.alias = ' . $db->quote(JFilterOutput::stringURLSafe($segment)))
						->where('s.film = f.id');
					$db->setQuery($qry);
					$id = $db->loadResult();
					
					if(!empty($id))
					{
						$vars['id'] = $id;
						$vars['view'] = 'season';
					}
				} else {
					//echo '222';
					
					$vars['alias'] = $segment;

					if(isset($vars['alias']) && !isset($vars['id'])) {
						//echo '333';
						$db = JFactory::getDbo();
						$qry = $db->getQuery(true);
						$qry->select('id');
						$qry->from('#__tvshows_film');
						$qry->where('alias = ' . $db->quote($vars['alias']));
						$db->setQuery($qry);
						$id = $db->loadResult();

						if(!empty($id)) {
							$vars['id'] = $id;
							$vars['view'] = 'film';
						} else {
							//echo '444';
							$db = JFactory::getDbo();
							$qry = $db->getQuery(true);
							$qry->select('id');
							$qry->from('#__tvshows_movies');
							$qry->where('alias = ' . $db->quote($vars['alias']));
							$db->setQuery($qry);
							$id = $db->loadResult();

							if(!empty($id))
							{
								$vars['id'] = $id;
								$vars['view'] = 'movie';
							}
						}
					}
				}
				
			} else {
				$segment = explode(':', $segment);
				
				if(count($segment)) {
					//echo 'zzz';
					$db = JFactory::getDbo();
					$qry = $db->getQuery(true);
					$qry
						->select($db->quoteName('s.id'))
						->from($db->quoteName('#__tvshows_season', 's'))
						//$qry->where('alias = ' . $db->quote($segment[0].'-'.$segment[1]));
						->join('INNER', $db->quoteName('#__tvshows_film', 'f') . ' ON (' . $db->quoteName('f.alias') . ' = ' . $db->quote($segments[$count-1]) . ')')
						->where('s.alias = ' . $db->quote(JFilterOutput::stringURLSafe(implode('-',$segment))))
						->where('s.film = f.id');
					$db->setQuery($qry);
					$id = $db->loadResult();
					//var_dump($segments[$count-1], $id, JFilterOutput::stringURLSafe(implode('-',$segment)));

					if(!empty($id)) {
						$vars['id'] = $id;
						$vars['view'] = 'season';
					}

					if(!count($segments)){
						JError::raiseError(404, JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'));
					}
				}
			}

		} else {
            $count--;
            $vars['task'] = array_pop($segments) . '.' . $segment;
        }
	}

	if ($count) {   
        $vars['task'] = implode('.',$segments);
	}
	return $vars;
}
