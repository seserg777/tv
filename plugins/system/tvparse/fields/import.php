<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldImport extends JFormField {
 
	protected $type = 'import';
	
	public function getInput() {		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<div class="control-group"><div class="control-label"><label>Import TV s</label></div><div class="controls"><input type="file" id="jform_importtvs" name="jform_importtvs" onchange="startAction(\'importTvs\');" /></div></div>';
		$html .= "<br/><br/><br/>";
		$html .= '<div class="control-group"><div class="control-label"><label>Import Seasons</label></div><div class="controls"><input type="file" id="jform_importseasons" name="jform_importseasons" onchange="startAction(\'importSeasons\');" /></div></div>';
		$html .= "<br/><br/><br/>";
		$html .= '<div class="control-group"><div class="control-label"><label>Import Links</label></div><div class="controls"><input type="file" id="jform_importlinks" name="jform_importlinks" onchange="startAction(\'importLinks\');" /></div></div>';
		$html .= "<br/><br/><br/>";
		$html .= '<div class="control-group"><div class="control-label"><label>Import Movies</label></div><div class="controls"><input type="file" id="jform_importmovies" name="jform_importmovies" onchange="startAction(\'importMovies\');" /></div></div>';
		$html .= '</div><div class="span6"><div id="result-2"></div></div></div>';
		
		return $html;
	}
}