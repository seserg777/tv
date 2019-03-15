<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Including fallback code for the placeholder attribute in the search field.
JHtml::_('jquery.framework');
JHtml::_('script', 'system/html5fallback.js', false, true);

if ($width)
{
	$moduleclass_sfx .= ' ' . 'mod_search' . $module->id;
	$css = 'div.mod_search' . $module->id . ' input[type="search"]{ width:auto; }';
	JFactory::getDocument()->addStyleDeclaration($css);
	$width = ' size="' . $width . '"';
}
else
{
	$width = '';
}
?>
<div class="search<?php echo $moduleclass_sfx ?>">
	<form action="<?php echo JRoute::_('index.php');?>" method="post" class="form-inline">
		<?php
			//$output = '<label for="mod-search-searchword" class="element-invisible">' . $label . '</label> ';
			$output = '';
			$output .= '<input name="searchword" id="mod-search-searchword" maxlength="' . $maxlength . '"  class="inputbox search-query" autocomplete="off" type="search"' . $width;
			$output .= ' placeholder="' . $text . '" />';
			//$output .= ' />';

			$output .= '<div class="search-hover"></div>';
			
			if ($button) :
				if ($imagebutton) :
					$btn_output = ' <input type="image" alt="' . $button_text . '" class="button" src="' . $img . '" onclick="this.form.searchword.focus();"/>';
				else :
					$btn_output = ' <button class="button btn btn-primary" onclick="this.form.searchword.focus();">' . $button_text . '</button>';
				endif;

				switch ($button_pos) :
					case 'top' :
						$output = $btn_output . '<br />' . $output;
						break;

					case 'bottom' :
						$output .= '<br />' . $btn_output;
						break;

					case 'right' :
						$output .= $btn_output;
						break;

					case 'left' :
					default :
						$output = $btn_output . $output;
						break;
				endswitch;
			endif;

			echo $output;
		?>
		<input type="hidden" name="task" value="search" />
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
	</form>
	<div class="result hide"><ul></ul></div>
	<div class="go-to-search hide"><a href="#"><?php echo JText::_('MOD_SEARCH_ALL_RESULTS');?></a></div>
</div>

<script src="/modules/mod_tvalphabet/assets/js/jquery.mCustomScrollbar.js"></script>
<script>
	function find(word){
		word = word.toString();
		if(word.length > 2){
			var jqXhrS = jQuery.getJSON("/index.php?option=com_tvshows&task=seasons.search&search="+word);
			var jqXhrM = jQuery.getJSON("/index.php?option=com_tvshows&task=movies.search&search="+word);
		
			jqXhrS
			.done(function(data) {
				var list = [];
				var module = jQuery('.searchheader-search');
				
				jqXhrM.done(function(r) {
					if(typeof r.data.list != 'undefined' && r.data.list.length){
						list = [...r.data.list];
					}
					
					if(typeof data.data.list != 'undefined' && data.data.list.length){
						list = [...data.data.list];
					}
					
					console.log(list);
						
					var width = (jQuery('body').width() - jQuery('#header > .wrapper').width())/2 + jQuery('.moduletableheader-search').width() - 8;
						
					module.find('.result').width(width);
					module.find('.go-to-search').width(width).find('a').attr('href', '/?s='+word);
						
					//.find('ul').append('<div class="go-to-search" style="width:'+width+'px;"><a href="/?s='+word+'">All results</a></div>');
						
					for (var i in list) {
						var row = `<li><a href='${list[i].link}'><div class='img' style='background-image:url(${list[i].image});'></div><h3>${list[i].title}`;
						if(typeof list[i].film_title != 'undefined'){
							row += ` ${list[i].film_title}`;
						}
						row += `</h3></a></li>`;
						module.find('.result').find('ul').append(row);
					}					
						
					module.find('.result').mCustomScrollbar({
						autoHideScrollbar: false,
						scrollInertia: 700,
						scrollEasing: 'linear',
						mouseWheel: {preventDefault: true,scrollAmount: 300,deltaFactor: 100 },
					}); 
				})			
			})
			.fail(function(xhr) {
				console.log('error common back', xhr);
			});
		}
	}
	
	jQuery(document).ready(function(){
		jQuery('#mod-search-searchword').on('change keyup paste',function(){
			//jQuery('.searchheader-search').find('.result,.go-to-search').remove();
			jQuery('.searchheader-search').find('.result').removeClass('hide');
			jQuery('.searchheader-search').find('.go-to-search').removeClass('hide');
			jQuery('.searchheader-search').find('.result ul').html('');
			find(jQuery(this).val());
		});
		
		jQuery('.moduletableheader-search').mouseleave(function() {
			//jQuery(this).find('.result,.go-to-search').remove();
			jQuery('.searchheader-search').find('.result').addClass('hide');
			jQuery('.searchheader-search').find('.go-to-search').addClass('hide');
			jQuery('.searchheader-search').find('.result ul').html('');
			jQuery('#mod-search-searchword').val('');
		});
	});
</script>