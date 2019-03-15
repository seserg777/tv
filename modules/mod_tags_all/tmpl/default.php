<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;?>

<p class="desc"><?php echo JText::_('MOD_TAGS_ALL_SELECT_THE_SERIES_CATEGORY');?></p>
<p class="bage"><span><?php echo count($list);?> <?php echo JText::_('MOD_TAGS_ALL_CATEGORIES');?></span></p>

<div class="ps_tagsall<?php echo $moduleclass_sfx; ?>">
	<?php if(count($list)){?>
		<ul>
			<?php foreach ($list as $i => $item) : ?>
				<?php if ((!empty($item->access)) && in_array($item->access, $user->getAuthorisedViewLevels())) : ?>
					<li class="cat-list-row<?php echo $i % 2; ?>">
						<a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($item->id . ':' . $item->alias)); ?>">
							<?php echo $item->title; ?>
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php } ?>
</div>