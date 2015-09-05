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

function addUser() {
	var username = jQuery('#add_username').val();
	var passwort = jQuery('#add_passwd').val();
	var passwort2 = jQuery('#add_passwd2').val();
	var mail = jQuery('#add_mail').val();
	var anrede = 0 ;//jQuery('input[name=radio]:checked').val();
	var admin = 0;
	admin = jQuery('input[name=admin]:checked').val();
	var name = jQuery('#add_lastname').val();
	
	var data = {
	'action': 'ADD',
	'username': username,
	'passwort': passwort,
	'passwort2': passwort2,
	'mail': mail,
	'anrede': anrede,
	'name': name,
	'admin': admin
	};
	
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		jQuery('#ajax_form').html(response);
		var data2 = {'action': 'USERS'};
		jQuery.post(ajax_object.ajax_url, data2, function(response2) {
			var final_data = jQuery(response2).find('#ajax_users').html();
			jQuery('#ajax_users').html(final_data);
		});
	});
}

function back() {
	var data = {'action': 'USERS'};

	jQuery.post(ajax_object.ajax_url, data, function(response) {
		var final_data = jQuery(response).find('#ajax_form').html();
		jQuery('#ajax_form').html(final_data);
	});
}

function deleteUser(id) {
	var data = {
	'action': 'DELETE',
	'id': id
	};
	
	jQuery.post(ajax_object.ajax_url, data, function() {
		var data = {'action': 'USERS'};
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			var final_data = jQuery(response).find('#ajax_users').html();
			jQuery('#ajax_users').html(final_data);
		});
	});	
}