<?php if(
	(isset($this->item->images) && count($this->item->images)) ||
	(isset($this->item->videos) && count($this->item->videos))
) {?>
	<div class="gallery">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#screens" role="tab" data-toggle="tab" aria-expanded="true"><?php echo JText::_('COM_TVSHOWS_VIEW_SCREENCAPS_FIRST_UPPER');?></a></li>
			<li role="presentation"><a href="#trailers" role="tab" data-toggle="tab" aria-expanded="false"><?php echo JText::_('COM_TVSHOWS_VIEW_TRAILERS_FIRST_UPPER');?></a></li>
		</ul>
		
		<div class="tab-content">
			<div id="trailers" role="tabpanel" class="tab-pane">
				<?php if(isset($this->item->videos)  && count($this->item->videos)){?>
					
					<div class="navigation">
						<div class="swiper-button-prev"></div>
						<div class="swiper-button-next"></div>
						<div class="swiper-pagination"></div>
					</div>	    		

					<div class="swiper-container">
						<div class="swiper-wrapper">
							<?php //echo '<pre>';var_dump($this->item->videos);?>
							<?php foreach($this->item->videos as $video){
								if($video->site == 'YouTube'){?>
									<div class="swiper-slide">
										<iframe src="https://www.youtube-nocookie.com/embed/<?php echo $video->key;?>?rel=0&amp;showinfo=0" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
									</div>
								<?php } 
							}?>
						</div>
					</div>
				<?php } ?>
			</div>
			
			<div id="screens" role="tabpanel" class="tab-pane active">
				<div class="navigation">
					<div class="swiper-button-prev"></div>
					<div class="swiper-button-next"></div>
					<div class="swiper-pagination"></div>
				</div>	    		

				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php foreach($this->item->images as $img){?>
							<div class="swiper-slide">
								<img src="<?php echo $img;?>" alt="<?php echo $this->item->title.' movie';?>" />
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>
	<script>
		jQuery(document).ready(function(){
			const gallerySlider = new Swiper ('#screens .swiper-container', {
				direction: 'horizontal',
				loop: false,
				slidesPerView: 3,
				spaceBetween: 25,
				pagination: {el: '#screens .swiper-pagination',},
				navigation: {nextEl: '#screens .swiper-button-next',prevEl: '#screens .swiper-button-prev',},
				breakpoints: {
					320: {
					  slidesPerView: 1,
					  spaceBetween: 0
					},
					480: {
					  slidesPerView: 2,
					  spaceBetween: 20
					},
					640: {
					  slidesPerView: 3,
					  spaceBetween: 25
					},
					980: {
					  slidesPerView: 3,
					  spaceBetween: 25
					},
					1024: {
					  slidesPerView: 3,
					  spaceBetween: 25
					},
					1279: {
					  slidesPerView: 3,
					  spaceBetween: 25
					}
				}
			});
		});
		
		jQuery(window).load(function(){
			const videoSlider = new Swiper ('#trailers .swiper-container', {
				direction: 'horizontal',
				loop: false,
				slidesPerView: 3,
				spaceBetween: 25,
				pagination: {el: '#trailers .swiper-pagination',},
				navigation: {nextEl: '#trailers .swiper-button-next', prevEl: '#trailers .swiper-button-prev',},
				breakpoints: {
					320: {
					  slidesPerView: 1,
					  spaceBetween: 0
					},
					480: {
					  slidesPerView: 2,
					  spaceBetween: 20
					},
					640: {
					  slidesPerView: 2,
					  spaceBetween: 25
					},
					980: {
					  slidesPerView: 3,
					  spaceBetween: 25
					},
					1279: {
					  slidesPerView: 3,
					  spaceBetween: 25
					}
				}
			});
		});
	</script>
<?php } ?>