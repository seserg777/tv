<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
//echo '<pre>';var_dump($list);?>

<div class="ps_tvshows_search<?php echo $moduleclass_sfx; ?>" id="m<?php echo $module->id;?>">  
	<div class="main-list">
		<?php if(count($list)){?>
			<?php foreach($list as $k => $v){?>
				<div class="main-list-item <?php if(!isset($v->episode_count)){?> movie<?php } ?>">
					<div class="card">
						<a href="<?php echo TvshowsHelperRoute::getSeasonRoute(null, $v->alias, $v->film_alias); ?>">
							<div class="card-image">
								<?php $image = false;
								if(isset($v->image) && !empty($v->image)){
								  $image = $v->image;
								} else {
								  if(isset($v->film_images) && !empty($v->film_images) && count($v->film_images)){
									$image = array_rand($v->film_images, 2);
								  }
								} 
								if($image){?>
								  <div class="img" style="background-image:url(<?php echo $image;?>);"></div>
								<?php } ?>
						
								<?php if(isset($v->bage) && !empty($v->bage)){?>
								  <span class="bage"><?php echo $v->bage;?></span>
								<?php } ?>

								<div class="download-icon"><span></span></div>
								
								<?php if(
									isset($v->film_title) && !empty($v->film_title) &&
									isset($v->title) && !empty($v->title)
								){?>
									<div class="card-text"><div class="card-title"><?php echo $v->film_title.' '.$v->title;?></div></div>
								<?php } ?>
							</div> 
						</a>
						
						<?php if(isset($v->episode_count) && !empty($v->episode_count)){?>
							<div class="card-capt"><?php echo $v->episode_count;?> <?php echo JText::_('MOD_TV_SEARCH_EPISODES');?></div>
						<?php } ?>

						<a class="go-to-season" href="<?php echo TvshowsHelperRoute::getSeasonRoute(null, $v->alias, $v->film_alias); ?>">
							<?php echo JText::_('MOD_TV_SEARCH_DOWNLOAD');?>
						</a>
					</div>  
				</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>