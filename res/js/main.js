DEFAULT_ENCODE = function(a) { return a; };
DEFAULT_DECODE = function(a) { return a; };
window.lang = null;
window.load_lang = false;
window.nb_script_to_load = 4;

function init_script() {
	window.include("/res/js/utils.js");
	window.include("/res/js/device.js");
	window.include("/res/js/message.js");
	loadPage();
}

function pageLoaded () {
	if (getCookie('accept_cookies') !== 'ok') {
		document.getElementById('cookiebar').style.display = 'flex';
	}
	
	if (document.getElementById('messages_contacts') !== null) {
		Messages.init();
	}
}

function loadPage () {
	if (window.nb_script_to_load > 0) {
		if (window.nb_script_to_load == 1 && (window.load_lang === false)) {
			window.load_lang = true;
			/* Load lang file */
			var opt = {
				'url': '/res/locales/' + getCookie('lang') + '.json',
				'data': {},
				'callback': function(data) {
					window.lang = JSON.parse(data);
					window.nb_script_to_load--;
				},
				'checkErrors': false,
				'decode': false
			};
			Ajax.get(opt);
		}
		/* Wait for the load of all scripts */
		Utils.setTimeout(function() {
			loadPage();
		}, 5);
	} else {
		pageLoaded();
	}
}

window.include("/res/js/konami.js");
