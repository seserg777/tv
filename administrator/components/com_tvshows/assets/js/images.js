function selectImg(from, path){
	let this_id = jQuery(from).attr('id');
	SqueezeBox.open(path+'index.php?option=com_media&view=images&tmpl=component&fieldid='+this_id+'&asset=&author=', {handler: 'iframe'});
}

function uploadFilmimage () {
	console.log('uploadFilmimage');

	let img_path = jQuery('#jform_img_path').val();

	/*if(jQuery('.item-images li').length){
		key = jQuery('.item-images li').last().data('image-id').toInt() + 1;
	}*/
	let indexes = [];
	jQuery('.item-images li').each(function(){
		indexes.push(parseInt(jQuery(this).data('image-id')));
	});

	const max = parseInt(Math.max.apply(null, indexes));
	let key = max + 1;
	if(isNaN(key)){
		key = 0;
	}

	let shadow_input = 	`<input type="hidden" name="jform[images][${key}]" value="${img_path}"/>`;

	jQuery('#jform_img_path').after(shadow_input);

	Joomla.submitbutton('film.apply');
}

function chooseFilmimage () {
	console.log('chooseFilmimage');
}

jQuery(document).ready(function() {
	jQuery('#jform_img_path').on('change', function(){ uploadFilmimage(); });

	jQuery('#jform_img_path').on('paste', function(){ chooseFilmimage(); });

	jQuery('.item-images li .icon-trash').on('click', function(){ 
		jQuery(this).parents('li').remove();

		Joomla.submitbutton('film.apply');
	});
});