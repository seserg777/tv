<div class="season-table">
	<?php if(isset($this->item->title) && !empty($this->item->title)){?>
		<div class="season-table-row">
			<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_ORIGINAL_TITLE');?></span>
			<?php if(isset($this->movie_imdb_link) && !empty($this->movie_imdb_link) && isset($this->item->imdbid) && !empty($this->item->imdbid)){?>
				<a href="https://www.imdb.com/title/<?php echo $this->item->imdbid;?>" target="_blank" <?php if($this->movie_imdb_link_nofollow){?>rel="nofollow"<?php } ?>><?php echo $this->item->title;?></a>
			<?php } else {
				echo $this->item->title;
			}?>
		</div>
	<?php } ?>

	<div class="season-table-row">
		<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
		<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
	</div>

	<?php if(isset($this->item->production_companies) && !empty($this->item->production_companies)){?>
		<div class="season-table-row">
			<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_PRODUCTION_COMPANIES');?></span>
			<?php echo $this->item->production_companies;?>
		</div>
	<?php } ?>

	<?php if(isset($this->item->producers) && !empty($this->item->producers)){?>
		<div class="season-table-row">
			<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_PRODUCERS');?></span>
			<?php echo $this->item->producers;?>
		</div>
	<?php } ?>

	<?php if(isset($this->item->budget) && !empty($this->item->budget)){?>
		<div class="season-table-row">
			<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_BUDGET');?></span>
			<?php echo $this->item->budget;?>
		</div>
	<?php } ?>
	
	<?php if(isset($this->item->language) && !empty($this->item->language)){?>
		<div class="season-table-row">
			<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_LANGUAGE');?></span>
			<?php switch($this->item->language){
				case 'en':
					echo 'English';
					break;
			}?>
		</div>
	<?php } ?>
	
	<?php if(isset($this->item->language) && !empty($this->item->language)){?>
		<div class="season-table-row">
			<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_DESCRIPTION');?></span>
			<?php echo $this->item->description;?>
		</div>
	<?php } ?>
</div>