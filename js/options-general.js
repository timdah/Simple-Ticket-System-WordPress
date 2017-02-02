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
	var problem = jQuery('#add_problem').val();
	var opt1 = jQuery('#add_opt1').val();
	var opt2 = jQuery('#add_opt2').val();
	var opt3 = jQuery('#add_opt3').val();
	var datepicker = jQuery('#add_datepicker').val();
	var take = 'NULL';
	var done = 'NULL';
	var answer = 'NULL';
	var link_mail = jQuery('input[name=link_mail]:checked').val();
	if(jQuery('#mail_enable').has('div').length != 0) {
		take = jQuery('#take').val();
		done = jQuery('#done').val();
		answer = jQuery('#answer').val();
	}
	
	if(checkText(problem)) {
		var data = {
		'action': 'SAVE',
		'problem': problem,
		'opt1': opt1,
		'opt2': opt2,
		'opt3': opt3,
		'datepicker': datepicker,
		'take': take,
		'done': done,
		'answer': answer,
		'link_mail': link_mail
		};
		
		jQuery.post(ajax_object.ajax_url, data, function() {
			var data2 = {'action': 'GENERAL_LOAD'};
			jQuery.post(ajax_object.ajax_url, data2, function(response) {
				var final_data = jQuery(response).find('#ts_content').html();
				jQuery('#ts_content').html(final_data);
			});
		});
	}
}

function activeDeactive() {
	var data = {
	'action': 'MAIL_ACDE'
	};
	
	jQuery.post(ajax_object.ajax_url, data, function() {
		var data2 = {'action': 'GENERAL_LOAD'};
		jQuery.post(ajax_object.ajax_url, data2, function(response) {
			var final_data = jQuery(response).find('#mail').html();
			jQuery('#mail').html(final_data);
		});
	});
}

function testMail() {
	var data = {
	'action': 'TEST_MAIL'
	};
	
	jQuery.post(ajax_object.ajax_url, data);
}

function checkText(string) {
	if (/\S/.test(string)) {
		// string is not empty and not just whitespace
		return true;
	} else {
		alert("Invalid Input");
		return false;
	}
}