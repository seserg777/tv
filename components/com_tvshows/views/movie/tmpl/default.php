<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
$doc = JFactory::getDocument();

$doc->addScript('//www.google.com/recaptcha/api.js');
$doc->addScript('//cdn.jsdelivr.net/npm/sweetalert2');

$today = JFactory::getDate();
$db = JFactory::getDbo(); 
$nulldate = $db->getNullDate();
if(isset($this->item->next_episode_time) && !empty($this->item->next_episode_time) && $this->item->next_episode_time != $nulldate){
	$expected = JFactory::getDate($this->item->next_episode_time);
	if ($expected->toUnix() > $today->toUnix()){
		$doc->addStyleSheet('/components/com_tvshows/assets/css/style.css');
	}
}
if(isset($this->item->images) && count($this->item->images)) {
	$doc->addStyleSheet('//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css');
}

$fileshares = array();

foreach($this->fileshare_domains->domain as $k => $v){
	$fileshares[$k]['domain'] = $v;
	$fileshares[$k]['title'] = $this->fileshare_domains->name[$k];
	$fileshares[$k]['popup_content'] = urlencode($this->fileshare_domains->popup_content[$k]);
	$fileshares[$k]['buy_premium_url'] = $this->fileshare_domains->buy_premium_url[$k];
}

$app  = JFactory::getApplication();
$client = $app->client;
JPluginHelper::importPlugin( 'content' );
$dispatcher = JEventDispatcher::getInstance();?>

<script>const fileshares = JSON.parse('<?php echo json_encode($fileshares);?>');</script>

<script src="/components/com_tvshows/assets/js/flipclock.min.js"></script>
<script>let arr = [];</script>

<div class="ps-season movie">
	<div class="page-header">
		<?php $document = JFactory::getDocument();
		$renderer   = $document->loadRenderer('modules');
		$options    = array('style' => 'xhtml');
		$position   = 'film-page-header';
		echo $renderer->render($position, $options, null);?>
	</div>
	
	<div class="intro">
	
		<?php if(isset($this->h1_pattern) && !empty($this->h1_pattern)){?>
			<h1>
				<?php echo TvshowsHelperMovie::replacer($this->item, $this->h1_pattern);?>
			</h1>
		<?php } else {?>
			<h1><?php echo $this->item->title; ?></h1>
		<?php } ?>
		
		<?php if(isset($this->description_pattern) && !empty($this->description_pattern)){?>
			<p>
				<?php echo TvshowsHelperMovie::replacer($this->item, $this->description_pattern);?>
			</p>
		<?php } ?>

		<div class="season-info clearfix">
			<?php echo $this->loadTemplate('image');?>
			<?php echo $this->loadTemplate('info');?>
		</div>
	</div>
	
	<?php echo $this->loadTemplate('gallery');?>
	
	<?php echo $this->loadTemplate('desc');?>
	
	<?php echo $this->loadTemplate('neighbours');?>
	
	<?php echo $this->loadTemplate('links');?>
	
	<div class="comments">
		<?php switch($this->comments_type){
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
					$itemURL = JRoute::_(TvshowsHelperRoute::getMovieRoute($this->item->id, $this->item->alias));
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

<script src="/components/com_tvshows/assets/js/form.js"></script>
<script>
	var active_btn;
	
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
		
		jQuery('.download-cell button.main-btn').click(function(){
			if (jQuery(this).hasClass('with-play-icon')){
				var link = atob(arr[jQuery(this).attr('data-url')]);
				jQuery('#premium .preview-block').html('');
				jQuery('#premium .modal-title').text(jQuery(this).closest('[itemprop="episode"]').find('.episodes-title').find('.h3').text());
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
				return false;
			} else {
				var link = jQuery(this).data('link');
				if(typeof link != 'undefined' && link != ''){
					link= atob(link);
					var win = window.open(link, '_blank');
					win.focus();
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

		jQuery('#k2s_cc .btn, #tezfiles_com .btn, #publish2_me .btn, #turbobit_net .btn').click(function(){
			if (jQuery(this).closest('.modal-footer').find('input[type="checkbox"]').prop('checked')){
				localStorage.setItem('file-vip', true);
			}
			if (!jQuery(this).hasClass('vip-btn')){
				jQuery('#k2s_cc, #tezfiles_com, #publish2_me, #turbobit_net').modal('hide'); 		
			}
			var win = window.open($(this).attr('href'), '_blank');
			win.focus();
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