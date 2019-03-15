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
}
?>