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
<?php $cat_id = $params->get('catid');?>

<div class="newsflash<?php echo $moduleclass_sfx; ?>" id="m<?php echo $module->id;?>">
	<div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
	<div class="swiper-container">
		<div class="swiper-wrapper">
			<?php foreach ($list as $item) : ?>
				<div class="swiper-slide">
					<?php $item->images = json_decode($item->images);?>
					<div class="item" 
						<?php if(isset($item->images->image_intro) && !empty($item->images->image_intro)){?>
							style="background-image:url('<?php echo $item->images->image_intro;?>');"
						<?php }?>
					>

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
							
							<?php if (!$params->get('intro_only')) { ?>
								<?php echo $item->afterDisplayTitle; ?>
							<?php } ?>

							<p><a class="readmore" href="#" data-href="<?php echo $item->link;?>">Описание проекта</a></p>

							<!--<p><a class="cat_url" href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat_id[0]));?>">Все проекты</a></p>-->
							<p><a class="cat_url" href="/obekty">Все проекты</a></p>

							<?php echo $item->beforeDisplayContent; ?>

							<?php echo $item->afterDisplayContent; ?>
						<?php } ?>
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
	jQuery('.moduletableblock-8 .readmore').on('click',function(e){
		e.preventDefault();
		var that = jQuery(this);
		var href = that.data('href');
		jQuery("#modal-portfolio").find('.modal-body').load(href+'&tmpl=component');
		setTimeout(function(){
			jQuery("#modal-portfolio").modal('show');
		},300);
	});
	
	body_width = jQuery('body').width();
				
	slidesPerView = 3;
	if(body_width <= 949){
		slidesPerView = 3;
	}
	if(body_width <= 586){
		slidesPerView = 1;
	}
	if(body_width <= 516){
		slidesPerView = 1;
	}
	if(body_width <= 303){
		slidesPerView = 1;
	}
				
	window.slider = new Swiper('#m<?php echo $module->id;?> .swiper-container', {
		//autoplay: 4000,
		speed: 400,
		direction: 'horizontal',
		spaceBetween: 0,
		loop:true,
		slidesPerView:slidesPerView,
		nextButton: '#m<?php echo $module->id;?> .swiper-button-next',
		prevButton: '#m<?php echo $module->id;?> .swiper-button-prev',
	}); 
});

jQuery(window).resize(function(){
	window.slider.destroy();
	
	body_width = jQuery('body').width();
	
	slidesPerView = 3;
	if(body_width <= 949){
		slidesPerView = 3;
	}
	if(body_width <= 586){
		slidesPerView = 1;
	}
	if(body_width <= 516){
		slidesPerView = 1;
	}
	if(body_width <= 303){
		slidesPerView = 1;
	}
	
	window.slider = new Swiper('#m<?php echo $module->id;?> .swiper-container', {
		//autoplay: 4000,
		speed: 400,
		direction: 'horizontal',
		spaceBetween: 0,
		loop:true,
		slidesPerView:slidesPerView,
		nextButton: '#m<?php echo $module->id;?> .swiper-button-next',
		prevButton: '#m<?php echo $module->id;?> .swiper-button-prev',
	}); 
});
</script>