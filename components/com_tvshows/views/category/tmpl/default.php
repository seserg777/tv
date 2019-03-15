<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
$this->subtemplatename = 'items';
echo JLayoutHelper::render('joomla.content.category_default', $this);
