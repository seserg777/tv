<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
$doc = JFactory::getDocument();

$recaptcha_public_v3 = $this->component_params->get('recaptcha-public-v3', null);

if(isset($recaptcha_public_v3) && !empty(recaptcha_public_v3)){
	$doc->addScript('//www.google.com/recaptcha/api.js?render='.$recaptcha_public_v3);
} else {
	$doc->addScript('//www.google.com/recaptcha/api.js');
}
$doc->addScript('//cdn.jsdelivr.net/npm/sweetalert2');

$today = JFactory::getDate();
$db = JFactory::getDbo(); 
$nulldate = $db->getNullDate();
if(isset($this->item->film->next_episode_time) && !empty($this->item->film->next_episode_time) && $this->item->film->next_episode_time != $nulldate){
	$expected = JFactory::getDate($this->item->film->next_episode_time);
	if ($expected->toUnix() > $today->toUnix()){
		$doc->addStyleSheet('/components/com_tvshows/assets/css/style.css');
	}
}
if(isset($this->item->images) && count($this->item->images)) {
	$doc->addStyleSheet('//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css');
}

$comments_type = $this->component_params->get('comments_type');
$recaptcha_public = $this->component_params->get('recaptcha-public');
$fileshare_domains = $this->component_params->get('fileshare_domains');
$fileshare_domains = json_decode($fileshare_domains);
$enable_modal_fileshare_domains = $this->component_params->get('enable_modal_fileshare_domains', false, 'INT');
$enable_season_custom_btn_1_with_links = $this->component_params->get('enable_season_custom_btn_1_with_links', false, 'INT');

$fileshares = array();

foreach($fileshare_domains->domain as $k => $v){
	$fileshares[$k]['domain'] = $v;
	$fileshares[$k]['title'] = $fileshare_domains->name[$k];
	$fileshares[$k]['popup_content'] = urlencode($fileshare_domains->popup_content[$k]);
	$fileshares[$k]['buy_premium_url'] = $fileshare_domains->buy_premium_url[$k];
}

$app  = JFactory::getApplication();
$client = $app->client;
JPluginHelper::importPlugin( 'content' );
$dispatcher = JEventDispatcher::getInstance();

$h1_pattern = $this->component_params->get('h1_pattern', null);
$h2_pattern = $this->component_params->get('h2_pattern', null);
$description_pattern = $this->component_params->get('description_pattern', null);
$description_pattern_2 = $this->component_params->get('description_pattern_2', null);
$season_imdb_link = $this->component_params->get('season_imdb_link', null);
$season_imdb_link_nofollow = $this->component_params->get('season_imdb_link_nofollow', null);?>

<script>const fileshares = JSON.parse('<?php echo json_encode($fileshares);?>');</script>

<script src="/components/com_tvshows/assets/js/flipclock.min.js"></script>
<script>let arr = [];</script>

<div class="ps-season">
	<div class="page-header">
		<?php $document = JFactory::getDocument();
		$renderer   = $document->loadRenderer('modules');
		$options    = array('style' => 'xhtml');
		$position   = 'film-page-header';
		echo $renderer->render($position, $options, null);?>
	</div>
	
	<div class="intro">
	
		<?php if(isset($h1_pattern) && !empty($h1_pattern)){?>
			<h1>
				<?php $h1_pattern = str_replace('{season_number}', $this->item->season_number, str_replace('{film_name}', $this->item->film->title, $h1_pattern)); 
				if(isset($this->item->implodetags) && !empty($this->item->implodetags)){
					$h1_pattern = str_replace('{film_genres}', $this->item->implodetags, $h1_pattern);
				}
				$h1_pattern = str_replace('{film_creators}', $this->item->film->creators, $h1_pattern);
				$h1_pattern = str_replace('{film_channel}', $this->item->film->channel, $h1_pattern);
				if(isset($this->item->count_seasons) && !empty($this->item->count_seasons)){
					$h1_pattern = str_replace('{seasons_enumeration}', $this->item->count_seasons, $h1_pattern);
				}
				echo $h1_pattern;?>
			</h1>
		<?php } else {?>
			<h1><?php echo $this->item->title; ?></h1>
		<?php } ?>
		
		<?php if(isset($description_pattern) && !empty($description_pattern)){?>
			<p>
				<?php  $description_pattern = str_replace('{season_number}', $this->item->season_number, str_replace('{film_name}', $this->item->film->title, $description_pattern)); 
				if(isset($this->item->implodetags) && !empty($this->item->implodetags)){
					$description_pattern = str_replace('{film_genres}', $this->item->implodetags, $description_pattern);
				}
				$description_pattern = str_replace('{film_creators}', $this->item->film->creators, $description_pattern);
				$description_pattern = str_replace('{film_channel}', $this->item->film->channel, $description_pattern);
				$description_pattern = str_replace('{film_url}', TvshowsHelperRoute::getFilmRoute(null, $this->item->film->alias), $description_pattern);
				if(isset($this->item->count_seasons) && !empty($this->item->count_seasons)){
					$description_pattern = str_replace('{seasons_enumeration}', $this->item->count_seasons, $description_pattern);
				}
				echo $description_pattern;?>
			</p>
		<?php } ?>

		<div class="season-info clearfix">
			<?php $image = false;
			if(isset($this->item->main_image) && !empty($this->item->main_image)){
				$image = $this->item->main_image;
			} else {
				if(isset($this->item->film->main_image) && !empty($this->item->film->main_image)){
					$image = $this->item->film->main_image;
				}
			}?> 
			
			<div class="season-image">
				<div class="card">
					<?php if(isset($this->item->bage) && !empty($this->item->bage)){?><p class="bage"><?php echo $this->item->bage;?></p><?php } ?>

					<?php if($image){?>
						<div class="card-image">
							<img src="<?php echo $this->escape($image); ?>" alt="<?php echo $this->item->film->title.' season '.$this->item->season_number.' poster';?>" />
						</div>
					<?php } else {?>
						<div class="card-image">
							<img src="data:image/jpeg;base64,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Ai0AAAAQAAAAAQAAAAAAEnByaW50T3V0cHV0T3B0aW9ucwAAABcAAAAAQ3B0bmJvb2wAAAAAAENsYnJib29sAAAAAABSZ3NNYm9vbAAAAAAAQ3JuQ2Jvb2wAAAAAAENudENib29sAAAAAABMYmxzYm9vbAAAAAAATmd0dmJvb2wAAAAAAEVtbERib29sAAAAAABJbnRyYm9vbAAAAAAAQmNrZ09iamMAAAABAAAAAAAAUkdCQwAAAAMAAAAAUmQgIGRvdWJAb+AAAAAAAAAAAABHcm4gZG91YkBv4AAAAAAAAAAAAEJsICBkb3ViQG/gAAAAAAAAAAAAQnJkVFVudEYjUmx0AAAAAAAAAAAAAAAAQmxkIFVudEYjUmx0AAAAAAAAAAAAAAAAUnNsdFVudEYjUHhsQFIAAAAAAAAAAAAKdmVjdG9yRGF0YWJvb2wBAAAAAFBnUHNlbnVtAAAAAFBnUHMAAAAAUGdQQwAAAABMZWZ0VW50RiNSbHQAAAAAAAAAAAAAAABUb3AgVW50RiNSbHQAAAAAAAAAAAAAAABTY2wgVW50RiNQcmNAWQAAAAAAAAAAABBjcm9wV2hlblByaW50aW5nYm9vbAAAAAAOY3JvcFJlY3RCb3R0b21sb25nAAAAAAAAAAxjcm9wUmVjdExlZnRsb25nAAAAAAAAAA1jcm9wUmVjdFJpZ2h0bG9uZwAAAAAAAAALY3JvcFJlY3RUb3Bsb25nAAAAAAA4QklNA+0AAAAAABAASAAAAAEAAQBIAAAAAQABOEJJTQQmAAAAAAAOAAAAAAAAAAAAAD+AAAA4QklNBA0AAAAAAAQAAABaOEJJTQQZAAAAAAAEAAAAHjhCSU0D8wAAAAAACQAAAAAAAAAAAQA4QklNJxAAAAAAAAoAAQAAAAAAAAABOEJJTQP1AAAAAABIAC9mZgABAGxmZgAGAAAAAAABAC9mZgABAKGZmgAGAAAAAAABADIAAAABAFoAAAAGAAAAAAABADUAAAABAC0AAAAGAAAAAAABOEJJTQP4AAAAAABwAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAADhC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eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAxOC0wOS0yN1QxNzo0NDowOSswMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDphNDI5NjAyZS1iZmJhLWZmNGMtOGU3Mi1iOTliOTg5YTBlOTAiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDplZWQ4YjJhMS1kNTRiLTc1NGUtYjlkYy05MTFiMGQ4NmQxMTUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoyNDk4MzE4Mi1iM2RlLWVkNDAtODhhNy02MjExZjdjNWY2MmIiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIGRjOmZvcm1hdD0iaW1hZ2UvanBlZyI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MjQ5ODMxODItYjNkZS1lZDQwLTg4YTctNjIxMWY3YzVmNjJiIiBzdEV2dDp3aGVuPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDphNDI5NjAyZS1iZmJhLWZmNGMtOGU3Mi1iOTliOTg5YTBlOTAiIHN0RXZ0OndoZW49IjIwMTgtMDktMjdUMTc6NDQ6MDkrMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDxwaG90b3Nob3A6VGV4dExheWVycz4gPHJkZjpCYWc+IDxyZGY6bGkgcGhvdG9zaG9wOkxheWVyTmFtZT0ibm8gcG9zdGVyIGF2YWlsYWJsZSIgcGhvdG9zaG9wOkxheWVyVGV4dD0ibm8gcG9zdGVyIGF2YWlsYWJsZSIvPiA8L3JkZjpCYWc+IDwvcGhvdG9zaG9wOlRleHRMYXllcnM+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDw/eHBhY2tldCBlbmQ9InciPz7/7gAhQWRvYmUAZAAAAAABAwAQAwIDBgAAAAAAAAAAAAAAAP/bAIQACgcHBwgHCggICg8KCAoPEg0KCg0SFBAQEhAQFBEMDAwMDAwRDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAELDAwVExUiGBgiFA4ODhQUDg4ODhQRDAwMDAwREQwMDAwMDBEMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwM/8IAEQgBagD7AwERAAIRAQMRAf/EAM8AAQADAQEBAQEAAAAAAAAAAAAEBQYDAgcBCAEBAQEBAAAAAAAAAAAAAAAAAAECAxAAAAUC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" alt="<?php echo $this->item->film->title.' season '.$this->item->season_number.' poster';?>" />
						</div>
					<?php } ?>

					<?php if(
						(isset($this->item->film->rate_imdb) && !empty($this->item->film->rate_imdb)) ||
						(isset($this->item->film->votes) && !empty($this->item->film->votes))
					){?>
						<div class="imdb">
							<span class="imdbRating">
								<?php if(isset($this->item->film->rate_imdb) && !empty($this->item->film->rate_imdb)){?>
									<span class="rating"><?php echo $this->item->film->rate_imdb;?><span class="ofTen">/10</span></span>
								<?php } ?>

								<?php if(isset($this->item->film->votes) && !empty($this->item->film->votes)){?>
									<span class="votes"><?php echo $this->item->film->votes;?> <?php echo JText::_('COM_TVSHOWS_VOTES');?></span>
								<?php } ?>
							</span>
						</div>
					<?php } ?>
				</div>
				
				<?php $scroll_to_download_btn = $this->component_params->get('scroll_to_download_btn', null);
				if(isset($scroll_to_download_btn) && !empty($scroll_to_download_btn) && $scroll_to_download_btn){?>
					<?php if(isset($this->item->links[$this->item->season_number]) && !empty($this->item->links[$this->item->season_number])){?>
						<button data-element="#ps-season-episodes" class="scroll-to-download" onclick="jQuery('body, html').animate({scrollTop: jQuery(jQuery(this).data('element')).offset().top - 80})"><i></i>Get direct download links</button>
					<?php } ?>
				<?php } ?>
				
				<?php if(isset($this->item->custom_btn1_anchor) && !empty($this->item->custom_btn1_anchor) 
					&& isset($this->item->custom_btn1_url) && !empty($this->item->custom_btn1_url)){?>
						<?php echo TvshowsHelperSeason::customBtn($this->item, base64_encode($this->item->custom_btn1_url), $this->item->custom_btn1_anchor, array('class' => 'scroll-to-download custom-btn 3', 'rel' => 'nofollow', 'target' => '_blank'));?>
				<?php } else {
					$season_custom_btn_1_anchor = $this->component_params->get('season_custom_btn_1_anchor', null);
					$season_custom_btn_1_url = $this->component_params->get('season_custom_btn_1_url', null);
					if(isset($season_custom_btn_1_anchor) && !empty($season_custom_btn_1_anchor) &&
						isset($season_custom_btn_1_url) && !empty($season_custom_btn_1_url)){?>
							<?php if(isset($this->item->links[$this->item->season_number]) && !empty($this->item->links[$this->item->season_number])){
								if($enable_season_custom_btn_1_with_links == 1){
									echo TvshowsHelperSeason::customBtn($this->item, base64_encode($season_custom_btn_1_url), $season_custom_btn_1_anchor, array('class' => 'scroll-to-download custom-btn 1', 'rel' => 'nofollow', 'target' => '_blank'));
								}
							} else {
								echo TvshowsHelperSeason::customBtn($this->item, base64_encode($season_custom_btn_1_url), $season_custom_btn_1_anchor, array('class' => 'scroll-to-download custom-btn 2', 'rel' => 'nofollow', 'target' => '_blank'));
							}?>
					<?php }
				}?>
			</div>

			<div class="season-table">
				<?php if(isset($this->item->film->title) && !empty($this->item->film->title)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_ORIGINAL_TITLE');?></span>
						<?php if(isset($season_imdb_link) && !empty($season_imdb_link) && isset($this->item->film->imdbid) && !empty($this->item->film->imdbid)){?>
							<a href="https://www.imdb.com/title/<?php echo $this->item->film->imdbid;?>" target="_blank" <?php if($season_imdb_link_nofollow){?>rel="nofollow"<?php } ?>><?php echo $this->item->film->title;?></a>
						<?php } else {
							echo $this->item->film->title;
						}?>
					</div>
				<?php } ?>

				<div class="season-table-row">
					<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
					<?php echo $this->item->tagLayout->render($this->item->film->tags->itemTags); ?>
				</div>

				<?php if(isset($this->item->film->awards) && !empty($this->item->film->awards)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_AWARDS');?></span>
						<?php echo $this->item->film->awards;?>
					</div>
				<?php } ?>

				<?php if(isset($this->item->film->channel) && !empty($this->item->film->channel)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_CHANNEL');?></span>
						<?php echo $this->item->film->channel;?>
					</div>
				<?php } ?>

				<?php if(isset($this->item->film->creators) && !empty($this->item->film->creators)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_CREATORS');?></span>
						<?php echo $this->item->film->creators;?>
					</div>
				<?php } ?>

				<?php if(isset($this->item->film->directors) && !empty($this->item->film->directors)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_DIRECTORS');?></span>
						<?php echo $this->item->film->directors;?>
					</div>
				<?php } ?>

				<?php if(isset($this->item->film->cast) && !empty($this->item->film->cast)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_CAST');?></span>
						<?php echo $this->item->film->cast;?>
					</div>
				<?php } ?>
				
				<?php if(isset($this->item->film->language) && !empty($this->item->film->language)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_LANGUAGE');?></span>
						<?php switch($this->item->film->language){
							case 'en':
								echo 'English';
								break;
						}?>
					</div>
				<?php } ?>
				
				<?php if(isset($this->item->film->language) && !empty($this->item->film->language)){?>
					<div class="season-table-row">
						<span class="strong"><?php echo JText::_('COM_TVSHOWS_VIEW_DESCRIPTION');?></span>
						<?php echo $this->item->description;?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<?php if(isset($this->item->images) && count($this->item->images)) { ?>
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
									<img src="<?php echo $img;?>" alt="<?php echo $this->item->film->title.' season '.$this->item->season_number.' screencaps';?>" />
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	<?php } ?>
	
	<div class="film-info" id="film-info">
		<div class="main-title">
		
			<?php if(isset($h2_pattern) && !empty($h2_pattern)){?>
				<h2>
					<?php  $h2_pattern = str_replace('{season_number}', $this->item->season_number, str_replace('{film_name}', $this->item->film->title, $h2_pattern)); 
					if(isset($this->item->implodetags) && !empty($this->item->implodetags)){
						$h2_pattern = str_replace('{film_genres}', $this->item->implodetags, $h2_pattern);
					}
					$h2_pattern = str_replace('{film_creators}', $this->item->film->creators, $h2_pattern);
					$h2_pattern = str_replace('{film_channel}', $this->item->film->channel, $h2_pattern);
					if(isset($this->item->count_seasons) && !empty($this->item->count_seasons)){
						$h2_pattern = str_replace('{seasons_enumeration}', $this->item->count_seasons, $h2_pattern);
					}
					echo $h2_pattern;?>
				</h2>
			<?php } else {?>
				<h2><?php echo $this->item->title; ?></h2>
			<?php } ?>
			
			<?php if(isset($description_pattern_2) && !empty($description_pattern_2)){?>
				<div class="subtitle">
					<?php $description_pattern_2 = str_replace('{season_number}', $this->item->season_number, str_replace('{film_name}', $this->item->film->title, $description_pattern_2)); 
					if(isset($this->item->implodetags) && !empty($this->item->implodetags)){
						$description_pattern_2 = str_replace('{film_genres}', $this->item->implodetags, $description_pattern_2);
					}
					$description_pattern_2 = str_replace('{film_creators}', $this->item->film->creators, $description_pattern_2);
					$description_pattern_2 = str_replace('{film_channel}', $this->item->film->channel, $description_pattern_2);
					$description_pattern_2 = str_replace('{film_url}', TvshowsHelperRoute::getFilmRoute(null, $this->item->film->alias), $description_pattern_2);
					if(isset($this->item->count_seasons) && !empty($this->item->count_seasons)){
						$description_pattern_2 = str_replace('{seasons_enumeration}', $this->item->count_seasons, $description_pattern_2);
					}
					echo  $description_pattern_2;?>
				</div>
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
											<h4 class="h5"><?php echo $this->item->film->next_episode_name;?></h4>
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
	
	<?php //echo '<pre>';var_dump($this->item->episodes);
	if(isset($this->item->episodes) && !empty($this->item->episodes)){
		$empty_episode_function = $this->component_params->get('empty_episode_function', null);
		$empty_episode_url = $this->component_params->get('empty_episode_url', null);
		if($empty_episode_function){?>
			<div class="ps-season-episodes" id="ps-season-episodes">
				<?php foreach($this->item->episodes as $episodes){?>
					<div itemprop="episode" itemscope itemtype="https://schema.org/Episode">
						<div class="block-content episodes-title bg2">
							<h4 class="h3"><span itemprop="name"><?php echo JText::_('COM_TVSHOWS_VIEW_EPISODE');?>&nbsp;<?php echo $episodes['episode_number'];?></span></h4>
						</div>
						<?php if(count($episodes)){?>
							<div class="block-content download-list">
								<div class="badge block-badge">
									<?php echo 'S';
									if($episodes['season_number'] < 10){
										echo '0'.$episodes['season_number'];
									} else {
										echo $episodes['season_number'];
									}
									echo 'E';
									if($episodes['episode_number'] < 10){
										echo '0'.$episodes['episode_number'];
									} else {
										echo $episodes['episode_number'];
									}?>
								</div>
								<div class="download-table">
									<div class="download-row title-row"> 
										<div class="download-cell cell1"></div>
									</div>
									<div class="download-row">
										<div class="download-cell cell1">
											<?php if(isset($empty_episode_url) && !empty($empty_episode_url)){?>
													<?php echo JHTML::_('link', base64_encode($empty_episode_url), JText::_('COM_TVSHOWS_VIEW_WATCH').'&nbsp;'.JText::_('COM_TVSHOWS_VIEW_EPISODE').'&nbsp;'.$episodes['episode_number'].'&nbsp;'.$episodes['name'], array('class' => 'scroll-to-download custom-btn main-btn with-download-icon 6', 'rel' => 'nofollow', 'target' => '_blank'));?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php } 
	}?>
	
	
	<?php if(isset($this->item->links[$this->item->season_number]) && !empty($this->item->links[$this->item->season_number])){?>
		<?php $counter = 1;?>
		<div class="ps-season-episodes" id="ps-season-episodes">
			<?php foreach($this->item->links[$this->item->season_number] as $episodes){?>
				<div itemprop="episode" itemscope itemtype="https://schema.org/Episode">
					<div class="block-content episodes-title bg2">
						<h4 class="h3"><?php echo JText::_('COM_TVSHOWS_VIEW_EPISODE');?> <span itemprop="name"><?php echo $episodes[0]['episode'];?></span></h4>
					</div>
					<?php if(count($episodes)){?>
						<div class="block-content download-list">
							<div class="badge block-badge">
								<?php echo 'S';
								if($episodes[0]['season'] < 10){
									echo '0'.$episodes[0]['season'];
								} else {
									echo $episodes[0]['season'];
								}
								echo 'E';
								if($episodes[0]['episode'] < 10){
									echo '0'.$episodes[0]['episode'];
								} else {
									echo $episodes[0]['episode'];
								}?>
							</div>
							<div class="download-table">
								<div class="download-row title-row"> 
									<div class="download-cell cell1"><?php echo JText::_('COM_TVSHOWS_VIEW_FORMAT');?></div>
									<div class="download-cell cell2"><?php echo JText::_('COM_TVSHOWS_VIEW_FILESIZE');?></div>
									<div class="download-cell cell3">
										<div class="download-col">
											<span class="links-title"><?php echo JText::_('COM_TVSHOWS_VIEW_DIRECT_DOWNLOAD_LINKS');?></span> 
												<a class="report" href="#" data-info="URL: <?php echo JUri::getInstance();?> <?php echo JText::_('COM_TVSHOWS_VIEW_EPISODE');?>:<?php echo $episodes[0]['episode'];?>">
											<span><?php echo JText::_('COM_TVSHOWS_VIEW_REPORT');?></span> <?php echo JText::_('COM_TVSHOWS_VIEW_BROKEN_LINK');?></a>
										</div>
									</div>
								</div>
									
								<?php  $format_episodes = array();
								foreach($episodes as $k => $v){
									if(!isset($format_episodes[$v['episode']])){$format_episodes[$v['episode']] = array();}
									if(isset($v['format'])){
										if(!isset($format_episodes[$v['episode']][$v['format']])){
											$format_episodes[$v['episode']][$v['format']] = array();
										}
										
										if(isset($v['quality'])){
											if(!isset($format_episodes[$v['episode']][$v['format']][$v['quality']])){
												$format_episodes[$v['episode']][$v['format']][$v['quality']] = array();
											}
										
											$format_episodes[$v['episode']][$v['format']][$v['quality']][] = $v;
										} else {
											if(!isset($format_episodes[$v['episode']][$v['format']]['unquality'])){
												$format_episodes[$v['episode']][$v['format']]['unquality'] = array();
											}
										
											$format_episodes[$v['episode']][$v['format']]['unquality'][] = $v;
										}
									} else {
										if(!isset($format_episodes[$v['episode']]['unformated'])){$format_episodes[$v['episode']]['unformated'] = array();}
										$format_episodes[$v['episode']]['unformated'][] = $v;
									}									
								}?>
								
								<?php foreach($format_episodes as $episode){
									ksort($episode);
										
									foreach($episode as $format => $formates){
										
										if($format != 'unformated'){
											ksort($formates);
											
											foreach($formates as $quality => $items){
												$size = explode(' ', $items[0]['size']);?>

												<div class="download-row">
													<div class="download-cell cell1">
														<span class="format-icon <?php echo strtolower($format);?>"><?php echo strtoupper($format);?></span> 
														<span><?php echo strtoupper($format);?></span> 
														<?php if($quality != 'unquality'){?>(<?php echo $quality;?>)<?php } ?>
													</div>
											
													<div class="download-cell cell2"><span><?php echo $size[0];?></span> <?php echo $size[1];?></div>
											
													<div class="download-cell cell3">
														<?php foreach($items as $link){
															if($enable_modal_fileshare_domains){
																if($link['hosted'] == 'Keep2Share'){
																	preg_match('/file\/(.*)\//', $link['url'], $matches);
																	if(count($matches) > 1){?> 
																		<button id="btn-0-<?php echo $counter;?>" data-url="btn-<?php echo $counter;?>" data-id="0" data-toggle="modal" data-target="#premium" class="main-btn with-play-icon small"><span><?php echo JText::_('COM_TVSHOWS_VIEW_PREMIUM_WATCH');?></span></button>
																	<?php }
																}?>
															
																<a id="btn-<?php echo $counter;?>" href="#" data-id="<?php echo $counter;?>" class="main-btn with-download-icon small 1">
																	<span><?php echo $link['hosted'];?></span>
																</a>
																<script>arr['btn-<?php echo $counter;?>']='<?php echo base64_encode($link['url']);?>';</script>
																<?php $counter++;
															} else { ?>
																<button class="disable-modal-fileshare-domains main-btn with-download-icon small 1" data-link="<?php echo base64_encode($link['url']);?>">
																	<span><?php echo $link['hosted'];?></span>
																</button>
															<?php }
														} ?>
													</div>
												</div>
											<?php } 
										} else {
											$size = explode(' ', $formates[0]['size']);?>
											
											<div class="download-row">
												<div class="download-cell cell1">
													<?php if($quality != 'unquality'){?>(<?php echo $quality;?>)<?php } ?>
												</div>
										
												<div class="download-cell cell2"><span><?php echo $size[0];?></span> <?php echo $size[1];?></div>
										
												<div class="download-cell cell3">
													<?php foreach($formates as $link){
														//var_dump($enable_modal_fileshare_domains);
														if($enable_modal_fileshare_domains){?>
															<a id="btn-<?php echo $counter;?>" href="#" class="main-btn with-download-icon small 2">
																<span><?php echo $link['hosted'];?></span>
															</a>
															<script>arr['btn-<?php echo $counter;?>']='<?php echo base64_encode($link['url']);?>';</script>
																<?php $counter++;
														} else {?>
														
														<?php }
													} ?>
												</div>
											</div>
											
										<?php } ?>
										
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div><!--end metadata div-->
	<?php } ?>
	
	<?php if(isset($this->neighbors) && !empty($this->neighbors)){?>
		<div class="other-seasons-title"><?php echo JText::_('COM_TVSHOWS_VIEW_OTHER_SEASONS');?> <?php echo $this->item->film->title; ?></div>
		<div class="other-seasons-items">
			<span class="counter"><?php echo JText::_('COM_TVSHOWS_VIEW_SEASONS');?></span>
			<ul class="main-list">
				<?php foreach ($this->neighbors as $key => $value) {?>
					<li class="main-list-item">
						<div class="card">
							<a href="<?php echo TvshowsHelperRoute::getSeasonRoute(null, $value->alias, $this->item->film->alias); ?>">
								<div class="card-image">
									<?php if(isset($value->main_image) && !empty($value->main_image)){
										$image = $value->main_image;
									} else {
										if(isset($this->item->film->main_image) && !empty($this->item->film->main_image)){
											$image = $this->item->film->main_image;
										}
									}?>

									<?php if($image){?>
										<div class="img" style="background-image:url(<?php echo $this->escape($image);?>);"></div>
									<?php } else {?>
										<div class="img" style="background-image:url('data:image/jpeg;base64,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Ai0AAAAQAAAAAQAAAAAAEnByaW50T3V0cHV0T3B0aW9ucwAAABcAAAAAQ3B0bmJvb2wAAAAAAENsYnJib29sAAAAAABSZ3NNYm9vbAAAAAAAQ3JuQ2Jvb2wAAAAAAENudENib29sAAAAAABMYmxzYm9vbAAAAAAATmd0dmJvb2wAAAAAAEVtbERib29sAAAAAABJbnRyYm9vbAAAAAAAQmNrZ09iamMAAAABAAAAAAAAUkdCQwAAAAMAAAAAUmQgIGRvdWJAb+AAAAAAAAAAAABHcm4gZG91YkBv4AAAAAAAAAAAAEJsICBkb3ViQG/gAAAAAAAAAAAAQnJkVFVudEYjUmx0AAAAAAAAAAAAAAAAQmxkIFVudEYjUmx0AAAAAAAAAAAAAAAAUnNsdFVudEYjUHhsQFIAAAAAAAAAAAAKdmVjdG9yRGF0YWJvb2wBAAAAAFBnUHNlbnVtAAAAAFBnUHMAAAAAUGdQQwAAAABMZWZ0VW50RiNSbHQAAAAAAAAAAAAAAABUb3AgVW50RiNSbHQAAAAAAAAAAAAAAABTY2wgVW50RiNQcmNAWQAAAAAAAAAAABBjcm9wV2hlblByaW50aW5nYm9vbAAAAAAOY3JvcFJlY3RCb3R0b21sb25nAAAAAAAAAAxjcm9wUmVjdExlZnRsb25nAAAAAAAAAA1jcm9wUmVjdFJpZ2h0bG9uZwAAAAAAAAALY3JvcFJlY3RUb3Bsb25nAAAAAAA4QklNA+0AAAAAABAASAAAAAEAAQBIAAAAAQABOEJJTQQmAAAAAAAOAAAAAAAAAAAAAD+AAAA4QklNBA0AAAAAAAQAAABaOEJJTQQZAAAAAAAEAAAAHjhCSU0D8wAAAAAACQAAAAAAAAAAAQA4QklNJxAAAAAAAAoAAQAAAAAAAAABOEJJTQP1AAAAAABIAC9mZgABAGxmZgAGAAAAAAABAC9mZgABAKGZmgAGAAAAAAABADIAAAABAFoAAAAGAAAAAAABADUAAAABAC0AAAAGAAAAAAABOEJJTQP4AAAAAABwAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAADhC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eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAxOC0wOS0yN1QxNzo0NDowOSswMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDphNDI5NjAyZS1iZmJhLWZmNGMtOGU3Mi1iOTliOTg5YTBlOTAiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDplZWQ4YjJhMS1kNTRiLTc1NGUtYjlkYy05MTFiMGQ4NmQxMTUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDoyNDk4MzE4Mi1iM2RlLWVkNDAtODhhNy02MjExZjdjNWY2MmIiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIGRjOmZvcm1hdD0iaW1hZ2UvanBlZyI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MjQ5ODMxODItYjNkZS1lZDQwLTg4YTctNjIxMWY3YzVmNjJiIiBzdEV2dDp3aGVuPSIyMDE4LTA5LTI3VDE3OjQ0OjA5KzAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDphNDI5NjAyZS1iZmJhLWZmNGMtOGU3Mi1iOTliOTg5YTBlOTAiIHN0RXZ0OndoZW49IjIwMTgtMDktMjdUMTc6NDQ6MDkrMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDxwaG90b3Nob3A6VGV4dExheWVycz4gPHJkZjpCYWc+IDxyZGY6bGkgcGhvdG9zaG9wOkxheWVyTmFtZT0ibm8gcG9zdGVyIGF2YWlsYWJsZSIgcGhvdG9zaG9wOkxheWVyVGV4dD0ibm8gcG9zdGVyIGF2YWlsYWJsZSIvPiA8L3JkZjpCYWc+IDwvcGhvdG9zaG9wOlRleHRMYXllcnM+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDw/eHBhY2tldCBlbmQ9InciPz7/7gAhQWRvYmUAZAAAAAABAwAQAwIDBgAAAAAAAAAAAAAAAP/bAIQACgcHBwgHCggICg8KCAoPEg0KCg0SFBAQEhAQFBEMDAwMDAwRDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAELDAwVExUiGBgiFA4ODhQUDg4ODhQRDAwMDAwREQwMDAwMDBEMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwM/8IAEQgBagD7AwERAAIRAQMRAf/EAM8AAQADAQEBAQEAAAAAAAAAAAAEBQYDAgcBCAEBAQEBAAAAAAAAAAAAAAAAAAECAxAAAAUC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');"></div>
									<?php } ?>

									<?php if(isset($value->bage) && !empty($value->bage)){?>
										<span class="bage"><?php echo $value->bage;?></span>
									<?php } ?>

									<div class="download-icon"><span></span></div>

									<div class="card-text">
										<div class="card-title"><?php echo $this->item->film->title.' '.$value->title;?></div>
									</div>
								</div>
							</a>

							<div class="card-capt"><?php echo $value->episode_count;?> <?php echo JText::_('COM_TVSHOWS_VIEW_EPISODES');?></div>

							<a class="go-to-season" href="<?php echo TvshowsHelperRoute::getSeasonRoute(null, $value->alias, $this->item->film->alias); ?>"><?php echo JText::_('COM_TVSHOWS_VIEW_DOWNLOAD');?></a>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
	
	
	<div class="comments">
		<?php switch($comments_type){
			case 'disqus':
				if (JPluginHelper::isEnabled('system', 'jw_disqus') != false){
					require_once (JPATH_ROOT.'/plugins/content/jw_disqus/jw_disqus/includes/helper.php');
					$plugin = JPluginHelper::getPlugin('content', 'jw_disqus');
					$pluginParams = new JRegistry($plugin->params);
					$disqusSubDomain = trim($pluginParams->get('disqusSubDomain', ''));
					$disqusLanguage = $pluginParams->get('disqusLanguage');
					$selectedCategories = $pluginParams->get('selectedCategories', '');
					$selectedMenus = $pluginParams->get('selectedMenus', '');
					$disqusListingCounter = $pluginParams->get('disqusListingCounter', 1);
					$disqusArticleCounter = $pluginParams->get('disqusArticleCounter', 1);
					$disqusDevMode = $pluginParams->get('disqusDevMode', 0);
					
					$output = new stdClass;
					$itemURL = JRoute::_(TvshowsHelperRoute::getSeasonRoute($this->item->film->id, $this->item->film->alias, $this->item->alias));
					$websiteURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https://".$_SERVER['HTTP_HOST'] : "http://".$_SERVER['HTTP_HOST'];
					$itemURLbrowser = explode("#", $websiteURL.$_SERVER['REQUEST_URI']);
					$itemURLbrowser = $itemURLbrowser[0];
					$output->itemURL = $websiteURL.$itemURL;
					$output->itemURLrelative = $itemURL;
					$output->itemURLbrowser = $itemURLbrowser;
					$output->disqusIdentifier = substr(md5($disqusSubDomain), 0, 10).'_id'.$this->item->id;
					$output->comments = "
						<div id=\"disqus_thread\"></div>
						<script type=\"text/javascript\">
							//<![CDATA[
							var disqus_shortname = '".$disqusSubDomain."';
							var disqus_url = '".$output->itemURL."';
							var disqus_identifier = '".substr(md5($disqusSubDomain), 0, 10)."_id".$this->item->id."';
							var disqus_developer = '".$disqusDevMode."';
							var disqus_config = function(){
								this.language = '{$disqusLanguage}';
							};
							(function() {
								var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
								dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
								(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
							})();
							//]]>
						</script>
						<noscript>
							<a href=\"https://".$disqusSubDomain.".disqus.com/?url=ref\">".JText::_("JW_DISQUS_VIEW_THE_DISCUSSION_THREAD")."</a>
						</noscript>
					";

					if($disqusArticleCounter){ ?>
						<div class="jwDisqusArticleCounter">
							<span>
								<a class="jwDisqusArticleCounterLink" href="#disqus_thread" data-disqus-identifier="<?php echo $output->disqusIdentifier; ?>"><?php echo JText::_("JW_DISQUS_VIEW_COMMENTS"); ?></a>
							</span>
							<div class="clr"></div>
						</div>
					<?php } ?>

					<div class="jwDisqusForm"><?php echo $output->comments; ?></div>

					<div class="clr"></div>
				<?php }
				break;
				
			case 'facebook':?>
				
				<div id="fb-root"></div>
					<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.1&appId=220214995343189&autoLogAppEvents=1';
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
				
				<div class="fb-comments" data-colorscheme="light" data-mobile="true" data-href="<?php echo jUri::getInstance();?>" data-numposts="10" data-width="100%"></div>
				
				<?php break;
		}?>
	</div>
</div>

<div class="modal fade" id="report" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title"><?php echo JText::_('COM_TVSHOWS_VIEW_REPORT');?> <?php echo JText::_('COM_TVSHOWS_VIEW_BROKEN_LINK');?></h4>
			</div>
			<div class="modal-body">
				<form action="index.php" method="POST" class="report-borken-link">
					<input type="text" name="url" required="required" class="input" />
					<input type="text" name="name" placeholder="<?php echo JText::_('COM_TVSHOWS_VIEW_YOUR_NAME_PLACEHOLDER');?>" class="input" />
					<input type="text" name="email" required="required" placeholder="<?php echo JText::_('COM_TVSHOWS_VIEW_YOUR_EMAIL_REQUIRED_PLACEHOLDER');?>" class="input" />
					<textarea name="comment" placeholder="<?php echo JText::_('COM_TVSHOWS_VIEW_YOUR_COMMENT_PLACEHOLDER');?>" class="input"></textarea>
					<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public;?>" <?php if($client->mobile){?>data-size="compact"<?php } ?>></div>
					<div class="row">
						<div class="col-sm-4 col-sm-offset-4">
							<input type="submit" name="submit" value="<?php echo JText::_('COM_TVSHOWS_VIEW_SUBMIT_PLACEHOLDER');?>" class="main-btn" />
						</div>
					</div>
					<input type="hidden" name="option" value="com_tvshows" />
					<input type="hidden" name="task" value="seasons.reportbroken" />
					<input type="hidden" name="season_id" value="<?php echo $this->item->id;?>" />
					<input type="hidden" name="btns" value="" />
					<input type="hidden" name="ajax" value="1" />
					<?php echo JHtml::_( 'form.token' ); ?>
				</form>
			</div>
		</div>
	</div>
</div>

<?php if(count($fileshares)){?>
	<div class="modal fade" id="premium" tabindex="-1" role="dialog" style="display: none;">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<div class="h4 modal-title"></div>
				</div>
				<div class="modal-body">
					<div class="preview-block"></div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<?php foreach($fileshares as $fileshare){?>
	<div class="modal fade" id="<?php echo str_replace('.', '_', $fileshare['domain']);?>" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title"><?php echo JText::_('COM_TVSHOWS_VIEW_TIRED_OF_SLOW_DOWNLOAD_SPEED');?></h4>
				</div>
				<div class="modal-body">
					<div class="row file-sharing">
						<?php echo urldecode($fileshare['popup_content']);?>
					</div>
				</div>
				<div class="modal-footer">	
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-6"><a target="_blank" rel="nofollow" href="<?php echo $fileshare['buy_premium_url'];?>" class="btn main-btn vip-btn"><span><?php echo JText::_('COM_TVSHOWS_VIEW_BUY_PREMIUM');?></span></a></div>
							<div class="col-sm-6 text-center"><a target="_blank" href="#" class="btn main-btn"><span><?php echo JText::_('COM_TVSHOWS_VIEW_CONTINUE_FREE_DOWNLOAD');?></span></a></div>
						</div>
						<br>
						<div class="text-center">
							<label><input type="checkbox"> <?php echo JText::_('COM_TVSHOWS_VIEW_DONT_ASK_ME_AGAIN');?></label>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>		

<script src="/components/com_tvshows/assets/js/bootstrap.min.js"></script>

<script>
	function showResponse(responseText, statusText, xhr, $form){
		responseText = JSON.parse(responseText);
		if(statusText == 'success'){
			swal(
				responseText.message,
				'',
				'success'
			);
		} else {
			swal(
				responseText.message,
				'',
				'error'
			);
		}
	} 

	jQuery(document).ready(function(){
		jQuery('form.report-borken-link').on('submit', function(e){
			const options = {success:showResponse,clearForm:true,replaceTarget:false}; 
			e.preventDefault();
			jQuery(this).ajaxSubmit(options);
			jQuery('#report').modal('hide');
		});
		
		jQuery('.ps-season .gallery .nav-tabs li a').click(function (e) {
			e.preventDefault()
			jQuery(this).tab('show')
		});
		
		if (jQuery('.next-episodes-item').length>0){
		  	var d = false;
		  	d = jQuery('.next-episodes-item').eq(-1).find('.cell3').find('span').text();
		  	if (d){
		  		var time_arr = d.split('/');
			  	var res_date = new Date(time_arr[2]+'-'+time_arr[1]+'-'+time_arr[0]+'T08:00:00');

			  	var now = new Date();
			  	var today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
			  	var sdate = new Date(res_date.getFullYear(), res_date.getMonth(), res_date.getDate());

			  	if ( today.valueOf() < sdate.valueOf() ){
			  		var diff = res_date.getTime() / 1000 - today.getTime() / 1000;
					clock = jQuery('.countdown').FlipClock(diff, {
						clockFace: 'DailyCounter',
						countdown: true,
						language:'en-Gb'
						//https://github.com/objectivehtml/FlipClock/tree/master/src/flipclock/js/lang
					});
			  	}else{
			  		jQuery('.countdown').addClass('today').text('Today!')
			  	}
		  	}
	  	}

	  	jQuery('.next-episodes-toggle button').click(function(){
		    jQuery('.next-episodes-item').not('.next-episodes-item:last-child').slideToggle('fast');
		    var text = jQuery(this).text();
		    jQuery(this).text(jQuery(this).attr('data-text'));
		    jQuery(this).attr('data-text', text)
		});
	});
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/js/swiper.min.js"></script>

<?php if(
	(isset($this->item->images) && count($this->item->images)) ||
	(isset($this->item->videos) && count($this->item->videos))
) {?>
	<script>
		jQuery(document).ready(function(){
			const gallerySlider = new Swiper ('#screens .swiper-container', {
				direction: 'horizontal',
				loop: false,
				slidesPerView: 5,
				spaceBetween: 25,
				pagination: {el: '#screens .swiper-pagination',},
				navigation: {nextEl: '#screens .swiper-button-next',prevEl: '#screens .swiper-button-prev',},
				breakpoints: {
					320: {slidesPerView: 1,spaceBetween: 0},
					480: {slidesPerView: 2,spaceBetween: 20},
					640: {slidesPerView: 3,spaceBetween: 25},
					980: {slidesPerView: 3,spaceBetween: 25},
					1024: {slidesPerView: 3,spaceBetween: 25},
					1279: {slidesPerView: 3,spaceBetween: 25}
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
					320: {slidesPerView: 1, 					  spaceBetween: 0 					},
					480: {slidesPerView: 2, 					  spaceBetween: 20 					},
					640: {slidesPerView: 2, 					  spaceBetween: 25 					},
					980: {slidesPerView: 3, 					  spaceBetween: 25 					},
					1279: {slidesPerView: 3, 					  spaceBetween: 25 					}
				}
			});
		});
	</script>
<?php } ?>

<script src="/components/com_tvshows/assets/js/form.js"></script>
<script>
	var active_btn;
	
	<?php if(isset($recaptcha_public_v3) && !empty($recaptcha_public_v3)){?>
	const recaptchaV3 = () => {
		return new Promise(function(resolve, reject) {
			grecaptcha.ready(function() {
				grecaptcha.execute('<?php echo $recaptcha_public_v3;?>', {action: 'homepage'}).then(function(token) {
					jQuery.getJSON('index.php?option=com_tvshows&task=seasons.verify&<?php echo JSession::getFormToken() .'=1';?>',{token: token})
					.done(function(r){	
						if(r.success == true){
							if(r.data == true){
								resolve(true);
							} else {
								console.warn('captcha v3 not validate');
								resolve(false);
							}
						} else {
							console.error('captcha v3 error');
							alert('Are You robot?');
							resolve(false);
						}
					});
				});
			});
		});
	}
	<?php } ?>
	
	function showPremumWatch(that){
		var link = atob(arr[that.attr('data-url')]);
		jQuery('#premium .preview-block').html('');
		jQuery('#premium .modal-title').text(that.closest('[itemprop="episode"]').find('.episodes-title').find('.h3').text());
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.dataset.url = link;
		script.dataset.width = "900px";
		script.dataset.height = "56.25%";
		if (script.readyState){
			script.onreadystatechange = function(){
				if (script.readyState == "loaded" ||
				script.readyState == "complete"){
					script.onreadystatechange = null;
				}
			};
		} else {
			script.onload = function(){};
		}
		script.src = "https://k2s.cc/js/preview.js";
		document.getElementsByClassName("preview-block")[0].appendChild(script);
		jQuery('#premium').modal('show');
	}
	
	jQuery(document).ready(function(){		
		jQuery('.report').on('click',function(e){
			e.preventDefault();
			jQuery('#report').modal('show');
			var episode = jQuery(this).parents('.block-content.download-list').prev().find('h4').text();
			jQuery('#report input[name="url"]').val('URL:' + location.href + ' ' + episode);
			var btns = jQuery(this).parents('.download-table').find('a[id*="btn-"]');
			var btns_info = [];
			btns.each(function(key, value){
				btns_info.push({
					url: arr[jQuery(value).attr('id')],
					anchor: jQuery(value).text().replace(/\t/g,'')
				});
			});
			jQuery('#report input[name="btns"]').val(JSON.stringify(btns_info));
		});
		
		jQuery('.ps-season .custom-btn').on('click',function(e){
			e.preventDefault();
			var href = jQuery(this).attr('href');
			if (href[0] == '/') { 
				href = atob(href.substring(1));
			}
			location.href = href;
		});
		
		jQuery('.download-cell button.main-btn').click(function(e){
			const that = jQuery(this);
			if (that.hasClass('with-play-icon')){
				e.preventDefault();
				
				<?php if(isset($recaptcha_public_v3) && !empty($recaptcha_public_v3)){?>
					var recaptchaV3Result = recaptchaV3();
					recaptchaV3Result.then(function(r){
						if(r === true){
							showPremumWatch(that);
						}
					});
				<?php } else {?>
					showPremumWatch(that);
				<?php } ?>
				return false;
			} else {
				var link = jQuery(this).data('link');
				if(typeof link != 'undefined' && link != ''){
					link= atob(link);
					<?php if(isset($recaptcha_public_v3) && !empty($recaptcha_public_v3)){?>
						var recaptchaV3Result = recaptchaV3();
						recaptchaV3Result.then(function(r){
							if(r === true){
								var win = window.open(link, '_blank');
								win.focus();
							}
						});
					<?php } else {?>
						var win = window.open(link, '_blank');
						win.focus();
					<?php } ?>
				}
			}
		});
		
		jQuery('.download-cell a.main-btn').click(function(){
			active_btn = jQuery(this);
			var attr = active_btn.attr('rel');

			if (typeof attr !== typeof undefined && attr !== false) {
			}else{
				var link = atob(arr[active_btn.attr('id')]); 
				//console.log(link);
				if (  (link.indexOf('k2s.cc') == -1) && (link.indexOf('tezfiles.com') == -1 ) && (link.indexOf('publish2.me') == -1 )){
					link = "http://linkshrink.net/zLly="+link;
					var win = window.open(link, '_blank');
					win.focus();
				} else {
					//console.log('else');
					link = link+'?site='+window.location.host;
					if (localStorage.getItem('file-vip')){
						var win = window.open(link, '_blank');
						win.focus();				
					}else{
						if (link.indexOf('k2s.cc') >= 0){
							jQuery('#k2s_cc').modal('show');
							jQuery('#k2s_cc .btn').not('.vip-btn').attr('href', link);
						}else{
							if (link.indexOf('tezfiles.com') >= 0){
								jQuery('#tezfiles_com').modal('show');		
								jQuery('#tezfiles_com .btn').not('.vip-btn').attr('href', link);
							}else{
								if (link.indexOf('publish2.me') >= 0){
									jQuery('#publish2_me').modal('show');		
									jQuery('#publish2_me .btn').not('.vip-btn').attr('href', link);
								}else{
									var win = window.open(link, '_blank');
									win.focus();
								}	
							}
						}
					}
				}
				return false;
			}
		});

		jQuery('#k2s_cc .btn, #tezfiles_com .btn, #publish2_me .btn, #turbobit_net .btn').click(function(e){
			const that = jQuery(this);
			if (that.closest('.modal-footer').find('input[type="checkbox"]').prop('checked')){
				localStorage.setItem('file-vip', true);
			}
			if (!that.hasClass('vip-btn')){
				jQuery('#k2s_cc, #tezfiles_com, #publish2_me, #turbobit_net').modal('hide'); 		
			}
			e.preventDefault();
			<?php if(isset($recaptcha_public_v3) && !empty($recaptcha_public_v3)){?>
				var recaptchaV3Result = recaptchaV3();
				recaptchaV3Result.then(function(r){
					if(r === true){
						var win = window.open(that.attr('href'), '_blank');
						win.focus();
					}
				});
			<?php } else { ?>
				var win = window.open(that.attr('href'), '_blank');
				win.focus();
			<?php } ?>
			return false;
		});
  
		function getCookie(name) {
			let matches = document.cookie.match(new RegExp(
				"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			));
			return matches ? decodeURIComponent(matches[1]) : undefined;
		}

		function setCookie(name, value, options) {
			options = options || {};

			let expires = options.expires;

			if (typeof expires == "number" && expires) {
				let d = new Date();
				d.setTime(d.getTime() + expires * 1000);
				expires = options.expires = d;
			}
			if (expires && expires.toUTCString) {
				options.expires = expires.toUTCString();
			}

			value = encodeURIComponent(value);

			let updatedCookie = name + "=" + value;

			for (let propName in options) {
				updatedCookie += "; " + propName;
				let propValue = options[propName];
				if (propValue !== true) {
					updatedCookie += "=" + propValue;
				}
			}

			document.cookie = updatedCookie;
		}
	});
</script>