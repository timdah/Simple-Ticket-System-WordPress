jQuery(document).ready(function() {
	// Ladeanimation
	jQuery(document)
	  .ajaxStart(function () {
		jQuery('#ts_load').css('visibility', 'visible');
	  })
	  .ajaxStop(function () {
		jQuery('#ts_load').css('visibility', 'hidden');
	  });  
});

function submitTicket() {
	var name = jQuery('#add_name').val();
	var mail = jQuery('#add_mail').val();
	var problem = jQuery('#add_problem').val();
	var status = jQuery('input[name=status]:checked').val();
	var telefon = jQuery('#add_telefon').val();
	var raum = jQuery('#add_raum').val();
	var rechner = jQuery('#add_rechner').val();
	if(jQuery('#add_termin').length) {
		var termin = jQuery('#add_termin').val();
	} else {var termin = '';}
	
	var data = {
	'action': 'MAIL',
	'name':name, 
	'mail':mail, 
	'problem':problem, 
	'status':status, 
	'telefon':telefon, 
	'raum':raum, 
	'rechner':rechner, 
	'termin':termin
	};

	jQuery.post(ajax_object.ajax_url, data, function(response) {
		jQuery('#ajax').html(response);
	});
}

function back() {
	var data = {'action': 'FORM'};
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		var final_data = $(response).find('#ajax').html();
		jQuery('#ajax').html(final_data);
	});
}