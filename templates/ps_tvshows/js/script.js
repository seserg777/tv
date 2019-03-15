function loading_play(elem){
    elem.append('<div class="stuff-to-preload"><div class="preloader"><div class="ball ball-a"></div><div class="ball ball-b"></div><div class="ball ball-c"></div><div class="ball ball-d"></div></div></div>');

}
function loading_stop(elem){
    elem.find('.stuff-to-preload').remove();
}

jQuery(document).ready(function(){	
	jQuery('.menu-toggle').click(function(){
		jQuery('body').addClass('menu-opened');
		return false;
	});
	jQuery('.menu-close').click(function(){
		jQuery('body').removeClass('menu-opened');
		return false;
	});
	jQuery('.search-toggle').click(function(){
		jQuery('body').toggleClass('search-opened');
		return false;
	});
	jQuery('.moduletableblock-2 .module_title').click(function(){
		jQuery(this).parents('.moduletableblock-2').toggleClass('active');
	});
	jQuery('.moduletableside-3 .module_title').click(function(){
		jQuery(this).parents('.moduletableside-3').toggleClass('active');
	});
	jQuery('.moduletableside-5 .module_title').click(function(){
		jQuery(this).parents('.moduletableside-5').toggleClass('active');
	});
  
	jQuery('#side-panel-toggle').on('click', function(){
		jQuery('#side-panel').toggleClass('active');
		jQuery('#side-panel-overlay').toggleClass('active');
	});
	
	jQuery('#side-panel-overlay').on('click', function(){
		jQuery('#side-panel').removeClass('active');
		jQuery('#side-panel-overlay').removeClass('active');
	});
});

jQuery(window).load(function(){
	/*jQuery('.fancybox').fancybox({
		autoSize:false,
		//autoWidth:true,
		autoHeight:true,
		width:940,
		padding:0,
		helpers:{overlay:{locked:false}},
		tpl:{
			wrap:'<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div><img src="/images/modal.png" /></div></div></div>'
		}
	});*/
});