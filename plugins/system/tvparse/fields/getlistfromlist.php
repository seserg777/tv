<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldGetlistfromlist extends JFormField {
 
	protected $type = 'Getlistfromlist';
	
	public function getInput() {

		$document = JFactory::getDocument();
		$document->addScript('/plugins/system/tvparse/assets/js/xlsx.full.min.js');
		$document->addScript('/plugins/system/tvparse/assets/js/script.js');
		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<h3>Список для загрузки</h3>';
		$html .= '<div class="control-group"><div class="control-label"><label>Список themoviedbid</label></div><div class="controls"><textarea class="span2" name="ids" id="ids"></textarea></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Кол-во screencaps</label></div><div class="controls"><input type="number" class="span2" value="5" name="screencaps_count_list" id="screencaps_count_list"></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество изображения постера сезона</label></div><div class="controls"><select class="span2" value="5" name="season_poster_quality_getlistfromlist" id="season_poster_quality_getlistfromlist"><option value="w300">300</option><option value="w500">500</option><option value="w780" selected="selected">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество доп.изображений фильма</label></div><div class="controls"><select class="span2" value="5" name="film_screeencaps_quality_getlistfromlist" id="film_screeencaps_quality_getlistfromlist"><option value="w300">300</option><option value="w500">500</option><option value="w780" selected="selected">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество доп.изображений сезона</label></div><div class="controls"><select class="span2" value="5" name="season_screeencaps_quality_getlistfromlist" id="season_screeencaps_quality_getlistfromlist"><option value="w300">300</option><option value="w500" selected="selected">500</option><option value="w780">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Язык</label></div><div class="controls"><input type="text" class="span2" value="en-US" name="lang_getlistfromlist" id="lang_getlistfromlist"></div></div>';
		$html .= '<button type="button" id="jform_getlist" name="jform_getlist" class="btn btn-primary" onclick="startAction(\'getListFromList\');" >Обработать</button>';
		$html .= '</div><div class="span6"><div class="Getlistfromlist"></div></div></div>';
		
		return $html;
	}
}