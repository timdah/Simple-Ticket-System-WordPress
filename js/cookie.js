function setcookie(user) {
	var data = {'action': 'COOKIE', 'user': user};
	jQuery.post(ajax_object.ajax_url, data, function() {
		var data2 = {'action': 'SYSTEM'};
		jQuery.post(ajax_object.ajax_url, data2, function(response) {
			var final_data = jQuery(response).filter('#ts_content').html();
			jQuery('#ts_content').html(final_data);
		});
	});
}