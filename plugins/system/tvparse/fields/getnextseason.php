<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldGetnextseason extends JFormField {
 
	protected $type = 'Getnextseason';
	
	public function getInput() {
		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<button type="button" id="jform_getnextseason" name="jform_getnextseason" class="btn btn-primary" onclick="startAction(\'getNextSeason\');" >Запуск</button>';
		$html .= '</div><div class="span6"><div id="result-1"></div></div></div>';
		
		return $html;
	}
}