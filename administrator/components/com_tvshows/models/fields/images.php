<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldImages extends JFormField {
 
	protected $type = 'Images';
	
	public function getImages() {
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$film_id = $jinput->get('id', null, 'INT');
		$view = $jinput->get('view', null, 'INT');
		
		if($film_id && $view == 'film'){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select($db->quoteName("images"))
				->from("#__tvshows_film")
				->where($db->quoteName('id') . ' = ' . $db->quote($film_id))
				->where($db->quoteName('published') . ' = 1');
			$db->setQuery($query);
			$result = $db->loadResult();

			return $result;
		}

		if($film_id && $view == 'season'){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select($db->quoteName("images"))
				->from("#__tvshows_season")
				->where($db->quoteName('id') . ' = ' . $db->quote($film_id))
				->where($db->quoteName('published') . ' = 1');
			$db->setQuery($query);
			$result = $db->loadResult();

			return $result;
		}
		
		return false;
	}
	
	public function getInput() {

		$document = JFactory::getDocument();
		$document->addScript('/administrator/components/com_tvshows/assets/js/images.js');
		JHtml::_('behavior.modal', 'a.modal');

		$app = JFactory::getApplication();
		$jinput = $app->input;
		$film_id = $jinput->get('id', null, 'INT');
		$html = '';
		$images = $this->getImages();
		
		$html .= '<input id="jform_img_path" value="" placeholder="Select image" name="jform_img_path" size="25" readonly="readonly" class="jform_img_path" onclick="selectImg(this, \''.JURI::base().'\')" />';

		if(!is_null($film_id)){
			if(!empty($images)){
				$images = json_decode($images);

				$html .= '<ul class="item-images">';
				
				foreach($images as $k => $v){
					$html .= '<li data-image-id="'.$k.'" ><span class="delete" onclick="deleteImage('.$k.')"><i class="icon-trash icon-white"></i></span><img width="120" src="'.JUri::root().'/'.$v.'">
						<input type="hidden" name="jform[images]['.$k.']" value="'.$v.'"/>
					</li>';
				}
				
				$html .= '</ul>';
			}
		} else {
			$html .= '<span class="alert alert-info">Чтобы загрузить изображение - сперва нажмите "Сохранить"</span>';
		}
		return $html;
	}
}