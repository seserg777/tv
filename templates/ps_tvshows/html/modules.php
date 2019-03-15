<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

function modChrome_no($module, &$params, &$attribs){
	if ($module->content){
		echo $module->content;
	}
}

function modChrome_well($module, &$params, &$attribs){
	if ($module->content){
		echo "<div class=\"well " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle){
			echo "<h3 class=\"page-header\">" . $module->title . "</h3>";
		}
		echo $module->content;
		echo "</div>";
	}
}

function modChrome_hidden($module, &$params, &$attribs) {
	if (!empty ($module->content)) { ?>
		<?php $moduleclass_sfx = $params->get('moduleclass_sfx');
		if(!isset($moduleclass_sfx)){
			$registry = new JRegistry();
			$module_params = $registry->loadString($module->params);
			$moduleclass_sfx = $module_params->get('moduleclass_sfx');
		}?>
		<div class="hide">
			<div id="moduletable<?php echo htmlspecialchars($moduleclass_sfx); ?>">
				<?php if ($module->showtitle) { ?>
					<p class="module_title"><?php echo $module->title; ?></p>
				<?php } ?>
				<?php echo $module->content; ?>
			</div>
		</div>
	<?php } 
}

function modChrome_full($module, &$params, &$attribs) {
	if (!empty ($module->content)) { ?>
		<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>" id="module<?php echo $module->id;?>">
			<div class="wrapper">
				<?php if ($module->showtitle) { ?>
					<p class="module_title"><?php echo $module->title; ?></p>
				<?php } ?>
				<?php echo $module->content; ?>
			</div>
		</div>
		<div class="clear"></div>
	<?php } 
}

function modChrome_tabs($module, $params, $attribs){
	$area = isset($attribs['id']) ? (int) $attribs['id'] :'1';
	$area = 'area-'.$area;

	static $modulecount;
	static $modules;

	if ($modulecount < 1)
	{
		$modulecount = count(JModuleHelper::getModules($module->position));
		$modules = array();
		//var_dump(JModuleHelper::getModules($module->position));
	}
	//exit;
	if ($modulecount == 1)
	{
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->title = $module->title;
		$temp->params = $module->params;
		$temp->id = $module->id;
		$modules[] = $temp;
		// list of moduletitles
		// list of moduletitles
		echo '<div class="ps-tabs"><ul id="'. $area.'" class="horizontal">';

		foreach ($modules as $rendermodule)
		{
			echo '<li>
				<a href="#module_'.$rendermodule->id.'"  data-toggle="tab">'.$rendermodule->title.'</a>
			</li>';
		}
		echo '</ul>';
		$counter = 0;
		// modulecontent
		//echo '<div class="tab-content">';
		foreach ($modules as $rendermodule)
		{
			$counter ++;

			echo '<div class="tab-pane" id="module_'.$rendermodule->id.'">';
			echo $rendermodule->content;
			if ($counter != count($modules))
			{
			//echo '<a href="#" class="unseen" onclick="nexttab(\'module_'. $rendermodule->id.'\');return false;" id="next_'.$rendermodule->id.'">'.JText::_('TPL_BEEZ3_NEXTTAB').'</a>';
			}
			echo '</div>';
		}
		//echo '</div>';
		$modulecount--;
		echo '</div><!--end-->';
	} else {
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->params = $module->params;
		$temp->title = $module->title;
		$temp->id = $module->id;
		$modules[] = $temp;
		$modulecount--;
	}
}

function modChrome_html($module, &$params, &$attribs){
	$moduleTag      = $params->get('module_tag', 'div');
	$headerTag      = htmlspecialchars($params->get('header_tag', 'p'), ENT_COMPAT, 'UTF-8');
	$bootstrapSize  = (int) $params->get('bootstrap_size', 0);
	$moduleClass    = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';

	// Temporarily store header class in variable
	$headerClass    = $params->get('header_class','module_title');
	$headerClass    = ($headerClass) ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	$content = trim($module->content);
	
	if (!empty ($content)) : ?>
		<<?php echo $moduleTag; ?> class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') . $moduleClass; ?>" id="module<?php echo $module->id;?>">
			<?php if ($module->showtitle != 0) : ?>
				<p class="module_title"><span><?php echo $module->title;?></span></p>
			<?php endif; ?>
			<?php echo $content; ?>
		</<?php echo $moduleTag; ?>><!--end module-->
	<?php endif;
}?>