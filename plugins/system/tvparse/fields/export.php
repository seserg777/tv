<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldExport extends JFormField {
 
	protected $type = 'export';
	
	public function getInput() {		
		$html = '<div class="row-fluid"><div class="span6">';
		$html .= '<div class="control-group"><div class="control-label"><label>Export links</label></div><div class="controls"><a class="btn btn-primary" href="'.JUri::base().'/index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=exportlinks" target="_blank">Export links</a></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Export links extended</label></div><div class="controls"><a class="btn btn-primary" href="'.JUri::base().'/index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=exportlinksextended" target="_blank">Export links extended</a></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Export links extended</label></div><div class="controls"><a class="btn btn-primary" href="'.JUri::base().'/index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=exporttvshows" target="_blank">Export tvshows</a></div></div>';
		$html .= '<div class="control-group"><div class="control-label"><label>Export movies</label></div><div class="controls"><a class="btn btn-primary" href="'.JUri::base().'/index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=exportmovies" target="_blank">Export movies</a></div></div>';
		$html .= '</div><div class="span6"><div id="result-2"></div></div></div>';
		
		return $html;
	}
}