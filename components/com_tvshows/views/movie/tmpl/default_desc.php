<div class="film-info" id="film-info">
	<div class="main-title">
	
		<?php if(isset($this->h2_pattern) && !empty($this->h2_pattern)){?>
			<h2>
				<?php echo TvshowsHelperMovie::replacer($this->item, $this->h2_pattern);?>
			</h2>
		<?php } else {?>
			<h2><?php echo $this->item->title; ?></h2>
		<?php } ?>
		
		<?php if(isset($this->description_pattern_2) && !empty($this->description_pattern_2)){?>
			<div class="subtitle">
				<?php echo TvshowsHelperMovie::replacer($this->item, $this->description_pattern_2);?>
			</div>
		<?php } else {?>
			<div class="subtitle"><?php echo $this->item->description;?></div>
		<?php } ?>
	</div>

	<?php if (isset($expected) && ($expected->toUnix() > $today->toUnix())){?>
		<div class="next-episodes">
			<div class="countdown"></div>
			<div>
					<div class="next-episodes-item">
						<div class="download-list">
							<div class="download-table">
								<div class="download-row title-row">
									<div class="download-cell">
										<h4 class="h5"><?php echo $this->item->next_episode_name;?></h4>
									</div>
									<div class="download-cell cell3">
										<?php echo JText::_('COM_TVSHOWS_VIEW_RELEASE_DATE');?> 
										<span><?php $date = new JDate($expected);echo $date->format('d/m/Y');?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	<?php } ?>
</div>