jQuery(document).ready(function() {
	// Ladeanimation
	jQuery(document)
	  .ajaxStart(function () {
		jQuery('#ts_load').css('visibility', 'visible');
	  })
	  .ajaxStop(function () {
		jQuery('#ts_load').css('visibility', 'hidden');
	  });
	
	// automatische Aktuallisierung stoppen wenn in Textfeld
	jQuery("input, textarea").on("focus", function()
	{
		stopInterval();
	});
	if(bCheck == 1)
	{
		jQuery("input, textarea").on("focusout", function()
		{
			startInterval();
		});	
	}
	  
});
var bCheck = 0;

// Globale Variablen
var url = window.location.href;

// Lade den div-Bereich 'ajax' neu, 'action' wird beim Aufruf mit übergeben --> Buttons
function allTickets(action) {
	var data = {
	'action': 'AJAX',
	'what':action
	};
	
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		jQuery('#ajax').html(response);
	});
}

// Ein- & Ausblenden des Filters
function expand() {
	if(jQuery('#suche').css('visibility') == 'hidden')
	{
		jQuery('#suche').css({'margin-left': '0%', 'width': '100%', 'height': '100px', 'visibility': 'visible', 'transition-duration': '0.2s, 0.2s, 0.2s, 0s', 'transition-delay': '0.2s, 0s, 0.2s, 0s'});
		jQuery('#pseudo').slideDown(30);
		setTimeout(function(){jQuery('#suche').children('form').css('opacity', '1');},200);
	} else 
	{
		jQuery('#suche').css({'margin-left': '78%', 'width': '22%', 'height': '0px', 'visibility': 'hidden', 'transition-duration': '0.2s, 0.2s, 0.2s, 0s', 'transition-delay': '0s, 0.2s, 0s, 0.4s'});
		setTimeout(function(){jQuery('#pseudo').slideUp(50);},400);
		jQuery('#suche').children('form').css('opacity', '0');
	}
}

// Filter anwenden
function filter() {
	var select = jQuery('#select').val();
	var search = jQuery('#search').val();
	var order = jQuery('#order').val();
	
	var data = {
	'action': 'AJAX',
	'search': search,
	'select': select,
	'order': order,
	'what': ''
	};
	
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		jQuery('#ajax').html(response);
		jQuery('#sites_count_0').css({'background': '#fff', 'color': '#778495', 'pointer-events': 'none', 'cursor': 'default'});
		var length = jQuery('#sites_count').children().length;
		if(length > 10)
		{
			for(var i = 4; i < length; i++)
			{
				jQuery('#sites_count_'+i).css('display', 'none');
			}
			jQuery('#sites_count_'+(length-1)).before('<div></div>');
			jQuery('#sites_count_'+(length-1)).css({'display': 'inline'});
		}
	});
}

// Reload des div-Bereichs 'ajax' alle 30s, wenn man unter dem Reiter "Neue Tickets" ist
var myInterval;
var fallback = 0;
function startInterval() {
	if(fallback != 0)
	{
		myInterval = setInterval(function(){allTickets('new')}, 30000);
		fallback = 0;
	}
}
function stopInterval() {
	fallback = 1;
	clearInterval(myInterval);
}

// Eintragen von Lösung/Bemerkung/Termin/Bearbeiter des Tickets
function update(id) {
	var select = jQuery('#'+id).find('.select_2').val();
	var text = jQuery('#'+id).find('.update_text').val();
	if(select == 'antwort') {
		answer(id, text);
		return;
	}
	if(select == 'termin')
	{
		text = jQuery('#'+id).find('.form-control').val();
	}
	if(select == 'bearbeiter')
	{
		text = jQuery('#'+id).find('.select_3').val();
	}
	if(select != 'bearbeiter') {
		var data = {
		'action': 'UPDATE',
		'id': id,
		'select': select,
		'text': text,
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
	} else {
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
			'what': 'open'
			};
			jQuery.post(ajax_object.ajax_url, data2, function(response) {
				jQuery('#ajax').html(response);
			});
		});
		
		indicator('52%');
	}
}

// Ticket übernehmen
function take(id) {
	buttonCheck(0);
	var data = {
	'action': 'UPDATE',
	'id': id,
	'what': 'take',
	'url': url
	};
	
	jQuery.post(ajax_object.ajax_url, data, function(response)
	{
		if(response.indexOf('ja') != -1)
		{
			stopInterval();
			jQuery('#'+id).slideUp(400);
			indicator('26%');	
			setTimeout(function(){
				var data2 = {
				'action': 'AJAX',
				'what': 'my'
				};
				jQuery.post(ajax_object.ajax_url, data2, function(response) {
					jQuery('#ajax').html(response);
				});
			},400);
		} else if(response.indexOf('nein') != -1)
		{
			alert("Dieses Ticket ist bereits vergeben.");
		}
	})
	.fail( function(xhr, textStatus, errorThrown) {
        alert(xhr.responseText);
    });
	
	console.log(bCheck);
}

// Ticket erledigt
function done(id) {
	var data = {
	'action': 'UPDATE',
	'id': id,
	'what': 'done',
	'url': url
	};
	
	jQuery.post(ajax_object.ajax_url, data, function(response)
	{
		if(response.indexOf('ja') != -1)
		{
			jQuery('#'+id).css('opacity', '0');
			jQuery('#'+id).slideUp(400);
		} else if(response.indexOf('nein') != -1)
		{
			alert("Dieses Ticket wird von jemand anderem bearbeitet.");
		}
	});
}

// Ticket übernehmen
function change(id) {
	var data = {
	'action': 'UPDATE',
	'id': id,
	'what': 'change'
	};
	
	jQuery.post(ajax_object.ajax_url, data, function()
	{
		jQuery('#'+id).slideUp(400);
		indicator('26%');	
		setTimeout(function(){
			var data2 = {
			'action': 'AJAX',
			'what': 'my'
			};
			jQuery.post(ajax_object.ajax_url, data2, function(response) {
				jQuery('#ajax').html(response);
			});
		},400);
	});
}

// Ticket zurück holen
function undo(id) {
	var data = {
	'action': 'UPDATE',
	'id': id,
	'what': 'undo'
	};
	
	jQuery.post(ajax_object.ajax_url, data, function()
	{
		jQuery('#'+id).slideUp(400);
		indicator('52%');	
		setTimeout(function(){
			var data2 = {
			'action': 'AJAX',
			'what': 'open'
			};
			jQuery.post(ajax_object.ajax_url, data2, function(response) {
				jQuery('#ajax').html(response);
			});
		},400);
	});
}

// Ein- & Ausblenden des Bereichs zum eintragen der Lösung etc.
function expand2(id) {
	if(jQuery('#'+id+' .update').css('display') == 'none')
	{
		jQuery('#'+id+' .update').css('display', 'flex');
		jQuery('#'+id+' .answers').css('display', 'table');
		jQuery('#'+id+' .expand').css('transform', 'rotate(180deg)');
	} else
	{
		if(jQuery('#'+id).height() > 800) {
			jQuery('html, body').animate({
				scrollTop: jQuery('#'+id).offset().top-20
			}, 500);
		}
		jQuery('#'+id+' .update').css('display', 'none');
		jQuery('#'+id+' .answers').css('display', 'none');
		jQuery('#'+id+' .expand').css('transform', 'rotate(0deg)');
	}
}

// Der Indikator unter den Buttons
function indicator(x) {
	jQuery('#indicator').children('div').css('margin-left', x);
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

// automatische Aktuallisierung stoppen wenn in Textfeld
function buttonCheck(x) {
	bCheck = x;
}
function textareaCheck() {
	jQuery(document).ready(function() {
		jQuery("input, textarea").on("focus", function()
		{
			stopInterval();
		});
		if(bCheck == 1)
		{
			jQuery("input, textarea").on("focusout", function()
			{
				startInterval();
			});	
		}
	});
}

// Filter Seitenzahlen
function sitenumber(count) {
	var select = jQuery('#select').val();
	var search = jQuery('#search').val();
	var order = jQuery('#order').val();
	
	var data = {
	'action': 'AJAX',
	'search': search,
	'select': select,
	'order': order,
	'offset': count,
	'what': ''
	};
	
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		jQuery('#ajax').html(response);
		jQuery('#sites_count_'+count/10).css({'background': '#fff', 'color': '#778495', 'pointer-events': 'none', 'cursor': 'default'});
		
		var length = jQuery('#sites_count').children().length;
		if(length > 10)
		{
			var current = count / 10;
			for(var i = current+4; i < length; i++)
			{
				jQuery('#sites_count_'+i).css('display', 'none');
			}
			for(var i = current-4; i > 0; i--)
			{
				jQuery('#sites_count_'+i).css('display', 'none');
			}
			if(jQuery('#sites_count_'+(length-2)).css('display') == 'none')
			{
				jQuery('#sites_count_'+(length-1)).before('<div></div>');
			}
			jQuery('#sites_count_'+(length-1)).css({'display': 'inline'});
			if(jQuery('#sites_count_1').css('display') == 'none')
			{
				jQuery('#sites_count_0').after('<div></div>');
			}
			jQuery('#sites_count_0').css({'display': 'inline'});
		}
	});
	
	jQuery(document).scrollTop(200);
}

// Login
function login() {
	var username = jQuery('#login_name').val();
	var passwort = jQuery('#login_passwort').val();
	
	var data = {
	'action': 'LOGIN',
	'username': username,
	'passwort': passwort
	};
	
	jQuery.post(ajax_object.ajax_url, data, function(response) {
		var data2 = {'action': 'SYSTEM'};
		jQuery.post(ajax_object.ajax_url, data2, function(response2) {
			var final_data = jQuery(response2).filter('#ts_content').html();
			jQuery('#ts_content').html(final_data);
			jQuery('#ts_content').append(response);
		});
	});
}

// Logout
function logout() {
	var data = {'action': 'LOGOUT'};
	jQuery.post(ajax_object.ajax_url, data, function() {
		var data2 = {'action': 'SYSTEM'};
		jQuery.post(ajax_object.ajax_url, data2, function(response) {
			var final_data = jQuery(response).filter('#ts_content').html();
			jQuery('#ts_content').html(final_data);
		});
	});
}

// Antworten
function answer(id, text) {
		
	var data = {
	'action': 'ANSWER',
	'id': id,
	'text': text,
	'url': url
	};
		
	jQuery.post(ajax_object.ajax_url, data, function() {
		var data2 = {
		'action': 'AJAX',
		'id': id,
		'what': ''
		};
		jQuery.post(ajax_object.ajax_url, data2, function(response) {
			var final_data = jQuery(response).find('.answers').html();
			jQuery('#'+id).children('.answers').html(final_data);
			textarea(id);
		});
	});	
}