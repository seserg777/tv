<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldConfig extends JFormField {

	protected $type = 'config';

	public function getInput() {
		echo '<script src="/modules/mod_tvpopular/assets/js/admin.js" ></script>';
	}
}