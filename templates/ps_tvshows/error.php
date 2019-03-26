<?php defined('_JEXEC') or die;
$app = JFactory::getApplication();
$client = $app->client;
if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

if($this->error->getCode() == '404'){
	header('HTTP/1.0 404 Not Found');
	header("Content-Type: text/html; charset=utf-8");
}?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
	<title>404 Страница не найдена</title>
	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
    <script type="text/javascript">jQuery.noConflict();</script>
	<link rel="stylesheet" href="/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
	<!--<link rel="stylesheet" href="/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap-responsive.css" type="text/css" />-->
</head>

<body class="p404">
    <div id="wrap"> 
		<header id="header">
			<a href="#" class="menu-toggle"><span class="icon icon-list"></span></a>
			<a href="#" class="search-toggle"><span class="icon search2-icon"></span></a>
			<div class="wrapper">
				<div class="header-table">
					<?php echo $doc->getBuffer('modules', 'header', array('style' => 'html')); ?>
				</div>
			</div>
		</header>
		
		<?php echo $doc->getBuffer('modules', 'slider', array('style' => 'html')); ?>

		<div id="wrapper">          
			<div id="main">
				<div class="container">
					<div class="row-fluid">
						<div id="content">	
							<div class="inner">
								<p class="heading-1">404</p>
								<p class="heading-2">Эта страница не найдена</p>
								<p class="heading-3">или не существует</p>
								<p class="heading-4"><a href="/" class="main-btn">Перейти на главную страницу</a></p>
							</div>
						</div><!--end #content-->
					</div>
				</div>
			</div>        
		</div><!--end #wrapper-->
		<div style="clear:both; "></div>

		<?php echo $doc->getBuffer('modules', 'bottom', array('style' => 'html')); ?>

		<?php echo $doc->getBuffer('modules', 'bottom-full', array('style' => 'full')); ?>
	</div>  
  
    <footer id="footer">
		<div class="footer">
			<div class="footer-1">
				<div class="wrapper"><?php echo $doc->getBuffer('modules', 'footer-1', array('style' => 'html')); ?></div>
			</div>
			<div class="footer-2">
				<div class="wrapper"><?php echo $doc->getBuffer('modules', 'footer-2', array('style' => 'html')); ?></div>
			</div>
		</div><!--class footer-->
    </footer> <!--id footer-->

    <?php echo $doc->getBuffer('modules', 'hidden', array('style' => 'hidden')); ?>
	
	<script>
    /*jquery.matchHeight-min.js http://brm.io/jquery-match-height/ */
    (function(c){var n=-1,f=-1,g=function(a){return parseFloat(a)||0},r=function(a){var b=null,d=[];c(a).each(function(){var a=c(this),k=a.offset().top-g(a.css("margin-top")),l=0<d.length?d[d.length-1]:null;null===l?d.push(a):1>=Math.floor(Math.abs(b-k))?d[d.length-1]=l.add(a):d.push(a);b=k});return d},p=function(a){var b={byRow:!0,property:"height",target:null,remove:!1};if("object"===typeof a)return c.extend(b,a);"boolean"===typeof a?b.byRow=a:"remove"===a&&(b.remove=!0);return b},b=c.fn.matchHeight=
    function(a){a=p(a);if(a.remove){var e=this;this.css(a.property,"");c.each(b._groups,function(a,b){b.elements=b.elements.not(e)});return this}if(1>=this.length&&!a.target)return this;b._groups.push({elements:this,options:a});b._apply(this,a);return this};b._groups=[];b._throttle=80;b._maintainScroll=!1;b._beforeUpdate=null;b._afterUpdate=null;b._apply=function(a,e){var d=p(e),h=c(a),k=[h],l=c(window).scrollTop(),f=c("html").outerHeight(!0),m=h.parents().filter(":hidden");m.each(function(){var a=c(this);
    a.data("style-cache",a.attr("style"))});m.css("display","block");d.byRow&&!d.target&&(h.each(function(){var a=c(this),b=a.css("display");"inline-block"!==b&&"inline-flex"!==b&&(b="block");a.data("style-cache",a.attr("style"));a.css({display:b,"padding-top":"0","padding-bottom":"0","margin-top":"0","margin-bottom":"0","border-top-width":"0","border-bottom-width":"0",height:"100px"})}),k=r(h),h.each(function(){var a=c(this);a.attr("style",a.data("style-cache")||"")}));c.each(k,function(a,b){var e=c(b),
    f=0;if(d.target)f=d.target.outerHeight(!1);else{if(d.byRow&&1>=e.length){e.css(d.property,"");return}e.each(function(){var a=c(this),b=a.css("display");"inline-block"!==b&&"inline-flex"!==b&&(b="block");b={display:b};b[d.property]="";a.css(b);a.outerHeight(!1)>f&&(f=a.outerHeight(!1));a.css("display","")})}e.each(function(){var a=c(this),b=0;d.target&&a.is(d.target)||("border-box"!==a.css("box-sizing")&&(b+=g(a.css("border-top-width"))+g(a.css("border-bottom-width")),b+=g(a.css("padding-top"))+g(a.css("padding-bottom"))),
    a.css(d.property,f-b+"px"))})});m.each(function(){var a=c(this);a.attr("style",a.data("style-cache")||null)});b._maintainScroll&&c(window).scrollTop(l/f*c("html").outerHeight(!0));return this};b._applyDataApi=function(){var a={};c("[data-match-height], [data-mh]").each(function(){var b=c(this),d=b.attr("data-mh")||b.attr("data-match-height");a[d]=d in a?a[d].add(b):b});c.each(a,function(){this.matchHeight(!0)})};var q=function(a){b._beforeUpdate&&b._beforeUpdate(a,b._groups);c.each(b._groups,function(){b._apply(this.elements,
    this.options)});b._afterUpdate&&b._afterUpdate(a,b._groups)};b._update=function(a,e){if(e&&"resize"===e.type){var d=c(window).width();if(d===n)return;n=d}a?-1===f&&(f=setTimeout(function(){q(e);f=-1},b._throttle)):q(e)};c(b._applyDataApi);c(window).bind("load",function(a){b._update(!1,a)});c(window).bind("resize orientationchange",function(a){b._update(!0,a)})})(jQuery);
    </script>
	<script>

	</script>
</body><!--end body-->
</html>