//Contains tiny_mce_popup.js, editable_selects.js, mcabs.js and validate.js

// Some global instances, this will be filled later
var tinyMCE = null, tinyMCELang = null;

function TinyMCE_Popup() {
};

TinyMCE_Popup.prototype = {
	findWin : function(w) {
		var c;

		// Check parents
		c = w;
		while (c && (c = c.parent) != null) {
			if (typeof(c.tinyMCE) != "undefined")
				return c;
		}

		// Check openers
		c = w;
		while (c && (c = c.opener) != null) {
			if (typeof(c.tinyMCE) != "undefined")
				return c;
		}

		// Try top
		if (typeof(top.tinyMCE) != "undefined")
			return top;

		return null;
	},

	init : function() {
		var win = window.opener ? window.opener : window.dialogArguments, c;
		var inst;

		if (!win)
			win = this.findWin(window);

		if (!win) {
			alert("tinyMCE object reference not found from popup.");
			return;
		}

		window.opener = win;
		this.windowOpener = win;
		this.onLoadEval = "";

		// Setup parent references
		tinyMCE = win.tinyMCE;
		tinyMCELang = win.tinyMCELang;

		inst = tinyMCE.selectedInstance;
		this.isWindow = tinyMCE.getWindowArg('mce_inside_iframe', false) == false;
		this.storeSelection = (tinyMCE.isRealIE) && !this.isWindow && tinyMCE.getWindowArg('mce_store_selection', true);

		if (this.isWindow)
			window.focus();

		// Store selection
		if (this.storeSelection)
			inst.selectionBookmark = inst.selection.getBookmark(true);

		// Setup dir
		if (tinyMCELang['lang_dir'])
			document.dir = tinyMCELang['lang_dir'];

		// Setup title
		var re = new RegExp('{|\\\$|}', 'g');
		var title = document.title.replace(re, "");
		if (typeof tinyMCELang[title] != "undefined") {
			var divElm = document.createElement("div");
			divElm.innerHTML = tinyMCELang[title];
			document.title = divElm.innerHTML;

			if (tinyMCE.setWindowTitle != null)
				tinyMCE.setWindowTitle(window, divElm.innerHTML);
		}

		// Output Popup CSS class
		document.write('<link href="' + tinyMCE.getParam("popups_css") + '" rel="stylesheet" type="text/css">');

		if (tinyMCE.getParam("popups_css_add")) {
			c = tinyMCE.getParam("popups_css_add");

			// Is relative
			if (c.indexOf('://') == -1 && c.charAt(0) != '/')
				c = tinyMCE.documentBasePath + "/" + c;

			document.write('<link href="' + c + '" rel="stylesheet" type="text/css">');
		}

		tinyMCE.addEvent(window, "load", this.onLoad);
	},

	onLoad : function() {
		var dir, i, elms, body = document.body;

		if (tinyMCE.getWindowArg('mce_replacevariables', true))
			body.innerHTML = tinyMCE.applyTemplate(body.innerHTML, tinyMCE.windowArgs);

		dir = tinyMCE.selectedInstance.settings['directionality'];
		if (dir == "rtl" && document.forms && document.forms.length > 0) {
			elms = document.forms[0].elements;
			for (i=0; i<elms.length; i++) {
				if ((elms[i].type == "text" || elms[i].type == "textarea") && elms[i].getAttribute("dir") != "ltr")
					elms[i].dir = dir;
			}
		}

		if (body.style.display == 'none')
			body.style.display = 'block';

		// Execute real onload (Opera fix)
		if (tinyMCEPopup.onLoadEval != "")
			eval(tinyMCEPopup.onLoadEval);
	},

	executeOnLoad : function(str) {
		if (tinyMCE.isOpera)
			this.onLoadEval = str;
		else
			eval(str);
	},

	resizeToInnerSize : function() {
		// Netscape 7.1 workaround
		if (this.isWindow && tinyMCE.isNS71) {
			window.resizeBy(0, 10);
			return;
		}

		if (this.isWindow) {
			var doc = document;
			var body = doc.body;
			var oldMargin, wrapper, iframe, nodes, dx, dy;

			if (body.style.display == 'none')
				body.style.display = 'block';

			// Remove margin
			oldMargin = body.style.margin;
			body.style.margin = '0';

			// Create wrapper
			wrapper = doc.createElement("div");
			wrapper.id = 'mcBodyWrapper';
			wrapper.style.display = 'none';
			wrapper.style.margin = '0';

			// Wrap body elements
			nodes = doc.body.childNodes;
			for (var i=nodes.length-1; i>=0; i--) {
				if (wrapper.hasChildNodes())
					wrapper.insertBefore(nodes[i].cloneNode(true), wrapper.firstChild);
				else
					wrapper.appendChild(nodes[i].cloneNode(true));

				nodes[i].parentNode.removeChild(nodes[i]);
			}

			// Add wrapper
			doc.body.appendChild(wrapper);

			// Create iframe
			iframe = document.createElement("iframe");
			iframe.id = "mcWinIframe";
			iframe.src = document.location.href.toLowerCase().indexOf('https') == -1 ? "about:blank" : tinyMCE.settings['default_document'];
			iframe.width = "100%";
			iframe.height = "100%";
			iframe.style.margin = '0';

			// Add iframe
			doc.body.appendChild(iframe);

			// Measure iframe
			iframe = document.getElementById('mcWinIframe');
			dx = tinyMCE.getWindowArg('mce_width') - iframe.clientWidth;
			dy = tinyMCE.getWindowArg('mce_height') - iframe.clientHeight;

			// Resize window
			// tinyMCE.debug(tinyMCE.getWindowArg('mce_width') + "," + tinyMCE.getWindowArg('mce_height') + " - " + dx + "," + dy);
			window.resizeBy(dx, dy);

			// Hide iframe and show wrapper
			body.style.margin = oldMargin;
			iframe.style.display = 'none';
			wrapper.style.display = 'block';
		}
	},

	resizeToContent : function() {
		var isMSIE = (navigator.appName == "Microsoft Internet Explorer");
		var isOpera = (navigator.userAgent.indexOf("Opera") != -1);

		if (isOpera)
			return;

		if (isMSIE) {
			try { window.resizeTo(10, 10); } catch (e) {}

			var elm = document.body;
			var width = elm.offsetWidth;
			var height = elm.offsetHeight;
			var dx = (elm.scrollWidth - width) + 4;
			var dy = elm.scrollHeight - height;

			try { window.resizeBy(dx, dy); } catch (e) {}
		} else {
			window.scrollBy(1000, 1000);
			if (window.scrollX > 0 || window.scrollY > 0) {
				window.resizeBy(window.innerWidth * 2, window.innerHeight * 2);
				window.sizeToContent();
				window.scrollTo(0, 0);
				var x = parseInt(screen.width / 2.0) - (window.outerWidth / 2.0);
				var y = parseInt(screen.height / 2.0) - (window.outerHeight / 2.0);
				window.moveTo(x, y);
			}
		}
	},

	getWindowArg : function(name, default_value) {
		return tinyMCE.getWindowArg(name, default_value);
	},

	restoreSelection : function() {
		if (this.storeSelection) {
			var inst = tinyMCE.selectedInstance;

			inst.getWin().focus();

			if (inst.selectionBookmark)
				inst.selection.moveToBookmark(inst.selectionBookmark);
		}
	},

	execCommand : function(command, user_interface, value) {
		var inst = tinyMCE.selectedInstance;

		this.restoreSelection();
		inst.execCommand(command, user_interface, value);

		// Store selection
		if (this.storeSelection)
			inst.selectionBookmark = inst.selection.getBookmark(true);
	},

	close : function() {
		tinyMCE.closeWindow(window);
	},

	pickColor : function(e, element_id) {
		tinyMCE.selectedInstance.execCommand('mceColorPicker', true, {
			element_id : element_id,
			document : document,
			window : window,
			store_selection : false
		});
	},

	openBrowser : function(element_id, type, option) {
		var cb = tinyMCE.getParam(option, tinyMCE.getParam("file_browser_callback"));
		var url = document.getElementById(element_id).value;

		tinyMCE.setWindowArg("window", window);
		tinyMCE.setWindowArg("document", document);

		// Call to external callback
		if (eval('typeof(tinyMCEPopup.windowOpener.' + cb + ')') == "undefined")
			alert("Callback function: " + cb + " could not be found.");
		else
			eval("tinyMCEPopup.windowOpener." + cb + "(element_id, url, type, window);");
	},

	importClass : function(c) {
		window[c] = function() {};

		for (var n in window.opener[c].prototype)
			window[c].prototype[n] = window.opener[c].prototype[n];

		window[c].constructor = window.opener[c].constructor;
	}

	};

// Setup global instance
var tinyMCEPopup = new TinyMCE_Popup();

tinyMCEPopup.init();

/**
 * $Id: editable_selects.js 162 2007-01-03 16:16:52Z spocke $
 *
 * Makes select boxes editable.
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

var TinyMCE_EditableSelects = {
	editSelectElm : null,

	init : function() {
		var nl = document.getElementsByTagName("select"), i, d = document, o;

		for (i=0; i<nl.length; i++) {
			if (nl[i].className.indexOf('mceEditableSelect') != -1) {
				o = new Option('(value)', '__mce_add_custom__');

				o.className = 'mceAddSelectValue';

				nl[i].options[nl[i].options.length] = o;
				nl[i].setAttribute('onchange', 'TinyMCE_EditableSelects.onChangeEditableSelect(this);');
			}
		}
	},

	onChangeEditableSelect : function(se) {
		var d = document, ne;

		if (se.options[se.selectedIndex].value == '__mce_add_custom__') {
			ne = d.createElement("input");
			ne.id = se.id + "_custom";
			ne.name = se.name + "_custom";
			ne.type = "text";

			ne.style.width = se.clientWidth;
			se.parentNode.insertBefore(ne, se);
			se.style.display = 'none';
			ne.focus();
			ne.onblur = TinyMCE_EditableSelects.onBlurEditableSelectInput;
			TinyMCE_EditableSelects.editSelectElm = se;
		}
	},

	onBlurEditableSelectInput : function() {
		var se = TinyMCE_EditableSelects.editSelectElm;

		if (se) {
			if (se.previousSibling.value != '') {
				addSelectValue(document.forms[0], se.id, se.previousSibling.value, se.previousSibling.value);
				selectByValue(document.forms[0], se.id, se.previousSibling.value);
			} else
				selectByValue(document.forms[0], se.id, '');

			se.style.display = 'inline';
			se.parentNode.removeChild(se.previousSibling);
			TinyMCE_EditableSelects.editSelectElm = null;
		}
	}
};

/**
 * $Id: mctabs.js 162 2007-01-03 16:16:52Z spocke $
 *
 * Moxiecode DHTML Tabs script.
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

function MCTabs() {
	this.settings = new Array();
};

MCTabs.prototype.init = function(settings) {
	this.settings = settings;
};

MCTabs.prototype.getParam = function(name, default_value) {
	var value = null;

	value = (typeof(this.settings[name]) == "undefined") ? default_value : this.settings[name];

	// Fix bool values
	if (value == "true" || value == "false")
		return (value == "true");

	return value;
};

MCTabs.prototype.displayTab = function(tab_id, panel_id) {
	var panelElm = document.getElementById(panel_id);
	var panelContainerElm = panelElm ? panelElm.parentNode : null;
	var tabElm = document.getElementById(tab_id);
	var tabContainerElm = tabElm ? tabElm.parentNode : null;
	var selectionClass = this.getParam('selection_class', 'current');

	if (tabElm && tabContainerElm) {
		var nodes = tabContainerElm.childNodes;

		// Hide all other tabs
		for (var i=0; i<nodes.length; i++) {
			if (nodes[i].nodeName == "LI")
				nodes[i].className = '';
		}

		// Show selected tab
		tabElm.className = 'current';
	}

	if (panelElm && panelContainerElm) {
		var nodes = panelContainerElm.childNodes;

		// Hide all other panels
		for (var i=0; i<nodes.length; i++) {
			if (nodes[i].nodeName == "DIV")
				nodes[i].className = 'panel';
		}

		// Show selected panel
		panelElm.className = 'current';
	}
};

MCTabs.prototype.getAnchor = function() {
	var pos, url = document.location.href;

	if ((pos = url.lastIndexOf('#')) != -1)
		return url.substring(pos + 1);

	return "";
};

// Global instance
var mcTabs = new MCTabs();

/**
 * $Id: validate.js 162 2007-01-03 16:16:52Z spocke $
 *
 * Various form validation methods.
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

/**
	// String validation:

	if (!Validator.isEmail('myemail'))
		alert('Invalid email.');

	// Form validation:

	var f = document.forms['myform'];

	if (!Validator.isEmail(f.myemail))
		alert('Invalid email.');
*/

var Validator = {
	isEmail : function(s) {
		return this.test(s, '^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$');
	},

	isAbsUrl : function(s) {
		return this.test(s, '^(news|telnet|nttp|file|http|ftp|https)://[-A-Za-z0-9\\.]+\\/?.*$');
	},

	isSize : function(s) {
		return this.test(s, '^[0-9]+(px|%)?$');
	},

	isId : function(s) {
		return this.test(s, '^[A-Za-z_]([A-Za-z0-9_])*$');
	},

	isEmpty : function(s) {
		var nl, i;

		if (s.nodeName == 'SELECT' && s.selectedIndex < 1)
			return true;

		if (s.type == 'checkbox' && !s.checked)
			return true;

		if (s.type == 'radio') {
			for (i=0, nl = s.form.elements; i<nl.length; i++) {
				if (nl[i].type == "radio" && nl[i].name == s.name && nl[i].checked)
					return false;
			}

			return true;
		}

		return new RegExp('^\\s*$').test(s.nodeType == 1 ? s.value : s);
	},

	isNumber : function(s, d) {
		return !isNaN(s.nodeType == 1 ? s.value : s) && (!d || !this.test(s, '^-?[0-9]*\\.[0-9]*$'));
	},

	test : function(s, p) {
		s = s.nodeType == 1 ? s.value : s;

		return s == '' || new RegExp(p).test(s);
	}
};

var AutoValidator = {
	settings : {
		id_cls : 'id',
		int_cls : 'int',
		url_cls : 'url',
		number_cls : 'number',
		email_cls : 'email',
		size_cls : 'size',
		required_cls : 'required',
		invalid_cls : 'invalid',
		min_cls : 'min',
		max_cls : 'max'
	},

	init : function(s) {
		var n;

		for (n in s)
			this.settings[n] = s[n];
	},

	validate : function(f) {
		var i, nl, s = this.settings, c = 0;

		nl = this.tags(f, 'label');
		for (i=0; i<nl.length; i++)
			this.removeClass(nl[i], s.invalid_cls);

		c += this.validateElms(f, 'input');
		c += this.validateElms(f, 'select');
		c += this.validateElms(f, 'textarea');

		return c == 3;
	},

	invalidate : function(n) {
		this.mark(n.form, n);
	},

	reset : function(e) {
		var t = new Array('label', 'input', 'select', 'textarea');
		var i, j, nl, s = this.settings;

		if (e == null)
			return;

		for (i=0; i<t.length; i++) {
			nl = this.tags(e.form ? e.form : e, t[i]);
			for (j=0; j<nl.length; j++)
				this.removeClass(nl[j], s.invalid_cls);
		}
	},

	validateElms : function(f, e) {
		var nl, i, n, s = this.settings, st = true, va = Validator, v;

		nl = this.tags(f, e);
		for (i=0; i<nl.length; i++) {
			n = nl[i];

			this.removeClass(n, s.invalid_cls);

			if (this.hasClass(n, s.required_cls) && va.isEmpty(n))
				st = this.mark(f, n);

			if (this.hasClass(n, s.number_cls) && !va.isNumber(n))
				st = this.mark(f, n);

			if (this.hasClass(n, s.int_cls) && !va.isNumber(n, true))
				st = this.mark(f, n);

			if (this.hasClass(n, s.url_cls) && !va.isAbsUrl(n))
				st = this.mark(f, n);

			if (this.hasClass(n, s.email_cls) && !va.isEmail(n))
				st = this.mark(f, n);

			if (this.hasClass(n, s.size_cls) && !va.isSize(n))
				st = this.mark(f, n);

			if (this.hasClass(n, s.id_cls) && !va.isId(n))
				st = this.mark(f, n);

			if (this.hasClass(n, s.min_cls, true)) {
				v = this.getNum(n, s.min_cls);

				if (isNaN(v) || parseInt(n.value) < parseInt(v))
					st = this.mark(f, n);
			}

			if (this.hasClass(n, s.max_cls, true)) {
				v = this.getNum(n, s.max_cls);

				if (isNaN(v) || parseInt(n.value) > parseInt(v))
					st = this.mark(f, n);
			}
		}

		return st;
	},

	hasClass : function(n, c, d) {
		return new RegExp('\\b' + c + (d ? '[0-9]+' : '') + '\\b', 'g').test(n.className);
	},

	getNum : function(n, c) {
		c = n.className.match(new RegExp('\\b' + c + '([0-9]+)\\b', 'g'))[0];
		c = c.replace(/[^0-9]/g, '');

		return c;
	},

	addClass : function(n, c, b) {
		var o = this.removeClass(n, c);
		n.className = b ? c + (o != '' ? (' ' + o) : '') : (o != '' ? (o + ' ') : '') + c;
	},

	removeClass : function(n, c) {
		c = n.className.replace(new RegExp("(^|\\s+)" + c + "(\\s+|$)"), ' ');
		return n.className = c != ' ' ? c : '';
	},

	tags : function(f, s) {
		return f.getElementsByTagName(s);
	},

	mark : function(f, n) {
		var s = this.settings;

		this.addClass(n, s.invalid_cls);
		this.markLabels(f, n, s.invalid_cls);

		return false;
	},

	markLabels : function(f, n, ic) {
		var nl, i;

		nl = this.tags(f, "label");
		for (i=0; i<nl.length; i++) {
			if (nl[i].getAttribute("for") == n.id || nl[i].htmlFor == n.id)
				this.addClass(nl[i], ic);
		}

		return null;
	}
};