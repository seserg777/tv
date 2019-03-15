<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

use Joomla\Registry\Registry;

JLoader::register('TagsHelperRoute', JPATH_BASE . '/components/com_tags/helpers/route.php');

$authorised = JFactory::getUser()->getAuthorisedViewLevels();

$app = jFactory::getApplication();
$jinput = $app->input;
$option = $jinput->get('option', null, 'CMD');
$view = $jinput->get('view', null, 'CMD');?>
<?php if (!empty($displayData)) : ?>
	<ul class="tags inline">
		<li class="title">Genres:</li>
		<?php foreach ($displayData as $i => $tag) : ?>
			<?php if (in_array($tag->access, $authorised)) : ?>
				<?php $tagParams = new Registry($tag->params); ?>
				<?php $link_class = $tagParams->get('tag_link_class', 'label label-info'); ?>
				<li class="tag-<?php echo $tag->tag_id; ?> tag-list<?php echo $i; ?>" itemprop="keywords">
					<?php if($option == 'com_tvshows' && ($view == 'season' || $view == 'film' || $view == 'movie')){?>
						<?php echo $this->escape($tag->title);?>
					<?php } else {?>
						<a href="<?php echo JRoute::_(TagsHelperRoute::getTagRoute($tag->tag_id . '-' . $tag->alias)); ?>" class="<?php echo $link_class; ?>"><?php echo $this->escape($tag->title);?></a>
					<?php } ?>
				</li>
				<?php if(($i+1)<count($displayData)){
					echo '<li>,&nbsp;</li>';
				} ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>