const typeHandle = () => {
	let order_type = jQuery('select#jform_params_order_type option:selected').val();
	if(order_type == 1){
		jQuery('select#jform_params_seasons').parents('.control-group').hide();
		jQuery('select#jform_params_films').parents('.control-group').hide();
	} else {
		jQuery('select#jform_params_seasons').parents('.control-group').show();
		jQuery('select#jform_params_films').parents('.control-group').show();
	}
}

jQuery(document).ready(function(){
	typeHandle();
	
	jQuery('select#jform_params_order_type').on('change', function(){
		typeHandle();
	});
});