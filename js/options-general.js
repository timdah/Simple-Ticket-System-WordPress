jQuery(document).ready(function() {
	// Ladeanimation
	jQuery(document)
	  .ajaxStart(function () {
		jQuery('.three-quarters-loader').css('visibility', 'visible');
	  })
	  .ajaxStop(function () {
		jQuery('.three-quarters-loader').css('visibility', 'hidden');
	  });  
});

function addChanges() {
	var opt1 = jQuery('#add_opt1').val();
	var opt2 = jQuery('#add_opt2').val();
	var opt3 = jQuery('#add_opt3').val();
	var datepicker = jQuery('#add_datepicker').val();

	var data = {
	'action': 'SAVE',
	'opt1': opt1,
	'opt2': opt2,
	'opt3': opt3,
	'datepicker': datepicker,
	};
	
	jQuery.post(ajax_object.ajax_url, data, function() {
		var data2 = {'action': 'GENERAL_LOAD'};
		jQuery.post(ajax_object.ajax_url, data2, function(response) {
			var final_data = jQuery(response).find('#ajax_form').html();
			jQuery('#ajax_form').html(final_data);
		});
	});
}