<?php defined('_JEXEC') or die;
header('X-Frame-Options: sameorigin');
header("X-XSS-Protection: 0");
$app  = JFactory::getApplication();
$doc  = JFactory::getDocument();
$view = $app->input->getCmd('view','', 'CMD');
$task = $app->input->getCmd('task','', 'CMD');
$layout = $app->input->getCmd('layout','', 'CMD');
$option   = $app->input->getCmd('option','', 'CMD');
$controller   = $app->input->getCmd('controller','', 'CMD');
$search   = $app->input->get('s','', 'STRING');
$templateparams= $app->getTemplate(true)->params;
$custom_js = $templateparams->get('custom_js', null);
$this->language = $doc->language;
$this->direction = $doc->direction;
JHtml::_('bootstrap.framework');
$doc->addStyleSheet($this->baseurl."/templates/".$this->template."/css/template.css");
$doc->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css");
jimport('joomla.environment.browser');
$browser = JBrowser::getInstance();
$browserType = $browser->getBrowser();
$browserVersion = $browser->getMajor();
if(($browserType == 'msie') && ($browserVersion >= 8)){
  $doc->addScript("https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.js");
  $doc->addScript("https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv-printshiv.min.js");
  $doc->addStyleSheet($this->baseurl."/templates/".$this->template."/css/style_ie.css");
}
$menu = $app->getMenu();
if ($menu->getActive() == $menu->getDefault()) {$class = "home-page";} else {$class = "";}
$pageclass = ''; 
if($doc->title == 404){$class .= ' p404';} 
if (is_object($menu->getActive())) {$pageclass = $menu->getActive()->params->get('pageclass_sfx');}
$client = $app->client;
$user = jFactory::getUser();
if($user->guest){
	$user_class = 'guest-user';
} else {
	$user_class = 'logged-user';
}?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(version_compare(JVERSION,"3.2.0","lt")){?>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
    <script type="text/javascript">jQuery.noConflict();</script>
    <?php } ?>
    <jdoc:include type="head" />
  <?php unset($doc->_scripts[JURI::root(true) . '/media/system/js/caption.js']);
  if(isset($this->_script['text/javascript'])){
    $this->_script['text/javascript'] = preg_replace('%jQuery\(window\).on\(\'load\',\s*function\(\)\s*\{\s*new\s*JCaption\(\'img.caption\'\)\;\s*\}\)\;\s*%', '', $this->_script['text/javascript']);
  }
  unset($doc->_scripts[JURI::root(true).'/media/jui/js/bootstrap.min.js']);
  $doc->setGenerator('');?>
  
	  <?php $body_font = $templateparams->get('body-font');
	  $body_background_color = $templateparams->get('body-background-color');
	  $body_font_color = $templateparams->get('body-font-color');
	  $body_link_color = $templateparams->get('body-link-color');
	  $body_link_hover_color = $templateparams->get('body-link-hover-color');
	  
	  $main_menu_background_color = $templateparams->get('main-menu-background-color');
	  $main_menu_font_color = $templateparams->get('main-menu-font-color');
	  $main_menu_hover_border_color = $templateparams->get('main-menu-hover-border-color');
	  
	  $header_search_background_color = $templateparams->get('header-search-background-color');
	  $header_search_font_color = $templateparams->get('header-search-font-color');
	  $header_search_result_border_color = $templateparams->get('header-search-result-border-color');
	  
	  $footer_background_color = $templateparams->get('footer-background-color');
	  $footer_menu_color = $templateparams->get('footer-menu-color');
	  $footer_menu_border_color = $templateparams->get('footer-menu-border-color');
	  $footer_menu_border_hover_color = $templateparams->get('footer-menu-border-hover-color');
	  $footer_font_color = $templateparams->get('footer-font-color');
	  $copy_link_color = $templateparams->get('copy-link-color');
	  $copy_link_hover_color = $templateparams->get('copy-link-hover-color');
	  
	  $bages_background_color = $templateparams->get('bages-background-color');
	  $main_btn_start_gradient_color = $templateparams->get('main-btn-start-gradient-color');
	  $main_btn_end_gradient_color = $templateparams->get('main-btn-end-gradient-color');
	  $scroll_color = $templateparams->get('scroll-color');
	  $calendar_active_day_background_color = $templateparams->get('calendar-active-day-background-color');
	  
	  $most_popular_module_background_color = $templateparams->get('most-popular-module-background-color');
	  $most_popular_module_title_color = $templateparams->get('most-popular-module-title-color');
	  $most_popular_module_border_color = $templateparams->get('most-popular-module-border-color');
	  $most_popular_module_active_color = $templateparams->get('most-popular-module-active-color');
	  
	  $sidebar_background_color = $templateparams->get('sidebar-background-color');
	  $sidebar_font_color = $templateparams->get('sidebar-font-color');
	  $sidebar_link_color = $templateparams->get('sidebar-link-color');
	  $sidebar_link_hover_color = $templateparams->get('sidebar-link-hover-color');
	  
	  $watch_and_download_module_background_color = $templateparams->get('watch-and-download-module-background-color');
	  $watch_and_download_module_font_color = $templateparams->get('watch-and-download-module-font-color');
	  
	  $horizontal_alphabet_module_background_color = $templateparams->get('horizontal-alphabet-module-background-color');
	  $horizontal_alphabet_module_title_color = $templateparams->get('horizontal-alphabet-module-title-color');
	  $horizontal_alphabet_module_results_background_color = $templateparams->get('horizontal-alphabet-module-results-background-color');
	  $horizontal_alphabet_module_results_link_color = $templateparams->get('horizontal-alphabet-module-results-link-color');
	  
	  $season_description_background_color = $templateparams->get('season-description-background-color');
	  $season_breadcrumbs_font_color = $templateparams->get('season-breadcrumbs-font-color');
	  $season_breadcrumbs_link_color = $templateparams->get('season-breadcrumbs-link-color');
	  $season_breadcrumbs_link_hover_color = $templateparams->get('season-breadcrumbs-link-hover-color');
	  $season_breadcrumbs_dots_color = $templateparams->get('season-breadcrumbs-dots-color');
	  $season_breadcrumbs_border_color = $templateparams->get('season-breadcrumbs-border-color');
	  $season_description_label_color = $templateparams->get('season-description-label-color');
	  $season_description_text_color = $templateparams->get('season-description-text-color');
	  $season_description_link_color = $templateparams->get('season-description-link-color');
	  $season_description_link_hover_color = $templateparams->get('season-description-link-hover-color');
	  $season_description_dividers_color = $templateparams->get('season-description-dividers-color');
	  $season_screencaps_dot_color = $templateparams->get('season-screencaps-dot-color');
	  $season_screencaps_dot_active_color = $templateparams->get('season-screencaps-dot-active-color');
  
	  $film_description_background_color = $templateparams->get('film-description-background-color');
	  $film_description_link_color = $templateparams->get('film-description-link-color');
	  $film_description_link_hover_color = $templateparams->get('film-description-link-hover-color');
	  $film_description_text_color = $templateparams->get('film-description-text-color');
	  $film_screencaps_title_color = $templateparams->get('film-screencaps-title-color');
	  $film_screencaps_title_border_color = $templateparams->get('film-screencaps-title-border-color');
	  $film_screencaps_dot_color = $templateparams->get('film-screencaps-dot-color');
	  $film_screencaps_dot_active_color = $templateparams->get('film-screencaps-dot-active-color');
  
    $list_tvshows_link_color = $templateparams->get('list-tvshows-link-color');
    $list_tvshows_link_hover_color = $templateparams->get('list-tvshows-link-hover-color');
    $list_tvshows_link_letter_color = $templateparams->get('list-tvshows-link-letter-color');
	$list_tvshows_link_letter_background_color = $templateparams->get('list-tvshows-link-letter-background-color');
	$list_tvshows_link_letter_border_color = $templateparams->get('list-tvshows-link-letter-border-color');
	$list_tvshows_link_letter_under_border_color = $templateparams->get('list-tvshows-link-letter-under-border-color');
  
    function hexToRgb($hex, $alpha = false) {
		$hex      = str_replace('#', '', $hex);
	   $length   = strlen($hex);
	   $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
	   $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
	   $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
	   if ( $alpha ) {
		  $rgb['a'] = $alpha;
	   }
	   return implode(',',$rgb);
    }?>
	
	<?php switch($body_font){
		case 'GothaPro':?>
			<style>
				@font-face{font-family:'GothaPro';src:url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProReg.eot);src:local('='),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProReg.eot) format('embedded-opentype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProReg.woff2) format('woff2'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProReg.woff) format('woff'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProReg.ttf) format('truetype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProReg.svg#GothaProReg) format('svg');font-style:normal;font-weight:normal}
				@font-face{font-family:'GothaPro';src:url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBol.eot);src:local('='),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBol.eot) format('embedded-opentype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBol.woff2) format('woff2'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBol.woff) format('woff'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBol.ttf) format('truetype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBol.svg#GothaProBol) format('svg');font-style:normal;font-weight:bold}
				@font-face{font-family:'GothaPro Black';src:url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBla.eot);src:local('='),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBla.eot) format('embedded-opentype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBla.woff2) format('woff2'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBla.woff) format('woff'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBla.ttf) format('truetype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/GothaProBla.svg#GothaProBla) format('svg');font-style:normal;font-weight:normal}
				@font-face{font-family:'Bariol';src:url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/Bariol_Bold.eot);src:local('='),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/Bariol_Bold.eot) format('embedded-opentype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/Bariol_Bold.woff2) format('woff2'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/Bariol_Bold.woff) format('woff'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/Bariol_Bold.ttf) format('truetype'),url(<?php echo $this->baseurl."/templates/".$this->template;?>/fonts/Bariol_Bold.svg#Bariol_Bold) format('svg');font-style:normal;font-weight:normal}
				body {font-family:'GothaPro',Arial,sans-serif;}
			</style>
			<?php break;
			
		case 'Roboto':?>
			<style>
				@import url('//fonts.googleapis.com/css?family=Roboto:400,400i,700&subset=cyrillic');
				body {font-family:'Roboto',Arial,sans-serif;}
				.moduletableblock-2 .module_title,.moduletableblock-1 .module_title,.moduletableside-4 .module_title,
					.moduletableside-3 .module_title,.moduletableside-2 .module_title,.moduletableside-1 .module_title,
					.moduletableblock-3 .module_title,h1, .h1,h2, .h2,h3, .h3,.ps-season .other-seasons-title
					{font-family:'Roboto',Arial,sans-serif !important;font-weight:bold !important;}
			</style>
			<?php break;
			
		case 'Open sans':?>
			<style>
				@import url('//fonts.googleapis.com/css?family=Open+Sans:400,400i,700&subset=cyrillic');
				body {font-family:'Open Sans',Arial,sans-serif;}
				.moduletableblock-2 .module_title,.moduletableblock-1 .module_title,.moduletableside-4 .module_title,
					.moduletableside-3 .module_title,.moduletableside-2 .module_title,.moduletableside-1 .module_title,
					.moduletableblock-3 .module_title,h1, .h1,h2, .h2,h3, .h3,.ps-season .other-seasons-title
					{font-family:'Open Sans',Arial,sans-serif !important;font-weight:bold !important;}
			</style>
			<?php break;
			
		case 'Montserrat':?>
			<style>
				@import url('//fonts.googleapis.com/css?family=Montserrat:400,400i,700&subset=cyrillic');
				body {font-family:'Montserrat',Arial,sans-serif;}
				.moduletableblock-2 .module_title,.moduletableblock-1 .module_title,.moduletableside-4 .module_title,
					.moduletableside-3 .module_title,.moduletableside-2 .module_title,.moduletableside-1 .module_title,
					.moduletableblock-3 .module_title,h1, .h1,h2, .h2,h3, .h3,.ps-season .other-seasons-title
					{font-family:'Montserrat',Arial,sans-serif !important;font-weight:bold !important;}
			</style>
			<?php break;
	}?>
	
	<style>
		body {background-color:<?php echo $body_background_color;?>;color:<?php echo $body_font_color;?>;}
		a,a:visited {color:<?php echo $body_link_color;?>;}
		a:hover,a:focus {color:<?php echo $body_link_hover_color;?>;}
		
		#header .header-table {background-color:<?php echo $main_menu_background_color;?>;}
		.moduletablemainmenu .nav > li > a {color:<?php echo $main_menu_font_color;?>;}
		.moduletablemainmenu .nav > li > a:after {border-color:<?php echo $main_menu_hover_border_color;?>;}
		.moduletableheader-search,#header:before,.moduletableheader-search .result,
			.moduletableheader-search .go-to-search
			{background-color:<?php echo $header_search_background_color;?>;}
		#mod-search-searchword::-moz-placeholder {color:<?php echo $header_search_font_color;?>;}
		#mod-search-searchword:-ms-input-placeholder {color:<?php echo $header_search_font_color;?>;}
		#mod-search-searchword::-webkit-input-placeholder {color:<?php echo $header_search_font_color;?>;}
		.moduletableheader-search .result {border-color:<?php echo $header_search_result_border_color;?>;}
		
		#footer .footer {background-color:<?php echo $footer_background_color;?>;}
		.moduletablefooter-menu .nav > li > a {color:<?php echo $footer_menu_color;?>;}
		.footer-1,.moduletablefooter-menu .nav > li {border-color:<?php echo $footer_menu_border_color;?>;}
		.moduletablefooter-menu .nav > li > a:after {border-color:<?php echo $footer_menu_border_hover_color;?>;}
		#footer .footer {color:<?php echo $footer_font_color;?>;}
		.moduletablecopy a {color:<?php echo $copy_link_color;?>;}
		.moduletablecopy a:hover {color:<?php echo $copy_link_hover_color;?>;}
		
		.moduletableblock-3 .card-date,.moduletableside-2 .bage span,.moduletableside-1 .bage span,
			.moduletableblock-3 .view-toggle-btn.active,.ps-season .other-seasons-items > .counter,
			.ps-film .inner .seasons-header .counter,.ps-film .intro .tags li[class*='tag-'],
			.moduletableblock-2 .alphabet > li.active .letter,.ps-season .ps-season-episodes .download-list > .badge,
			.ps-season .gallery .nav-tabs>li.active>a,.ps-season .gallery .nav-tabs>li.active>a:hover,
			.ps-season .gallery .nav-tabs>li.active>a:focus,.moduletableside-5 .keys > li.active > span
			{background-color:<?php echo $bages_background_color;?>;}
		.moduletableblock-2 .alphabet > li.active .letter,.moduletableblock-2 .result .left i,
			.moduletableblock-3 .view-toggle-btn.active,.moduletableblock-2 .alphabet > li .letter:hover,
			.ps-season .gallery .nav-tabs>li.active>a, .ps-season .gallery .nav-tabs>li.active>a:hover,
			.ps-season .gallery .nav-tabs>li.active>a:focus,.ps-season .gallery .nav > li > a,
			.moduletableside-5 .keys > li > span:hover,.moduletableside-5 .keys > li.active > span
			{border-color:<?php echo $bages_background_color;?>;}
		.moduletableblock-3 .view-toggle-btn:not(.active) span,.moduletableblock-2 .result .left i,
			.moduletableblock-2 .alphabet > li .letter:hover,
			.moduletableside-5 .keys > li > span:hover
			{color:<?php echo $bages_background_color;?>;}
		.moduletableblock-3 .card .download-icon,.moduletableblock-1 .card-image .download-icon,
			.ps-season .other-seasons-items .main-list .main-list-item .card .download-icon,
			.ps-film .inner .main-list .main-list-item .card-image .download-icon
			{background-color:<?php echo $bages_background_color;?>;background-color:rgba(<?php echo hexToRgb($bages_background_color,0.8);?>);}
		.ps-season .other-seasons-items .main-list .main-list-item .card:hover .download-icon,
			.ps-film .inner .main-list .main-list-item .card:hover .download-icon
			{background-color:<?php echo $bages_background_color;?>;}
		.moduletableblock-3 .card .go-to-season,.ps-film .intro .scroll-to-download,.ps-film .intro .custom-btn-1,
			.ps-film .inner .main-list .main-list-item .go-to-season
			{background:<?php echo $main_btn_start_gradient_color;?>;background: -moz-linear-gradient(top,<?php echo $main_btn_start_gradient_color;?> 0,<?php echo $main_btn_end_gradient_color;?> 100%);background: -webkit-linear-gradient(top,<?php echo $main_btn_start_gradient_color;?> 0,<?php echo $main_btn_end_gradient_color;?> 100%);background: linear-gradient(to bottom,<?php echo $main_btn_start_gradient_color;?> 0,<?php echo $main_btn_end_gradient_color;?> 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $main_btn_start_gradient_color;?>',endColorstr='<?php echo $main_btn_end_gradient_color;?>',GradientType=0);}
		.mCSB_dragger_bar,.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar 
			{background:<?php echo $scroll_color;?> !important;}
		.ps-season .other-seasons-items .main-list .main-list-item .go-to-season,
			.ps-season .season-image .scroll-to-download,.ps-season .ps-season-episodes .download-cell .main-btn
			{background:<?php echo $main_btn_start_gradient_color;?>;background: -moz-linear-gradient(top,<?php echo $main_btn_start_gradient_color;?> 0,<?php echo $main_btn_end_gradient_color;?> 100%);background: -webkit-linear-gradient(top,<?php echo $main_btn_start_gradient_color;?> 0,<?php echo $main_btn_end_gradient_color;?> 100%);background: linear-gradient(to bottom,<?php echo $main_btn_start_gradient_color;?> 0,<?php echo $main_btn_end_gradient_color;?> 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<?php echo $main_btn_start_gradient_color;?>',endColorstr='<?php echo $main_btn_end_gradient_color;?>',GradientType=0);}
			
		.moduletableblock-1 {background-color:<?php echo $most_popular_module_background_color;?>;}
		.moduletableblock-1 .module_title {color:<?php echo $most_popular_module_title_color;?>;border-color:<?php echo $most_popular_module_border_color;?>;}
		.moduletableblock-1 .navigation .swiper-button-prev, .moduletableblock-1 .navigation .swiper-button-next
			{border-color:<?php echo $most_popular_module_border_color;?>;}
		.moduletableblock-1 .navigation .swiper-pagination-bullet {background-color:<?php echo $most_popular_module_border_color;?>;}
		.moduletableblock-1 .navigation .swiper-pagination-bullet.swiper-pagination-bullet-active {background:<?php echo $most_popular_module_active_color;?>;}
		
		[class*='sidebar-'] {background-color:<?php echo $sidebar_background_color;?>;color:<?php echo $sidebar_font_color;?>;}
		[class*='sidebar-'] .module_title {color:<?php echo $sidebar_font_color;?>;}
		[class*='sidebar-'] a {color:<?php echo $sidebar_link_color;?>}
		[class*='sidebar-'] a:hover {color:<?php echo $sidebar_link_hover_color;?>;}
		
		.moduletableblock-4 {background-color:<?php echo $watch_and_download_module_background_color;?>;color:<?php echo $watch_and_download_module_font_color;?>;}
		.moduletableblock-4 h1,.moduletableblock-4 h2,.moduletableblock-4 h3 {color:<?php echo $watch_and_download_module_font_color;?>;}
		
		.moduletableblock-2 {background-color:<?php echo $horizontal_alphabet_module_background_color;?>;}
		.moduletableblock-2 .module_title {color:<?php echo $horizontal_alphabet_module_title_color;?>;}
		.moduletableblock-2 .result {background-color:<?php echo $horizontal_alphabet_module_results_background_color;?>;}
		.moduletableblock-2 .result .right .list li a {color:<?php echo $horizontal_alphabet_module_results_link_color;?>;}
		
		.ps-season .page-header,.ps-season .intro {background-color:<?php echo $season_description_background_color;?>;}
		.ps-season .intro,.ps-season .season-table-row .tags [class*='tag-'] {color:<?php echo $season_description_text_color;?>;}
		.ps-season .intro a,.ps-season .film-info a {color:<?php echo$season_description_link_color;?>;}
		.ps-season .intro a:hover,.ps-season .film-info a:hover {color:<?php echo$season_description_link_hover_color;?>;}
		.ps-season .season-table-row .strong,.ps-season .season-table-row .tags .title {color:<?php echo $season_description_label_color;?>;}
		.ps-season .season-table-row {border-color:<?php echo $season_description_dividers_color;?>;}
		.ps-season .gallery .navigation .swiper-pagination-bullet {background-color:<?php echo $season_screencaps_dot_color;?>}
		.ps-season .gallery .navigation .swiper-pagination-bullet.swiper-pagination-bullet-active {background-color:<?php echo $season_screencaps_dot_active_color;?>;}
		
		.ps-film .page-header,.ps-film .intro {background-color:<?php echo $film_description_background_color;?>;}
		.moduletablefilm-breadcrumbs ul li > span {color:<?php echo $season_breadcrumbs_font_color;?>;}
		.moduletablefilm-breadcrumbs ul li a {color:<?php echo $season_breadcrumbs_link_color;?>;border-color:rgba(<?php echo hexToRgb($season_breadcrumbs_link_color, 0.5);?>);}
		.moduletablefilm-breadcrumbs ul li a:hover {color:<?php echo $season_breadcrumbs_link_hover_color;?>;border-color:rgba(<?php echo hexToRgb($season_breadcrumbs_link_hover_color, 0.5);?>);}
		.moduletablefilm-breadcrumbs ul li:after {background-color:<?php echo $season_breadcrumbs_dots_color;?>;}
		.moduletablefilm-breadcrumbs {border-color:<?php echo $season_breadcrumbs_border_color;?>;}
		.ps-film .intro,.ps-film .intro .card-text {color:<?php echo $film_description_text_color;?>;}
		.ps-film .intro a {color:<?php echo $film_description_link_color;?>;}
		.ps-film .intro a:hover {color:<?php echo $film_description_link_hover_color;?>;}
		.ps-film .gallery > h3 {color:<?php echo $film_screencaps_title_color;?>;border-color:<?php echo $film_screencaps_title_border_color;?>;}
		.ps-film .gallery .navigation .swiper-pagination-bullet {background-color:<?php echo $film_screencaps_dot_color;?>;}
		.ps-film .gallery .navigation .swiper-pagination-bullet.swiper-pagination-bullet-active {background-color:<?php echo $film_screencaps_dot_active_color;?>;}
		
		[class*='ps_tvcalendar'] td.fc-today {background-color:rgba(<?php echo hexToRgb($calendar_active_day_background_color, 0.4);?>);}
		
		.ps-films .inner ul li:not(.key) a {color:<?php echo $list_tvshows_link_color;?>;}
		.ps-films .inner ul li:not(.key) a:hover {color:<?php echo $list_tvshows_link_hover_color;?>;}
		.ps-films .inner ul li.key {color:<?php echo $list_tvshows_link_letter_color;?>;background-color:<?php echo $list_tvshows_link_letter_background_color;?>;border-color:<?php echo $list_tvshows_link_letter_border_color;?>;}
		.ps-films .inner ul:before,.ps-films .inner ul li:not(.key):before {border-color:<?php echo $list_tvshows_link_letter_under_border_color;?>;}
	</style>
  
</head>
<body class="<?php echo $user_class.' '.$view.' '.$task.' '.$layout.' '.$option.' '.$class.' '.$pageclass.' '.$controller.' Itemid'.$menu->getActive()->id;?>">
    <div id="wrap">
		<?php if($client->mobile){?>
			<div id="top">
				<div class="row-fluid">
					<div id="side-panel-toggle"><div class="stripes"><div class="stripe"></div><div class="stripe"></div><div class="stripe"></div></div></div>
					<?php if($this->countModules('top-1')) { ?>
						<jdoc:include type="modules" name="top-1" style="html" />
					<?php } ?>
					<?php if($this->countModules('top-2')) { ?>
						<jdoc:include type="modules" name="top-2" style="html" />
					<?php } ?>
				</div>
			</div>
			<div id="side-panel">
				<?php if($this->countModules('side-panel')) { ?>
						<jdoc:include type="modules" name="side-panel" style="html" />
					<?php } ?>
			</div>
			<div id="side-panel-overlay"></div>
		<?php } else {}?>
		
		<?php if($this->countModules('header')) { ?>  
			<header id="header">
				<a href="#" class="menu-toggle"><span class="icon icon-list"></span></a>
				<a href="#" class="search-toggle"><span class="icon search2-icon"></span></a>
				<div class="wrapper"><div class="header-table"><jdoc:include type="modules" name="header" style="html" /></div></div>
			</header>
		<?php } ?>
		
		<?php if(isset($search) && !empty($search)){?>
		
		<?php } else {?>
			<?php if($this->countModules('slider')) { ?><jdoc:include type="modules" name="slider" style="full" /><?php } ?>
		<?php } ?>

      <div id="wrapper">          
        <div id="main">
			<?php if ($this->countModules('sideleft')) { ?>
				<div class="sidebar-left"><jdoc:include type="modules" name="sideleft" style="html" /></div>
			<?php } ?>

			<div id="content">
				<div class="inner">
					<?php if(isset($search) && !empty($search)){?>
						<jdoc:include type="modules" name="content-search-top" style="html" />
					<?php } else {?>
						<?php if($this->countModules('component-top')) { ?>
							<jdoc:include type="modules" name="component-top" style="tabs" />
						<?php } ?>
						
						<?php if($client->mobile){?>
							<jdoc:include type="modules" name="component-mobile" style="html" />
						<?php } ?>
						
						<?php if($this->countModules('component')) { ?>
							<jdoc:include type="modules" name="component" style="html" />
						<?php } ?>
					
						<jdoc:include type="message" />
						<jdoc:include type="component" />
						
						<div class="clear"></div>
						<?php if($this->countModules('component-bottom')) { ?>
							<jdoc:include type="modules" name="component-bottom" style="html" />
						<?php } ?>
					<?php } ?>
				</div>
			</div><!--end #content-->

			<?php if ($this->countModules('sideright') || $this->countModules('sideright-films') || $this->countModules('sideright-seasons')) { ?>
				<div class="sidebar-right">
					<?php if($view != 'film' && $view != 'season' && $this->countModules('sideright')){?>
						<jdoc:include type="modules" name="sideright" style="html" />
					<?php } ?>

					<?php if($option == 'com_tvshows' && $view == 'film' && $this->countModules('sideright-films')){?>
						<jdoc:include type="modules" name="sideright-films" style="html" />
					<?php } ?>
					
					<?php if($option == 'com_tvshows' && $view == 'season' && $this->countModules('sideright-seasons')){?>
						<jdoc:include type="modules" name="sideright-seasons" style="html" />
					<?php } ?>
				</div>
			<?php } ?>
        </div>        
      </div><!--end #wrapper--><div style="clear:both; "></div>
		
		<?php if(isset($search) && !empty($search)){?>
		
		<?php } else {?>
			<?php if($this->countModules('bottom')) { ?><div class="bottom"><div class="wrapper"><jdoc:include type="modules" name="bottom" style="html" /></div></div><?php } ?>
			<?php if($this->countModules('bottom-full')) { ?><jdoc:include type="modules" name="bottom-full" style="full" /><?php } ?>
		<?php } ?>
    </div>  
  
  <?php if ($this->countModules('footer-1') || $this->countModules('footer-2')) { ?>
      <footer id="footer">
        <div class="footer">
            <div class="footer-1"><div class="wrapper"><jdoc:include type="modules" name="footer-1" style="html" /></div></div>
            <div class="footer-2"><div class="wrapper"><jdoc:include type="modules" name="footer-2" style="html" /></div></div>
        </div><!--class footer-->
      </footer> <!--id footer-->
  <?php } ?>  

    <?php if ($this->countModules('hidden')){?>
    <jdoc:include type="modules" name="hidden" style="hidden" />
    <?php } ?>
	<jdoc:include type="modules" name="debug" />
    <script src="<?php echo $this->baseurl."/templates/".$this->template."/js/selectivizr-min.js";?>" ></script>
  <?php if(isset($custom_js) && !empty($custom_js)){echo $custom_js;}?>
  <script>
    /*jquery.matchHeight-min.js http://brm.io/jquery-match-height/ */
    (function(c){var n=-1,f=-1,g=function(a){return parseFloat(a)||0},r=function(a){var b=null,d=[];c(a).each(function(){var a=c(this),k=a.offset().top-g(a.css("margin-top")),l=0<d.length?d[d.length-1]:null;null===l?d.push(a):1>=Math.floor(Math.abs(b-k))?d[d.length-1]=l.add(a):d.push(a);b=k});return d},p=function(a){var b={byRow:!0,property:"height",target:null,remove:!1};if("object"===typeof a)return c.extend(b,a);"boolean"===typeof a?b.byRow=a:"remove"===a&&(b.remove=!0);return b},b=c.fn.matchHeight=
    function(a){a=p(a);if(a.remove){var e=this;this.css(a.property,"");c.each(b._groups,function(a,b){b.elements=b.elements.not(e)});return this}if(1>=this.length&&!a.target)return this;b._groups.push({elements:this,options:a});b._apply(this,a);return this};b._groups=[];b._throttle=80;b._maintainScroll=!1;b._beforeUpdate=null;b._afterUpdate=null;b._apply=function(a,e){var d=p(e),h=c(a),k=[h],l=c(window).scrollTop(),f=c("html").outerHeight(!0),m=h.parents().filter(":hidden");m.each(function(){var a=c(this);
    a.data("style-cache",a.attr("style"))});m.css("display","block");d.byRow&&!d.target&&(h.each(function(){var a=c(this),b=a.css("display");"inline-block"!==b&&"inline-flex"!==b&&(b="block");a.data("style-cache",a.attr("style"));a.css({display:b,"padding-top":"0","padding-bottom":"0","margin-top":"0","margin-bottom":"0","border-top-width":"0","border-bottom-width":"0",height:"100px"})}),k=r(h),h.each(function(){var a=c(this);a.attr("style",a.data("style-cache")||"")}));c.each(k,function(a,b){var e=c(b),
    f=0;if(d.target)f=d.target.outerHeight(!1);else{if(d.byRow&&1>=e.length){e.css(d.property,"");return}e.each(function(){var a=c(this),b=a.css("display");"inline-block"!==b&&"inline-flex"!==b&&(b="block");b={display:b};b[d.property]="";a.css(b);a.outerHeight(!1)>f&&(f=a.outerHeight(!1));a.css("display","")})}e.each(function(){var a=c(this),b=0;d.target&&a.is(d.target)||("border-box"!==a.css("box-sizing")&&(b+=g(a.css("border-top-width"))+g(a.css("border-bottom-width")),b+=g(a.css("padding-top"))+g(a.css("padding-bottom"))),
    a.css(d.property,f-b+"px"))})});m.each(function(){var a=c(this);a.attr("style",a.data("style-cache")||null)});b._maintainScroll&&c(window).scrollTop(l/f*c("html").outerHeight(!0));return this};b._applyDataApi=function(){var a={};c("[data-match-height], [data-mh]").each(function(){var b=c(this),d=b.attr("data-mh")||b.attr("data-match-height");a[d]=d in a?a[d].add(b):b});c.each(a,function(){this.matchHeight(!0)})};var q=function(a){b._beforeUpdate&&b._beforeUpdate(a,b._groups);c.each(b._groups,function(){b._apply(this.elements,
    this.options)});b._afterUpdate&&b._afterUpdate(a,b._groups)};b._update=function(a,e){if(e&&"resize"===e.type){var d=c(window).width();if(d===n)return;n=d}a?-1===f&&(f=setTimeout(function(){q(e);f=-1},b._throttle)):q(e)};c(b._applyDataApi);c(window).bind("load",function(a){b._update(!1,a)});c(window).bind("resize orientationchange",function(a){b._update(!0,a)})})(jQuery);
  </script>
  <!--<script src="<?php echo $this->baseurl."/templates/".$this->template."/js/tablesaw.js";?>" ></script>
  <script src="<?php echo $this->baseurl."/templates/".$this->template."/js/tablesaw-init.js";?>" ></script>-->
  <script type="text/javascript">
      (function(k){k.fn.animatescroll=function(h){h=k.extend({},k.fn.animatescroll.defaults,h);jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,a,c,b,d){return jQuery.easing[jQuery.easing.def](e,a,c,b,d)},easeInQuad:function(e,a,c,b,d){return b*(a/=d)*a+c},easeOutQuad:function(e,a,c,b,d){return-b*(a/=d)*(a-2)+c},easeInOutQuad:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a+c:-b/2*(--a*(a-2)-1)+c},easeInCubic:function(e,a,c,b,d){return b*(a/=d)*a*a+c},
      easeOutCubic:function(e,a,c,b,d){return b*((a=a/d-1)*a*a+1)+c},easeInOutCubic:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a+c:b/2*((a-=2)*a*a+2)+c},easeInQuart:function(e,a,c,b,d){return b*(a/=d)*a*a*a+c},easeOutQuart:function(e,a,c,b,d){return-b*((a=a/d-1)*a*a*a-1)+c},easeInOutQuart:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a*a+c:-b/2*((a-=2)*a*a*a-2)+c},easeInQuint:function(e,a,c,b,d){return b*(a/=d)*a*a*a*a+c},easeOutQuint:function(e,a,c,b,d){return b*((a=a/d-1)*a*a*a*a+1)+c},easeInOutQuint:function(e,
      a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a*a*a+c:b/2*((a-=2)*a*a*a*a+2)+c},easeInSine:function(e,a,c,b,d){return-b*Math.cos(a/d*(Math.PI/2))+b+c},easeOutSine:function(e,a,c,b,d){return b*Math.sin(a/d*(Math.PI/2))+c},easeInOutSine:function(e,a,c,b,d){return-b/2*(Math.cos(Math.PI*a/d)-1)+c},easeInExpo:function(e,a,c,b,d){return 0==a?c:b*Math.pow(2,10*(a/d-1))+c},easeOutExpo:function(e,a,c,b,d){return a==d?c+b:b*(-Math.pow(2,-10*a/d)+1)+c},easeInOutExpo:function(e,a,c,b,d){return 0==a?c:a==d?c+b:1>(a/=d/2)?
      b/2*Math.pow(2,10*(a-1))+c:b/2*(-Math.pow(2,-10*--a)+2)+c},easeInCirc:function(e,a,c,b,d){return-b*(Math.sqrt(1-(a/=d)*a)-1)+c},easeOutCirc:function(e,a,c,b,d){return b*Math.sqrt(1-(a=a/d-1)*a)+c},easeInOutCirc:function(e,a,c,b,d){return 1>(a/=d/2)?-b/2*(Math.sqrt(1-a*a)-1)+c:b/2*(Math.sqrt(1-(a-=2)*a)+1)+c},easeInElastic:function(e,a,c,b,d){e=1.70158;var f=0,g=b;if(0==a)return c;if(1==(a/=d))return c+b;f||(f=0.3*d);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return-(g*Math.pow(2,10*
      (a-=1))*Math.sin(2*(a*d-e)*Math.PI/f))+c},easeOutElastic:function(e,a,c,b,d){e=1.70158;var f=0,g=b;if(0==a)return c;if(1==(a/=d))return c+b;f||(f=0.3*d);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return g*Math.pow(2,-10*a)*Math.sin(2*(a*d-e)*Math.PI/f)+b+c},easeInOutElastic:function(e,a,c,b,d){e=1.70158;var f=0,g=b;if(0==a)return c;if(2==(a/=d/2))return c+b;f||(f=0.3*d*1.5);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return 1>a?-0.5*g*Math.pow(2,10*(a-=1))*Math.sin(2*(a*
      d-e)*Math.PI/f)+c:g*Math.pow(2,-10*(a-=1))*Math.sin(2*(a*d-e)*Math.PI/f)*0.5+b+c},easeInBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return b*(a/=d)*a*((f+1)*a-f)+c},easeOutBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return b*((a=a/d-1)*a*((f+1)*a+f)+1)+c},easeInOutBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return 1>(a/=d/2)?b/2*a*a*(((f*=1.525)+1)*a-f)+c:b/2*((a-=2)*a*(((f*=1.525)+1)*a+f)+2)+c},easeInBounce:function(e,a,c,b,d){return b-jQuery.easing.easeOutBounce(e,d-a,0,b,d)+c},
      easeOutBounce:function(e,a,c,b,d){return(a/=d)<1/2.75?7.5625*b*a*a+c:a<2/2.75?b*(7.5625*(a-=1.5/2.75)*a+0.75)+c:a<2.5/2.75?b*(7.5625*(a-=2.25/2.75)*a+0.9375)+c:b*(7.5625*(a-=2.625/2.75)*a+0.984375)+c},easeInOutBounce:function(e,a,c,b,d){return a<d/2?0.5*jQuery.easing.easeInBounce(e,2*a,0,b,d)+c:0.5*jQuery.easing.easeOutBounce(e,2*a-d,0,b,d)+0.5*b+c}});if("html,body"==h.element){var l=this.offset().top;k(h.element).animate({scrollTop:l-h.padding-100},h.scrollSpeed,h.easing)}else k(h.element).animate({scrollTop:this.offset().top-
      this.parent().offset().top+this.parent().scrollTop()-h.padding},h.scrollSpeed,h.easing)};k.fn.animatescroll.defaults={easing:"swing",scrollSpeed:800,'padding':0,element:"html,body"}})(jQuery);
    </script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
  <script src="<?php echo $this->baseurl."/templates/".$this->template."/js/script.js";?>" ></script>
</body><!--end body-->
</html>