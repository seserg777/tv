<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldGetlist extends JFormField {
 
	protected $type = 'Getlist';
	
	public function getInput() {

		$document = JFactory::getDocument();
		$document->addScript('/plugins/system/tvparse/assets/js/xlsx.full.min.js');
		$document->addScript('/plugins/system/tvparse/assets/js/script.js');
		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<h3>Данные для загрузки</h3>';
		$html .= '<div class="control-group"><div class="control-label"><label>Год выхода сериала</label></div><div class="controls"><input type="text" class="span2" value="2018" name="cp_year" id="cp_year"></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Минимальный рейтинг сериала</label></div><div class="controls"><input type="text" class="span2" value="9" name="cp_vote" id="cp_vote"></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Оригинальный язык сериала</label></div><div class="controls"><input type="text" class="span2" value="en" name="cp_lang" id="cp_lang"></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>На каком языке получить сериалы</label></div><div class="controls"><input type="text" class="span2" value="en-US" name="cp_lang_rec" id="cp_lang_rec"></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Кол-во screencaps</label></div><div class="controls"><input type="number" class="span2" value="5" name="screencaps_count" id="screencaps_count"></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество изображения постера сезона</label></div><div class="controls"><select class="span2" value="5" name="season_poster_quality_getlist" id="season_poster_quality_getlist"><option value="w300">300</option><option value="w500">500</option><option value="w780" selected="selected">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество доп.изображений фильма</label></div><div class="controls"><select class="span2" value="5" name="film_screeencaps_quality_getlist" id="film_screeencaps_quality_getlist"><option value="w300">300</option><option value="w500">500</option><option value="w780" selected="selected">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество доп.изображений сезона</label></div><div class="controls"><select class="span2" value="5" name="season_screeencaps_quality_getlist" id="season_screeencaps_quality_getlist"><option value="w300">300</option><option value="w500" selected="selected">500</option><option value="w780">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<button type="button" id="jform_getlist" name="jform_getlist" class="btn btn-primary" onclick="startAction(\'getList\');" >Получить список</button>';
		$html .= '</div><div class="span6"><div class="Getlist"></div></div></div>';
		
		return $html;
	}
}