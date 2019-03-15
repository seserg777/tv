<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldGetmoviesfromlist extends JFormField {
 
	protected $type = 'Getmoviesfromlist';
	
	public function getInput() {

		$document = JFactory::getDocument();
		$document->addScript('/plugins/system/tvparse/assets/js/xlsx.full.min.js');
		$document->addScript('/plugins/system/tvparse/assets/js/script.js');
		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<h3>Список для загрузки</h3>';
		$html .= '<div class="control-group"><div class="control-label"><label>Список themoviedbid</label></div><div class="controls"><textarea class="span2" name="getmoviesfromlist_ids" id="getmoviesfromlist_ids"></textarea></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество изображения постера</label></div><div class="controls"><select class="span2" value="5" name="getmoviesfromlist_poster_quality" id="getmoviesfromlist_poster_quality"><option value="w300">300</option><option value="w500">500</option><option value="w780" selected="selected">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество доп.изображений</label></div><div class="controls"><select class="span2" value="5" name="getmoviesfromlist_screeencaps_quality" id="getmoviesfromlist_screeencaps_quality"><option value="w300">300</option><option value="w500">500</option><option value="w780" selected="selected">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Язык</label></div><div class="controls"><input type="text" class="span2" value="en-US" name="getmoviesfromlist_lang" id="getmoviesfromlist_lang"></div></div>';
		$html .= '<button type="button" id="jform_getlist" name="jform_getlist" class="btn btn-primary" onclick="startAction(\'getMoviesFromList\');" >Обработать</button>';
		$html .= '</div><div class="span6"><div class="Getmoviesfromlist"></div></div></div>';
		
		return $html;
	}
}