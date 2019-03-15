<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */
defined("_JEXEC") or die("Restricted access");
$doc = JFactory::getDocument();
$h1_pattern = $this->component_params->get('film_h1_pattern', null);
$h2_pattern = $this->component_params->get('film_h2_pattern', null);
$description_pattern = $this->component_params->get('season_description_pattern', null);
$film_imdb_link = $this->component_params->get('film_imdb_link', null);
$film_imdb_link_nofollow = $this->component_params->get('film_imdb_link_nofollow', null);
$custom_btn_1_anchor = $this->component_params->get('films-custom-btn-1-anchor', null);
$custom_btn_1_link = $this->component_params->get('films-custom-btn-1-link', null);
$film_scrolltodownload_btn = $this->component_params->get('film_scrolltodownload_btn', 1);
$enable_film_custom_btn_1_with_seasons = $this->component_params->get('enable_film_custom_btn_1_with_seasons', 0);
if(isset($this->item->images) && count($this->item->images)) {$doc->addStyleSheet('//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css');}?>

<div class="ps-film">
	<div class="page-header">
		<?php $document = JFactory::getDocument();
		$renderer   = $document->loadRenderer('modules');
		$options    = array('style' => 'xhtml');
		$position   = 'film-page-header';
		echo $renderer->render($position, $options, null);?>
	</div>
	
	<div class="intro">
		<div class="serial-main">
			<div class="card">
				<div class="card-image">
					<?php if(isset($this->item->main_image) && !empty($this->item->main_image)){?>
						<img src="<?php echo $this->item->main_image;?>" alt="<?php echo $this->item->title?> poster" />
					<?php } else {
						if(isset($this->item->images) && count($this->item->images)){?>
							<img src="<?php echo $this->item->images[array_rand($this->item->images)];?>" alt="<?php echo $this->item->title?> <?php echo JText::_('COM_TVSHOWS_VIEW_POSTER');?>" />
						<?php }
					}?>
				</div>

				<div class="card-text">

					<?php if(isset($h1_pattern) && !empty($h1_pattern)){?>
						<h1 class="card-title">
							<?php $h1_pattern = str_replace('{seasons_enumeration}', $this->item->count_seasons, str_replace('{film_name}', $this->item->title, $h1_pattern));
						
							if(isset($this->item->implodetags) && !empty($this->item->implodetags)){
								$h1_pattern = str_replace('{film_genres}', $this->item->implodetags, $h1_pattern);
							}
							$h1_pattern = str_replace('{film_creators}', $this->item->creators, $h1_pattern);
							$h1_pattern = str_replace('{film_channel}', $this->item->channel, $h1_pattern);
						
							echo $h1_pattern;?>
						</h1>
					<?php } else {?>
						<h1 class="card-title"><?php echo $this->item->title; ?></h1>
					<?php } ?>
					
					<?php if(count($this->item->seasons)){?>
						<div class="table-of-contents">
							<span class="title"><strong><?php echo JText::sprintf('COM_TVSHOWS_TABLE_OF_CONTENTS', $this->item->title);?></strong></span>
							<ul>
								<?php foreach($this->item->seasons as $k => $v){?>
									<li><a href="#season-<?php echo $v->season_number;?>"><?php echo JText::sprintf('COM_TVSHOWS_TABLE_OF_CONTENTS_SEASON_NUMBER', $v->season_number);?></a></li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
					
					<?php if(isset($description_pattern) && !empty($description_pattern)){?>
						<p>
							<?php $description_pattern = str_replace('{seasons_enumeration}', $this->item->count_seasons, str_replace('{film_name}', $this->item->title, $description_pattern)); 
							
							if(isset($this->item->implodetags) && !empty($this->item->implodetags)){
								$description_pattern = str_replace('{film_genres}', $this->item->implodetags, $description_pattern);
							}
							$description_pattern = str_replace('{film_creators}', $this->item->creators, $description_pattern);
							$description_pattern = str_replace('{film_channel}', $this->item->channel, $description_pattern);
							
							echo $description_pattern;?>
						</p>
					<?php } ?>
					
				</div>
				
				<?php if(
					(isset($this->item->rate_imdb) && !empty($this->item->rate_imdb)) ||
					(isset($this->item->votes) && !empty($this->item->votes))
				){?>
					<div class="imdb">
						<?php if(isset($film_imdb_link) && !empty($film_imdb_link)){?>
							<a href="https://www.imdb.com/title/<?php echo $this->item->imdbid;?>" target="_blank" <?php if($film_imdb_link_nofollow){?>rel="nofollow"<?php } ?>><i></i></a>
						<?php } else {?>
							<i></i>
						<?php } ?>
						
						<span class="imdbRating">
							<?php if(isset($this->item->rate_imdb) && !empty($this->item->rate_imdb)){?>
								<span class="rating">
									<?php echo $this->item->rate_imdb;?>
									<span class="ofTen">/10</span>
								</span>
							<?php } ?>

							<?php if(isset($this->item->votes) && !empty($this->item->votes)){?>
								<span class="votes"><?php echo $this->item->votes;?> <?php echo JText::_('COM_TVSHOWS_VOTES');?></span>
							<?php } ?>
						</span>
					</div>
				<?php } ?>

				<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
				<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>

				<?php if(isset($this->item->seasons) && !empty($this->item->seasons) && $film_scrolltodownload_btn){?>
					<button data-element="#seasons" class="scroll-to-download" onclick="jQuery('body, html').animate({scrollTop: jQuery(jQuery(this).data('element')).offset().top - 80})"><i></i><?php echo JText::_('COM_TVSHOWS_SCROLL_TO_DOWNLOAD');?></button>
				<?php } ?>
				
				<?php if(isset($custom_btn_1_anchor) && !empty($custom_btn_1_anchor) && isset($custom_btn_1_link) && !empty($custom_btn_1_link)){
					if($enable_film_custom_btn_1_with_seasons == 1 && !count($this->item->seasons)){
						echo TvshowsHelperFilm::customBtn($this->item, base64_encode($custom_btn_1_link), $custom_btn_1_anchor, array('class' => 'custom-btn-1'));
					} 
					if($enable_film_custom_btn_1_with_seasons == 0){
						echo TvshowsHelperFilm::customBtn($this->item, base64_encode($custom_btn_1_link), $custom_btn_1_anchor, array('class' => 'custom-btn-1'));
					}
				}?>
			</div>
		</div>				
		
		<?php if(isset($this->item->images) && count($this->item->images)) { ?>
			<div class="gallery">
				<h3><?php echo $this->item->title;?>  <?php echo JText::_('COM_TVSHOWS_SCREENCUPS');?></h3>
				
				<div class="navigation">
					<div class="swiper-button-prev"></div>
					<div class="swiper-button-next"></div>
					<div class="swiper-pagination"></div>
				</div>	    		

				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php foreach($this->item->images as $img){?>
							<div class="swiper-slide">
								<img src="<?php echo $img;?>" alt="<?php echo $this->item->title;?>  <?php echo JText::_('COM_TVSHOWS_SCREENCUPS');?>" />
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	
	<div class="text-block">
		<?php if(isset($this->item->description) && !empty($this->item->description)){?>
			<span class="description"><?php echo $this->item->description;?></span>
		<?php } ?>
	</div>
	
	<div class="inner">
		<?php if(isset($this->item->seasons) && !empty($this->item->seasons)){?>
			<div class="seasons-header">
				
				<?php if(isset($h2_pattern) && !empty($h2_pattern)){?>
					<h2>
						<?php $h2_pattern = str_replace('{seasons_enumeration}', $this->item->count_seasons, str_replace('{film_name}', $this->item->title, $h2_pattern)); 
						
						if(isset($this->item->implodetags) && !empty($this->item->implodetags)){
							$h2_pattern = str_replace('{film_genres}', $this->item->implodetags, $h2_pattern);
						}
						$h2_pattern = str_replace('{film_creators}', $this->item->creators, $h2_pattern);
						$h2_pattern = str_replace('{film_channel}', $this->item->channel, $h2_pattern);
						echo $h2_pattern;?>
					</h2>
				<?php } else {?>
					<h2><?php echo $this->item->title; ?></h1>
				<?php } ?>
				
				<?php $published_seasons_count = 0;
				foreach($this->item->seasons as $s){
					if($s->published)
					$published_seasons_count++;
				}?>
				<span class="counter"><?php echo $published_seasons_count;?> <?php echo JText::_('COM_TVSHOWS_VIEW_SEASONS');?></span>
			</div>
			
			<div class="film-seasons">
				<ul class="main-list" id="seasons">
					<?php foreach($this->item->seasons as $k => $v){
						if($v->published){?>
							<li class="main-list-item" id="season-<?php echo $v->season_number;?>">
								<div class="card">
									<a href="<?php echo TvshowsHelperRoute::getSeasonRoute($v->id, $v->alias, $this->item->alias); ?>">
										<?php if(isset($v->main_image) && !empty($v->main_image)){
											$season_img = $v->main_image;
										} else {
											if(isset($this->item->main_image) && !empty($this->item->main_image)){
												$season_img = $this->item->main_image;
											}
										}?>
										
										<div class="card-image">
											<?php if(isset($season_img) && !empty($season_img)){?>
												<div class="img" style="background-image:url(<?php echo $season_img;?>);"></div>
											<?php } else {?>
												<div class="img" style="background-image:url('data:image/jpeg;base64,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Ai0AAAAQAAAAAQAAAAAAEnByaW50T3V0cHV0T3B0aW9ucwAAABcAAAAAQ3B0bmJvb2wAAAAAAENsYnJib29sAAAAAABSZ3NNYm9vbAAAAAAAQ3JuQ2Jvb2wAAAAAAENudENib29sAAAAAABMYmxzYm9vbAAAAAAATmd0dmJvb2wAAAAAAEVtbERib29sAAAAAABJbnRyYm9vbAAAAAAAQmNrZ09iamMAAAABAAAAAAAAUkdCQwAAAAMAAAAAUmQgIGRvdWJAb+AAAAAAAAAAAABHcm4gZG91YkBv4AAAAAAAAAAAAEJsICBkb3ViQG/gAAAAAAAAAAAAQnJkVFVudEYjUmx0AAAAAAAAAAAAAAAAQmxkIFVudEYjUmx0AAAAAAAAAAAAAAAAUnNsdFVudEYjUHhsQFIAAAAAAAAAAAAKdmVjdG9yRGF0YWJvb2wBAAAAAFBnUHNlbnVtAAAAAFBnUHMAAAAAUGdQQwAAAABMZWZ0VW50RiNSbHQAAAAAAAAAAAAAAABUb3AgVW50RiNSbHQAAAAAAAAAAAAAAABTY2wgVW50RiNQcmNAWQAAAAAAAAAAABBjcm9wV2hlblByaW50aW5nYm9vbAAAAAAOY3JvcFJlY3RCb3R0b21sb25nAAAAAAAAAAxjcm9wUmVjdExlZnRsb25nAAAAAAAAAA1jcm9wUmVjdFJpZ2h0bG9uZwAAAAAAAAALY3JvcFJlY3RUb3Bsb25nAAAAAAA4QklNA+0AAAAAABAASAAAAAEAAQBIAAAAAQABOEJJTQQmAAAAAAAOAAAAAAAAAAAAAD+AAAA4QklNBA0AAAAAAAQAAABaOEJJTQQZAAAAAAAEAAAAHjhCSU0D8wAAAAAACQAAAAAAAAAAAQA4QklNJxAAAAAAAAoAAQAAAAAAAAABOEJJTQP1AAAAAABIAC9mZgABAGxmZgAGAAAAAAABAC9mZgABAKGZmgAGAAAAAAABADIAAAABAFoAAAAGAAAAAAABADUAAAABAC0AAAAGAAAAAAABOEJJTQP4AAAAAABwAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAADhCSU0EAAAAAAAAAgAHOEJJTQQCAAAAAAAQAAAAAAAAAAAAAAAAAAAAADhCSU0EMAAAAAAACAEBAQEBAQEBOEJJTQQtAAAAAAACAAA4QklNBAgAAAAAABUAAAABAAACQAAAAkAAAAABAAAWmwEAOEJJTQQeAAAAAAAEAAAAADhCSU0EGgAAAAADSwAAAAYAAAAAAAAAAAAAAWoAAAD7AAAACwQRBDUENwAgBDgEPAQ1BD0EOAAtADEAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAPsAAAFqAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAEAAAAAAABudWxsAAAAAgAAAAZib3VuZHNPYmpjAAAAAQAAAAAAAFJjdDEAAAAEAAAAAFRvcCBsb25nAAAAAAAAAABMZWZ0bG9uZwAAAAAAAAAAQnRvbWxvbmcAAAFqAAAAAFJnaHRsb25nAAAA+wAAAAZzbGljZXNWbExzAAAAAU9iamMAAAABAAAAAAAFc2xpY2UAAAASAAAAB3NsaWNlSURsb25nAAAAAAAAAAdncm91cElEbG9uZwAAAAAAAAAGb3JpZ2luZW51bQAAAAxFU2xpY2VPcmlnaW4AAAANYXV0b0dlbmVyYXRlZAAAAABUeXBlZW51bQAAAApFU2xpY2VUeXBlAAAAAEltZyAAAAAGYm91bmRzT2JqYwAAAAEAAAAAAABSY3QxAAAABAAAAABUb3AgbG9uZwAAAAAAAAAATGVmdGxvbmcAAAAAAAAAAEJ0b21sb25nAAABagAAAABSZ2h0bG9uZwAAAPsAAAADdXJsVEVYVAAAAAEAAAAAAABudWxsVEVYVAAAAAEAAAAAAABNc2dlVEVYVAAAAAEAAAAAAAZhbHRUYWdURVhUAAAAAQAAAAAADmNlbGxUZXh0SXNIVE1MYm9vbAEAAAAIY2VsbFRleHRURVhUAAAAAQAAAAAACWhvcnpBbGlnbmVudW0AAAAPRVNsaWNlSG9yekFsaWduAAAAB2RlZmF1bHQAAAAJdmVydEFsaWduZW51bQAAAA9FU2xpY2VWZXJ0QWxpZ24AAAAHZGVmYXVsdAAAAAtiZ0NvbG9yVHlwZWVudW0AAAARRVNsaWNlQkdDb2xvclR5cGUAAAAATm9uZQAAAAl0b3BPdXRzZXRsb25nAAAAAAAAAApsZWZ0T3V0c2V0bG9uZwAAAAAAAAAMYm90dG9tT3V0c2V0bG9uZwAAAAAAAAALcmlnaHRPdXRzZXRsb25nAAAAAAA4QklNBCgAAAAAAAwAAAACP/AAAAAAAAA4QklNBBEAAAAAAAEBADhCSU0EFAAAAAAABAAAAAk4QklNBAwAAAAADzoAAAABAAAAbwAAAKAAAAFQAADSAAAADx4AGAAB/9j/7QAMQWRvYmVfQ00AAv/uAA5BZG9iZQBkgAAAAAH/2wCEAAwICAgJCAwJCQwRCwoLERUPDAwPFRgTExUTExgRDAwMDAwMEQwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwBDQsLDQ4NEA4OEBQODg4UFA4ODg4UEQwMDAwMEREMDAwMDAwRDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDP/AABEIAKAAbwMBIgACEQEDEQH/3QAEAAf/xAE/AAABBQEBAQEBAQAAAAAAAAADAAECBAUGBwgJCgsBAAEFAQEBAQEBAAAAAAAAAAEAAgMEBQYHCAkKCxAAAQQBAwIEAgUHBggFAwwzAQACEQMEIRIxBUFRYRMicYEyBhSRobFCIyQVUsFiMzRygtFDByWSU/Dh8WNzNRaisoMmRJNUZEXCo3Q2F9JV4mXys4TD03Xj80YnlKSFtJXE1OT0pbXF1eX1VmZ2hpamtsbW5vY3R1dnd4eXp7fH1+f3EQACAgECBAQDBAUGBwcGBTUBAAIRAyExEgRBUWFxIhMFMoGRFKGxQiPBUtHwMyRi4XKCkkNTFWNzNPElBhaisoMHJjXC0kSTVKMXZEVVNnRl4vKzhMPTdePzRpSkhbSVxNTk9KW1xdXl9VZmdoaWprbG1ub2JzdHV2d3h5ent8f/2gAMAwEAAhEDEQA/AOPz/wBapr6o36drvRzgO2SG7vXj9zqVLPtX/huvqCoq10/IpqtfVlEjCym+jlECSxsh9WWxo+lZhXtZkN/0lfrY/wDh0HIx7sXIsxsgBt1LiywAyJH5zHfn1P8A5yp/+Eq96mUjSSSSUpJJJJSkkkklKSSSSUpJJJJSldpAw8B2Wf6RmB9GID+bUP0edl7T/pP+Tsf+v1D/ALjoOFinLyW0l/o1w5995EiqmsepkZBb+d6VTfYz/C2+nT/hFO/Mx8nObfdQ44TCxjMNroLcav2V4jbf9J6P85d+fk2XZH+ESU//0OBV6z9d6cLucnpzW1XeL8UkVYt/8p2Da5uDb/3Wu6f/AKCxUUbDyn4eSzIawWhstspdo2yt4NWRjPP7mRS99X/gimUhSVjOxW4uRsqebcaxotxbjy+l/wDMvf8A8K2HU5Lf8HlU31KukpSSSSSlJJJJKUkkkkpSSSt9Pope+zJym78PDaLb2Ekeo4nbjYQcPzsy/wBj/wBzE+13/wCBSUzu/U+ntxuMnPay/J8W48i7Bxf/AEIds6lf/I/Zv/CKip333ZN9mTkO333vNlr4iXOO52g+j/JaoJKf/9HgUkklMpvYoObhPwfpZGNvyMHuXNjfn4Q/4yuv7djf8PRk1/zmaqIIIkag8FSrttptZdS813VObZVY3lr2HfXY3+o9qtdRqqLq87GYK8bN3ObU36NVzI+2Ybf3WUvsZdjf90snFSU00kkklKSSSSUpJJJJSvgCSdABqSTw1oV7qP6q1nSmEH7M4vzHN4flkbLWz+fXgV/qNP8Awv2y+v8ApSXTz9jqf1U6WVO9LAHjkwHuyI/d6bS9mT/4ct6f/LVAAAADgcJKXSSSSU//0uBSSSUylK701zLfU6Zc4MqzS30bHmG15TZbiXOd+bVbvfhZP/AZHr/9pq1SSIBBB1B0ISUu5r2OcyxpY9hLXscIc1zTtex7fzXMd7UysZuUMyxl72kZJYG5T5kWWN9jcn971LqBV9p3fzmT6t/+GVdJSkkkklKRMbGuysivGoAN1zgxm4w0E/n2O/Mqrb+kus/wdXvQ0fHyRj0ZDWNPr5DRSLZ0ZS6ftTGfnerk/oqN/wD3F+01f9qElM+oZNN1rKsUk4WKz0cQkQXNkvsynt/NtzbnPyX/AOj314/+AVVJJJSkkkklP//T4FJJJTKdLov1e6p1z7UOmsZa/CrFtlbnbXODi4NZQ3a71LXem76Xpp6/q31e6npluPWy89Z9UYVVb/f+hMX+vvDGU+n+d+ker/1Q+sdHQKusWGx9WZk4rWYDms3j12G11fqaOYxm99f84t/J/wAY3SndQ6J1OnGew47MpnU8VjACw5XovtsxbZZXc/7RT627d+lZ/OendYmkm1PMdb+qHW+iYwy8ptN2Lv8ASffi2eq2uz6PpXyyp9bt3s+h6fqfo/3E/R/qf1jrOCM/CdjNpNxx2i+303OtaAfTY303tdu3ez3q5n9S+rHTfq3mdB+r9uTm/tO6u2+7IYam1Mqc2xlbGFtXqZH6Gtj3sq/SfznqfzdSfpH1qxOk/VevCZi05vUa+onLrryq3OqY3b+jya3s2t9et/8ALSs0pp9P+pn1gz7sqr0a8MYNno5V2XYK622+3bS2xgu9V7/Urc30/wBF+kq/SfpE1f1M+sDupZPTrqa8R+FX62VkZFgZjsqO708j7SA/dVZss27Wf4O31fS9KxaNX1j6T13pWT0r61XXYr7cw59Wbi172lxb6bseyiLrG7GPeyj/AK17/wBD+lus+ufSn9Zfbj5WZ0vFpwqen4d7qmZTbWVGyx9nVMV++5309lHo/p/571LqPW9FK5KcjF+oXX8rKuxKX4Zuo2SPtAO8WM9euyn067NzHVe/3+m/+R6aYfUbrRuyqTdgt+xMqffa7IipovNjah63pbd+6l29r/8Ag/31eZ9Yvq1T9fsPrmHR9l6bj1PZkPrp2epa6u+p2TXiV7n1ts9atn+k/wBIhfVL6wdI6Zi9ax821+P+0bmPx3txxkANa6x/voe19P0XN/nEtVNXG+pHWMq3KrovwXtwvT9a4ZH6L9MC+vZcKi13HvWX1fpHUei5zsDqNXpZDWh4ghzXMdIZbU8fTY7Y9dTT9bOg4VfWg2tvVh1CzDfXj5GMKqrRVsblMfTWwUUOrrZ+ge9n9I/SfpVjfXLq2J1jrhzcK512Iaa66K31eiaWs3fqgZ+e1ry671f+G9P/AASQJtThpJJJyn//1OBSSSUymVVN19tePjt9S+57aqax+c95FdTP7T3Lct6D0sdUwcbHzXXYOe63CGYNnsz6T9n9zWepuwLsizDyKv8AC/Ycv+c/RLJ6fnW9Pyhl0Nab2Me2l7p/Rve01Nya4/w9DXudRu/wv6RHyeudTzcC3A6hfZnMsfXZVbkWPfZS+ve3djOLtv6eu19d29v7ln85WgbUyb0XJ+xUb63s6pn5v2HBxXewTVFebZfvb+Zl3Y+JX+kr/wC1XqfzatX9E6d+2OmUYOU/K6X1LIZijJBYH+oy9mFnbNnqMb/OVZuH6jP6NlY/qIVv1m6jd1IdUeyoZdeO/HocwFgqfYH+tn1NqLP16y3Iycn1H/8Aai/1f8HWmr+s3Vz6JzLXdRdjZNGZjPy3vsdVZQd22t+7+ZyW+zIr/qWfziXqUi6n06nDoZZW57i7MzcUh5EbMQ4wpf7Wt/SP+0v9b/oemjdF6LT1Gqx195osuf8AY+ltBaBbmljsltd29277PtbRjWOZ/wBqOoYigOt12VOqzem4+a05N+WwvtyKix2SazfWz7LdVur/AEFez1VFn1h6xRjU4vT8m3p2PQ1wFeLY9ge573XPvvdu3W3e/wBL3/4GqtiWtKbnRfq+zqPRn9QZjZWbkNzPsv2fHvpxwxvpNv8AVtflY+V7vUc6v/BoXVui4uB+1RTdbb+zs6jEr9RoYS25l9tovZt991D6PR9al32e7+er/RW1JXfWKvJryaczpWJkUZWU7OdVvyK2svextNz6zRex+y3Z6vpvf7PUQx14vGS3MwMbLpynY7vQ3XUsq+yVvxMRlH2a5lvp149np/pbbP30tVNnpH1eb1Do7s9mJl594zHYppxb6aA1gqqyGWPdk4+Vve99r2fmLFvYGX21tY6oMe5vp2OD3t2naWWW1tqZY9rh9NlVa0GdYxfsj8G7pONfhnIdlVUvtyR6T311Y72strvbbYzZQz+ees6x1brHuqrbRW4yylpc5rB2ra+0vtc1v/COSF9VMUkkkVP/1eBSSSUylJEgAk6AakpK701rKvU6nc0Pqwi30a3iW2ZTpdiUub+dVVsfm5P/AAGP6H/amtJSLNxRh2Moe4nJDA7KZECux/vbjfvepTQavtO7+byfVo/wKrp3Oe9zn2OL3vJc97jLnOcdz3vd+c57vcmSUpJJJJSkfHxhkUZDmOPr47RcKo0fS2ftTmfnerjfor9n/cX7Tb/gEBExsm7FyK8nHIF1Lg9m4S0kfmWN/Pqsb+jur/wlXsSUjSVrqGNTTay3FBGFlM9bFBMlrZLLMV7vzrcK5r8Z/wDpP0WR/h1VSUpJJJJT/9bgUkklMplXVbdaymlhsutc2uqtvLnvOyutv9d7tqtdRtqDq8HGeLMbCDmttb9G258fbMxv7zLn1spxv+6WNiqWKThYT876ORk78fB7FrY9PPzR/Urs+w43/D35Nn85hKiAAIGgHASUpJJJJSkkkklKSSSSU3unj7XU/pR1sud6uAfDJADDjyfzepUsbjf+HKun/wAtUAQQCOCn+BIPII0II4LSr3Uf1prOqsAnJcWZjRwzLA32uj8yvPr/AF2r/hfttDP6MkpopJJJKf/X4FGw8V2Zksx2vFQdLrLnatrrYDbkZLx+5j0sfb/L/m0FXrP1Ppwp4yeotbbf4sxQRbiUfyXZ1rWZ1v8A3Wp6f/prFMpDnZTcrI31MNWPW0VYtJ5rpZ/Msd/wrpddku/wmVdfaq6SSSlJJJJKUkkkkpSSSSSlK10++llj8bKdsw8xoqvfBPpkHfjZoaPzsO/9I/8AfxftVH+HVVJJTO+i7Gvsxshuy+h5rtZMw5p2u9w+k39x356gr136509uTzk4DWUZPi/HkU4OT/6DO2dNv/4P9m/8IqKSn//Q4vp+PTba+7KBdhYjfWygDBeJDKcRjh9GzNvczH/4Ov1sj/tOg5GRdlZFmTkEOuucX2ECBJ/NY38ytn83Uz/B1exWc+MWmvpbfp0u9bOI75Jbt9GR9JnTaX/Zf/Db+of6RUlMpSSSSSlJJJJKUkkkkpSSSSSlJJJJKT4WUcTJbfs9WuHMuoJgW1WD08jHc7831ane1/8AgrfTu/wanfh4+NnNouvcMF5ZYzMa2XOxrPdXlNr/ANK2r+dq/Mya7sf/AAaqq7QRmYDsRx/WMPffiE/nVH9Jn4m4/uf8o4//ABef+fkpKf/ZOEJJTQQhAAAAAABTAAAAAQEAAAAPAEEAZABvAGIAZQAgAFAAaABvAHQAbwBzAGgAbwBwAAAAEgBBAGQAbwBiAGUAIABQAGgAbwB0AG8AcwBoAG8AcAAgAEMAQwAAAAEAOEJJTQQGAAAAAAAHAAMBAQABAQD/4Q5uaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzE0MiA3OS4xNjA5MjQsIDIwMTcvMDcvMTMtMDE6MDY6MzkgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAxOC0wOS0yN1QxNzo0NDowOSswMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDphNDI5NjAyZS1iZmJhLWZmNGMtOGU3Mi1iOTliOTg5YTBlOTAiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDplZWQ4YjJhMS1kNTRiLTc1NGUtYjlkYy05MTFiMGQ4NmQxMTUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoyNDk4MzE4Mi1iM2RlLWVkNDAtODhhNy02MjExZjdjNWY2MmIiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIGRjOmZvcm1hdD0iaW1hZ2UvanBlZyI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MjQ5ODMxODItYjNkZS1lZDQwLTg4YTctNjIxMWY3YzVmNjJiIiBzdEV2dDp3aGVuPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDphNDI5NjAyZS1iZmJhLWZmNGMtOGU3Mi1iOTliOTg5YTBlOTAiIHN0RXZ0OndoZW49IjIwMTgtMDktMjdUMTc6NDQ6MDkrMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDxwaG90b3Nob3A6VGV4dExheWVycz4gPHJkZjpCYWc+IDxyZGY6bGkgcGhvdG9zaG9wOkxheWVyTmFtZT0ibm8gcG9zdGVyIGF2YWlsYWJsZSIgcGhvdG9zaG9wOkxheWVyVGV4dD0ibm8gcG9zdGVyIGF2YWlsYWJsZSIvPiA8L3JkZjpCYWc+IDwvcGhvdG9zaG9wOlRleHRMYXllcnM+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDw/eHBhY2tldCBlbmQ9InciPz7/7gAhQWRvYmUAZAAAAAABAwAQAwIDBgAAAAAAAAAAAAAAAP/bAIQACgcHBwgHCggICg8KCAoPEg0KCg0SFBAQEhAQFBEMDAwMDAwRDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAELDAwVExUiGBgiFA4ODhQUDg4ODhQRDAwMDAwREQwMDAwMDBEMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwM/8IAEQgBagD7AwERAAIRAQMRAf/EAM8AAQADAQEBAQEAAAAAAAAAAAAEBQYDAgcBCAEBAQEBAAAAAAAAAAAAAAAAAAECAxAAAAUC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');"></div>
											<?php } ?>

											<?php if(isset($v->bage) && !empty($v->bage)){?>
												<span class="bage"><?php echo $v->bage;?></span>
											<?php } ?>

											<div class="download-icon"><span></span></div>

											<div class="card-text">
												<div class="card-title"><?php echo $this->item->title.' '.$v->title;?></div>
											</div>
										</div>
									</a>

									<div class="card-capt"><?php echo $v->episode_count;?> <?php echo JText::_('COM_TVSHOWS_VIEW_EPISODES');?></div>
									
									<a class="go-to-season" href="<?php echo TvshowsHelperRoute::getSeasonRoute(null, $v->alias, $this->item->alias); ?>">
										<?php echo JText::_('COM_TVSHOWS_VIEW_DOWNLOAD');?>
									</a>
								</div>
							</li>
						<?php }
					} ?>
				</ul>
			</div>
		<?php } ?>
	</div>

	<?php if(isset($this->item->images) && count($this->item->images)) {?>
		<script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>
		<script>
  			var mySwiper = new Swiper ('.gallery .swiper-container', {
			    direction: 'horizontal',
			    loop: false,
			    slidesPerView: 5,
  				spaceBetween: 25,
			    pagination: {el: '.gallery .swiper-pagination',},
			    navigation: {
			      nextEl: '.gallery .swiper-button-next',
			      prevEl: '.gallery .swiper-button-prev',
			    },
			    breakpoints: {
				    320: {
				      slidesPerView: 2,
				      spaceBetween: 30
				    },
				    480: {
				      slidesPerView: 2,
				      spaceBetween: 30
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
  		</script>
	<?php } ?>
	
	<script>
		jQuery(document).ready(function(){
			jQuery('.ps-film .intro .custom-btn-1').on('click',function(e){
				e.preventDefault();
				let href = jQuery(this).attr('href');
				if (href[0] == '/') { 
					href = atob(href.substring(1));
				}
				window.open(href);
			});
		});
	</script>
</div>