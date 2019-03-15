<?php defined('_JEXEC') or die;
$list = ModTvalphabetHelper::getList($params);?>

<div class="ps_tvshows_alphabet<?php echo $moduleclass_sfx; ?>" id="m<?php echo $module->id;?>">
	<?php if(count($list)){?>
		<ul>
			<?php $key = '';
			foreach ($list as $item) {
				$f_char = substr($item->title, 0, 1);
				
				if(is_numeric($f_char)){
					if($key != '0-9'){
						$key = '0-9';?>
						<li class="key">0-9</li>
					<?php }
				} else {
					if($key != strtolower($f_char) || empty($key)){
						$key = strtolower($f_char);?>
						<li class="key"><?php echo $f_char;?></li>
					<?php } 
				}?>

				<li class="hide">
					<a href="<?php echo TvshowsHelperRoute::getFilmRoute(null, $item->alias);?>"><?php echo $item->title; ?></a>
				</li>

			<?php } ?>
		</ul>
	<?php } ?>
</div>

<script>
	let module = jQuery('#m<?php echo $module->id;?>');
	
	jQuery('document').ready(function(){
		jQuery('#m<?php echo $module->id;?> .key').on('click',function(){
			jQuery('#m<?php echo $module->id;?> li:not(.key)').addClass('hide');
			jQuery(this).nextUntil('.key').removeClass('hide');
		});
	});
</script>