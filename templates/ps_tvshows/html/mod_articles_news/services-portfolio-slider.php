<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;?>
<link href="//cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/css/swiper.min.css" rel="stylesheet" type="text/css">
<script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.0/js/swiper.min.js" type="text/javascript"></script>
<?php $cat_id = $params->get('catid');
$text_before = $params->get('text_before');?>

<?php if(isset($text_before) && !empty($text_before)){echo $text_before;} ?>

<div class="newsflash<?php echo $moduleclass_sfx; ?>" id="m<?php echo $module->id;?>">
	<div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<?php foreach ($list as $item) : ?>
				<div class="swiper-slide">
					<?php $item->images = json_decode($item->images);?>
					<div class="item">
						<?php $item_heading = $params->get('item_heading', 'h4');?>
						<?php if ($params->get('item_title')) { ?>
							<p class="newsflash-title<?php echo $params->get('moduleclass_sfx'); ?>">
								<?php if ($params->get('link_titles') && $item->link != '') { ?>
									<a href="<?php echo $item->link; ?>">
										<?php echo $item->title; ?>
									</a>
								<?php } else { ?>
									<?php echo $item->title; ?>
								<?php } ?>
							</p>
						<?php } ?>
					
						<?php if (!$params->get('intro_only')) { ?>
							<?php echo $item->afterDisplayTitle; ?>
						<?php } ?>
						
						<div class="text">	
							<p class="title"><?php echo $item->title;?></p>
							<?php $introtext = preg_replace('/<h1[^>]*>([\s\S]*?)<\/h1[^>]*>/', '', $item->introtext);
							echo JHtml::_('string.truncate',trim(strip_tags($introtext)), 300);?>
							<p><a class="readmore" href="#" data-href="<?php echo $item->link;?>">Подробнее о проекте</a></p>
							<?php echo $item->beforeDisplayContent; ?>
							<?php echo $item->afterDisplayContent; ?>
						</div>
						
						<?php if(isset($item->images->image_intro) && !empty($item->images->image_intro)){?>
							<div class="img"><img src="<?php echo $item->images->image_intro;?>" alt="<?php echo $item->title; ?>" /></div>
						<?php }?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<div id="modal-portfolio" class="modal hide fade" tabindex="-1" role="dialog">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<div class="modal-body"></div>
</div>

<script>
jQuery(document).ready(function(){
	jQuery('.moduletableblock-16 .readmore').on('click',function(e){
		e.preventDefault();
		var that = jQuery(this);
		var href = that.data('href');
		jQuery("#modal-portfolio").find('.modal-body').load(href+'&tmpl=component');
		setTimeout(function(){
			jQuery("#modal-portfolio").modal('show');
		},300);
	});
	
	window.slider = new Swiper('#m<?php echo $module->id;?> .swiper-container', {
		//autoplay: 4000,
		speed: 400,
		direction: 'horizontal',
		spaceBetween: 0,
		loop:true,
		slidesPerView:1,
		nextButton: '#m<?php echo $module->id;?> .swiper-button-next',
		prevButton: '#m<?php echo $module->id;?> .swiper-button-prev',
	}); 
});
</script>