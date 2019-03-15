<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$name = $params->get('name');
$readmore_text = $params->get('readmore_text');
$cat_id = $params->get('catid');?>

<p class="module_title">
	Новости
	<a class="cat_url" href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat_id[0]));?>">все новости</a>
</p>

<div class="newsflash<?php echo $moduleclass_sfx; ?>">
	<?php if(isset($name) && !empty($name)){?>
		<p class="module_title"><?php echo $name;?></p>
	<?php } ?>
	<?php foreach ($list as $item) : ?>
		<?php require JModuleHelper::getLayoutPath('mod_articles_news', '_item'); ?>
	<?php endforeach; ?>
	
	
</div>