<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('behavior.core');
JHtml::_('formbehavior.chosen', 'select');

// Get the user object.
$user = JFactory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
$items = $this->items;
$n = count($this->items);
JFactory::getDocument()->addScriptDeclaration("
		var resetFilter = function() {
		document.getElementById('filter-search').value = '';
	}
");?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') || $this->params->get('show_pagination_limit')) { ?>
		<fieldset class="filters btn-toolbar">
			<?php if ($this->params->get('filter_field')) { ?>
				<div class="btn-group">
					<label class="filter-search-lbl element-invisible" for="filter-search">
						<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?>
					</label>
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>" />
					<button type="button" name="filter-search-button" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" onclick="document.adminForm.submit();" class="btn">
						<span class="icon-search"></span>
					</button>
					<button type="reset" name="filter-clear-button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" class="btn" onclick="resetFilter(); document.adminForm.submit();">
						<span class="icon-remove"></span>
					</button>
				</div>
			<?php } ?>
			
			<?php if ($this->params->get('show_pagination_limit')) { ?>
				<div class="btn-group pull-right">
					<label for="limit" class="element-invisible">
						<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php } ?>

			<input type="hidden" name="filter_order" value="" />
			<input type="hidden" name="filter_order_Dir" value="" />
			<input type="hidden" name="limitstart" value="" />
			<input type="hidden" name="task" value="" />
			<div class="clearfix"></div>
		</fieldset>
	<?php } ?>

	<?php if ($this->items == false || $n == 0) { ?>
		<p> <?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php } else { ?>

		<div class="category list-striped main-list">
			<?php foreach ($items as $i => $item) { 
					//echo '<pre>';var_dump($item);?>
					<div class="main-list-item <?php if ($item->core_state == 0) {echo 'system-unpublished';}?> <?php echo $item->type_alias;?>">
						<div class="card">
							<?php if ($item->type_alias == 'com_tvshows.film'){?>
								<a href="<?php echo TvshowsHelperRoute::getFilmRoute($item->content_item_id, $item->core_alias, $item->core_catid);?>">
									<div class="card-image">
										<?php if(isset($item->core_images) && !empty($item->core_images)){
											$images = json_decode($item->core_images);
											//echo '<pre>';var_dump(count($images));
											if(count($images) > 1){?>
												<div class="img" style="background-image:url(<?php echo $images[array_rand($images)];?>);"></div>
											<?php } 
										}?>
										
										<div class="download-icon"><span></span></div>
										
										<div class="card-text">
											<div class="card-title">
												<?php echo $this->escape($item->core_title); ?>
											</div>
										</div>
									</div>
								</a>
								
								<div class="card-capt text-center"></div>
								
								<a class="main-btn" href="<?php echo TvshowsHelperRoute::getFilmRoute($item->content_item_id, $item->core_alias, $item->core_catid);?>">Download</a>
							<?php } else {
								if($item->type_alias == 'com_tvshows.movie'){
									//echo '<pre>';var_dump($item);?>
									<a href="<?php echo TvshowsHelperRoute::getMovieRoute($item->content_item_id, $item->core_alias, $item->core_catid);?>">
									<div class="card-image">
										<?php if(isset($item->core_images) && !empty($item->core_images)){
											$images = json_decode($item->core_images);
											//echo '<pre>';var_dump(count($images));
											if(count($images)){?>
												<div class="img" style="background-image:url(<?php echo $images[array_rand($images)];?>);"></div>
											<?php } 
										}?>
										
										<div class="download-icon"><span></span></div>
										
										<div class="card-text">
											<div class="card-title">
												<?php echo $this->escape($item->core_title); ?>
											</div>
										</div>
									</div>
								</a>
								
								<div class="card-capt text-center"></div>
								
								<a class="main-btn" href="<?php echo TvshowsHelperRoute::getMovieRoute($item->content_item_id, $item->core_alias, $item->core_catid);?>">Download</a>
								<?php } else {?>
							
									<?php if (($item->type_alias == 'com_users.category') || ($item->type_alias == 'com_banners.category')) { ?>
										<h3>
											<?php echo $this->escape($item->core_title); ?>
										</h3>
									<?php } else { ?>
										<h3>
											<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
												<?php echo $this->escape($item->core_title); ?>
											</a>
										</h3>
									<?php } ?>
										
									<?php echo $item->text;?>
									
									<?php echo $item->event->afterDisplayTitle; ?>
									
									<?php $images  = json_decode($item->core_images); ?>
											
									<?php if ($this->params->get('tag_list_show_item_image', 1) == 1 && !empty($images->image_intro)) { ?>
										<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
											<img src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>">
										</a>
									<?php } ?>
										
									<?php if ($this->params->get('tag_list_show_item_description', 1)) { ?>
										<?php echo $item->event->beforeDisplayContent; ?>
										<span class="tag-body">
											<?php echo JHtml::_('string.truncate', $item->core_body, $this->params->get('tag_list_item_maximum_characters')); ?>
										</span>
										<?php echo $item->event->afterDisplayContent; ?>
									<?php } 
								}
							}?>
						</div>
					</div>
			<?php } ?>
		</div>

	<?php } ?>
</form>
