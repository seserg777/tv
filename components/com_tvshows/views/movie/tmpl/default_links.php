<?php if(isset($this->item->links) && !empty($this->item->links)){?>
	<div class="movie-links-title" id="ps-movie-links"><?php echo JText::_('COM_TVSHOWS_VIEW_LINKS_MOVIES');?> <?php echo $this->item->title; ?></div>
	<div class="movie-links"><?php echo $this->item->links;?></div>
<?php } ?>