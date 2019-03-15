<?php defined('_JEXEC') or die;
$list = ModTvalphabetHelper::getList($params);
$alphabet = array();
foreach ($list as $k => $v) {
	$f_char = substr($v->title, 0, 1);
	if(is_numeric($f_char)){
		$f_char = '0-9';
	}
	if(!isset($alphabet[strtolower($f_char)])){
		$alphabet[strtolower($f_char)] = array();
	}
	$alphabet[strtolower($f_char)][] = $v;
}
//echo '<pre>';var_dump($alphabet);?>

<div class="ps_tvshows_alphabet<?php echo $moduleclass_sfx; ?>" id="m<?php echo $module->id;?>">
	<?php if(count($alphabet)){?>
		<ul class="keys">
			<?php foreach ($alphabet as $key => $value) {?>
				<li class="key"><span><?php echo $key;?></span></li>
			<?php } ?>
		</ul>
		
		<?php foreach ($alphabet as $k => $v) {?>
			<div class="result hide">
				<ul class="values" data-key="<?php echo $k;?>">
					<?php foreach($v as $item){?>
						<li>
							<a href="<?php echo TvshowsHelperRoute::getFilmRoute(null, $item->alias);?>"><?php echo $item->title; ?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
	<?php } ?>
</div>

<script>
	let module = jQuery('#m<?php echo $module->id;?>');
	
	jQuery('document').ready(function(){
		jQuery('#m<?php echo $module->id;?> .key').on('click',function(){
			jQuery(this).siblings().removeClass('active');
			jQuery(this).addClass('active');
			let key = jQuery(this).text();
			jQuery('#m<?php echo $module->id;?> .result').addClass('hide');
			jQuery('#m<?php echo $module->id;?> .values[data-key="'+key.toString()+'"]').parents('.result').removeClass('hide');
		});
		
		jQuery('.moduletable<?php echo $moduleclass_sfx; ?> .result').mCustomScrollbar({
			autoHideScrollbar: false,
			scrollInertia: 700,
			scrollEasing: 'linear',
			mouseWheel: {preventDefault: true,scrollAmount: 300,deltaFactor: 100 },
		}); 
	});
</script>