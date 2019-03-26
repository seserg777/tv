<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Films list controller class.
 *
 * @package     Tvshows
 * @subpackage  Controllers
 */
class TvshowsControllerMovies extends JControllerAdmin
{
	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'movies';
	
	/**
	 * Get the admin model and set it to default
	 *
	 * @param   string           $name    Name of the model.
	 * @param   string           $prefix  Prefix of the model.
	 * @param   array			 $config  The model configuration.
	 */
	public function getModel($name = 'Movie', $prefix='TvshowsModel', $config = array())
	{
		$config['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function search(){
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$search = $jinput->get('search');
		
		$return = false;
		
		$model = $this->getModel('Movies', 'TvshowsModel');
		$model->setState('filter.search', 'title:'.$search);
		$list = $model->search();
		
		$return['list'] = $list;
		
		echo json_encode( array('data' => $return) );
		$app->close();
	}
	
	public function getItemsByletter(){
		JSession::checkToken('get') or die( 'Invalid Token' );
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$letter = $jinput->get('letter', null, 'STRING');
		if(isset($letter) && !empty($letter)){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select($db->quoteName(array('title', 'alias')))
				->from($db->quoteName('#__tvshows_movies'))
				->where($db->quoteName('title') . ' LIKE '.$db->quote($letter.'%'))
				->where($db->quoteName('published') . ' = 1 ');
			$db->setQuery($query);
			$result = $db->loadObjectList();
			
			foreach($result as $k => $v){
				$result[$k]->link = jRoute::_(TvshowsHelperRoute::getMovieRoute(null, $v->alias));
			}
		} else {
			$result = array();
		}
		
		echo new JResponseJson($result);
		$app->close();
	}
}
?>