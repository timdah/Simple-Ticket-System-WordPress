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

function checkText(string) {
	if (/\S/.test(string)) {
		// string is not empty and not just whitespace
		return true;
	} else {
		alert("Invalid Input");
		return false;
	}
}

function submitTicket() {
	if(jQuery('#rcheck').val() != ''){return;}
	
	var url = window.location.href;
	var name = jQuery('#add_name').val();
	var mail = jQuery('#add_mail').val();
	var title = jQuery('#add_title').val();
	var problem = jQuery('#add_problem').val();
	var status = jQuery('input[name=status]:checked').val();
	var telefon = jQuery('#add_telefon').val();
	var raum = jQuery('#add_raum').val();
	var rechner = jQuery('#add_rechner').val();
	if(jQuery('#add_termin').length) {
		var termin = jQuery('#add_termin').val();
	} else {var termin = '';}
	
	if(checkText(name) && checkText(mail) && checkText(title) && checkText(problem)) {
	
		var data = {
		'action': 'MAIL',
		'url':url,
		'name':name, 
		'mail':mail,
		'title':title,
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
}

function back() {
	var data = {'action': 'FORM'};
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		var final_data = jQuery(response).find('#ajax').html();
		jQuery('#ajax').html(final_data);
	});
}

// Aktuallisierung des Textes im Textfeld im Eintragebereich
function textarea(id) {
	var option;
	jQuery(document).ready(function() {
		jQuery('#'+id+' .select_2').on('change', function() {
			option = this.value;
			var data = {
			'action': 'UPDATE',
			'id': id,
			'option': option,
			'what': 'load'
			};
			jQuery.post(ajax_object.ajax_url, data, function(response) {
				jQuery('#'+id+' .textarea').html(response);
			});	
		});
	});
}

// Eintragen von Lösung/Bemerkung/Termin/Bearbeiter des Tickets
function update(id) {
	var select = jQuery('#'+id).find('.select_2').val();
	var text = jQuery('#'+id).find('.update_text').val();
	if(select == 'antwort') {
		answer(id, text);
		return;
	}
	if(select != 'bearbeiter') {
		var data = {
		'action': 'UPDATE',
		'id': id,
		'select': select,
		'text': text
		};
		
		jQuery.post(ajax_object.ajax_url, data, function() {
			var data2 = {
			'action': 'AJAX',
			'id': id,
			'what': ''
			};
			jQuery.post(ajax_object.ajax_url, data2, function(response) {
				var final_data = jQuery(response).find('.ticket').html();
				jQuery('#'+id).children('.ticket').html(final_data);
				textarea(id);
			});
		});
	}
}

function answer(id, text) {
		
	var data = {
	'action': 'ANSWER',
	'id': id,
	'text': text
	};
		
	jQuery.post(ajax_object.ajax_url, data, function() {
		var data2 = {
		'action': 'SHOW',
		'id': id
		};
		jQuery.post(ajax_object.ajax_url, data2, function(response) {
			var final_data = jQuery(response).find('.answers').html();
			jQuery('#'+id).children('.answers').html(final_data);
			textarea(id);
		});
	});	
}