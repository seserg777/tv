<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldGetnewseasons extends JFormField {
 
	protected $type = 'Getnewseasons';
	
	public function getInput() {
		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<h3>Список для загрузки</h3>';
		$html .= '<div class="control-group"><div class="control-label"><label>Список themoviedbid</label></div><div class="controls"><textarea class="span2" name="idsl" id="idsl"></textarea></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Кол-во screencaps</label></div><div class="controls"><input type="number" class="span2" value="5" name="screencaps_count_gns" id="screencaps_count_gns"></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество изображения постера сезона</label></div><div class="controls"><select class="span2" value="5" name="season_poster_quality_gns" id="season_poster_quality_gns"><option value="w300">300</option><option value="w500">500</option><option value="w780" selected="selected">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Качество доп.изображений сезона</label></div><div class="controls"><select class="span2" value="5" name="season_screeencaps_quality_gns" id="season_screeencaps_quality_gns"><option value="w300">300</option><option value="w500" selected="selected">500</option><option value="w780">780</option><option value="w1280">1280</option><option value="original">original</option></select></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Язык</label></div><div class="controls"><input type="text" class="span2" value="en-US" name="lang_gns" id="lang_gns"></div></div>';
		$html .= '<button type="button" id="jform_getnewseasonsfromlist" name="jform_getnewseasonsfromlist" class="btn btn-info" onclick="startAction(\'getNewSeasonsFromList\');" >Получить список из списка</button>';
		$html .= '<br/><br/><br/>';
		$html .= '<button type="button" id="jform_getnewallseasons" name="jform_getnewallseasons" class="btn btn-primary" onclick="startAction(\'getNewAllSeasons\');" >Получить список из текущих фильмов</button>';
		$html .= '</div><div class="span6"><div class="getnewseasons-info"></div></div></div>';
		
		return $html;
	}
}