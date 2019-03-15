<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldEpisodes extends JFormField {
 
	protected $type = 'episodes';
	
	public function getInput() {		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<div class="control-group"><div class="control-label"><label>Язык</label></div><div class="controls"><input type="text" class="span2" value="en-US" name="lang_episodes" id="lang_episodes"></div></div><button type="button" id="jform_episodes" name="jform_episodes" class="btn btn-primary" onclick="startAction(\'getAllSeasonsEpisodes\');">Обработать</button>';
		$html .= '</div><div class="span6"><div id="episodes-result"></div></div></div>';
		
		return $html;
	}
}