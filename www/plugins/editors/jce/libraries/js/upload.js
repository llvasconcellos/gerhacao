/*
Script: Swiff.Base.js
	Contains <Swiff>, <Swiff.getVersion>, <Swiff.remote>

Author:
	Valerio Proietti, <http://mad4milk.net>
	enhanced by Harald Kirschner <http://digitarald.de>

Credits:
	Flash detection 'borrowed' from SWFObject.

License:
	MIT-style license.
*/

/*
Function: Swiff
	creates a flash object with supplied parameters.

Arguments:
	source - the swf path.
	properties - an object with key/value pairs. all options are optional. see below.
	where - the $(element) to inject the flash object.

Properties:
	width - int, the width of the flash object. defaults to 0.
	height - int, the height of the flash object. defaults to 0.
	id - string, the id of the flash object. defaults to 'Swiff-Object-num_of_object_inserted'.
	wmode - string, transparent or opaque.
	bgcolor - string, hex value for the movie background color.
	vars - an object of variables (functions, anything) you want to pass to your flash movie

Returns:
	the object element, to be injected somewhere.
	Important: the $ function on the OBJECT element wont extend it, will just target the movie by its id/reference. So its not possible to use the <Element> methods on it.
	This is why it has to be injected using $('myFlashContainer').adopt(myObj) instead of $(myObj).injectInside('myFlashContainer');

Example:
	(start code)
	var obj = new Swiff('myMovie.swf', {
		width: 500,
		height: 400,
		id: 'myBeautifulMovie',
		wmode: 'opaque',
		bgcolor: '#ff3300',
		vars: {
			onLoad: myOnloadFunc,
			myVariable: myJsVar,
			myVariableString: 'hello'
		}
	});
	$('myElement').adopt(obj);
	(end)
*/

var Swiff = function(source, props){
	if (!Swiff.fixed) Swiff.fix();
	var instance = Swiff.nextInstance();
	Swiff.vars[instance] = {};
	props = $merge({
		width: 1,
		height: 1,
		id: instance,
		wmode: 'transparent',
		bgcolor: '#ffffff',
		allowScriptAccess: 'sameDomain',
		callBacks: {'onLoad': Class.empty},
		params: {}
	}, props || {});
	var append = [];
	if (window.ie) append.push('__salt=' + $time());
	for (var p in props.callBacks){
		Swiff.vars[instance][p] = props.callBacks[p];
		append.push(p + '=Swiff.vars.' + instance + '.' + p);
	}
	if (props.params) append.push(Object.toQueryString(props.params));
	var swf = source + (source.contains('?') ? '&' : '?') + append.join('&');
	return new Element('div').setHTML(
		'<object width="', props.width, '" height="', props.height, '" id="', props.id, '" type="application/x-shockwave-flash" data="', swf, '">'
			,'<param name="allowScriptAccess" value="', props.allowScriptAccess, '" />'
			,'<param name="movie" value="', swf, '" />'
			,'<param name="bgcolor" value="', props.bgcolor, '" />'
			,'<param name="scale" value="noscale" />'
			,'<param name="salign" value="lt" />'
			,'<param name="wmode" value="', props.wmode, '" />'
		,'</object>').firstChild;
};

Swiff.extend = $extend;

Swiff.extend({

	count: 0,

	callBacks: {},

	vars: {},

	nextInstance: function(){
		return 'Swiff' + Swiff.count++;
	},

	//from swfObject, fixes bugs in ie+fp9
	fix: function(){
		Swiff.fixed = true;
		window.addEvent('beforeunload', function(){
			__flash_unloadHandler = __flash_savedUnloadHandler = Class.empty;
		});
		if (!window.ie) return;
		window.addEvent('unload', function(){
			$each(document.getElementsByTagName("object"), function(swf){
				swf.style.display = 'none';
				for (var p in swf){
					if (typeof swf[p] == 'function') swf[p] = Class.empty;
				}
				swf.parentNode.removeChild(swf);
			});
		});
	},

	/*
	Function: Swiff.getVersion
		gets the major version of the flash player installed.

	Returns:
		a number representing the flash version installed, or 0 if no player is installed.
	*/

	getVersion: function(){
		if (!Swiff.pluginVersion) {
			var x;
			if(navigator.plugins && navigator.mimeTypes.length){
				x = navigator.plugins["Shockwave Flash"];
				if(x && x.description) x = x.description;
			} else if (window.ie){
				try {
					x = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
					x = x.GetVariable("$version");
				} catch(e){}
			}
			Swiff.pluginVersion = ($type(x) == 'string') ? parseInt(x.match(/\d+/)[0]) : 0;
		}
		return Swiff.pluginVersion;
	},

	/*
	Function: Swiff.remote
		Calls an ActionScript function from javascript. Requires ExternalInterface.

	Returns:
		Whatever the ActionScript Returns
	*/

	remote: function(obj, fn){
		var rs = obj.CallFunction("<invoke name=\"" + fn + "\" returntype=\"javascript\">" + __flash__argumentsToXML(arguments, 2) + "</invoke>");
		return eval(rs);
	}

});
/*
Script: Swiff.Uploader.js
	Contains <Swiff.Uploader>

Author:
	Valerio Proietti, <http://mad4milk.net>,
	Harald Kirschner, <http://digitarald.de>

License:
	MIT-style license.
*/

/*
Class: Swiff.Uploader
	creates an uploader instance. Requires an existing Swiff.Uploader.swf instance.

Arguments:
	callBacks - an object, containing key/value pairs, representing the possible callbacks. See below.
	onLoaded - Callback when the swf is initialized
	options - types, multiple, queued, swf, url, container

callBacks:
	onOpen - a function to fire when the user opens a file.
	onProgress - a function to fire when the file is uploading. passes the name, the current uploaded size and the full size.
	onSelect - a function to fire when the user selects a file.
	onComplete - a function to fire when the file finishes uploading
	onError - a function to fire when there is an error.
	onCancel - a function to fire when the user cancels the file uploading.
*/

Swiff.Uploader = new Class({

	options: {
		types: false,
		multiple: true,
		queued: true,
		swf: null,
		url: null,
		container: null
	},

	callBacks: {
		onOpen: Class.empty,
		onProgress: Class.empty,
		onSelect: Class.empty,
		onComplete: Class.empty,
		onError: Class.empty,
		onCancel: Class.empty
	},

	initialize: function(callBacks, onLoaded, onLoadFail, options){
		this.setOptions(options);
		if (Swiff.getVersion() < 8){
			onLoadFail();
			return false;
		}
		this.onLoaded = onLoaded;
		var calls = $extend($merge(this.callBacks), callBacks || {});
		for (p in calls){
			calls[p] = calls[p].bind(this);
		}
		this.instance = Swiff.nextInstance();
		Swiff.callBacks[this.instance] = calls;
		this.object = Swiff.Uploader.register(this.loaded.bind(this), this.options.swf, this.options.container);
		return this;
	},

	loaded: function(){
		Swiff.remote(this.object, 'create', this.instance, this.options.types, this.options.multiple, this.options.queued, this.options.url);
		this.onLoaded.delay(10);
	},

	browse: function(){
		Swiff.remote(this.object, 'browse', this.instance);
	},

	send: function(url){
		Swiff.remote(this.object, 'upload', this.instance, url);
	},

	remove: function(name, size){
		Swiff.remote(this.object, 'remove', this.instance, name, size);
	},

	fileIndex: function(name, size){
		return Swiff.remote(this.object, 'fileIndex', this.instance, name, size);
	},

	fileList: function(){
		return Swiff.remote(this.object, 'filelist', this.instance);
		
	}

});

Swiff.Uploader.implement(new Options);

Swiff.Uploader.extend = $extend;

Swiff.Uploader.extend({

	swf: 'Swiff.Uploader.swf',

	callBacks: [],

	register: function(callBack, url, container){
		if (!Swiff.Uploader.object || !Swiff.Uploader.loaded) {
			Swiff.Uploader.callBacks.push(callBack);
			if (!Swiff.Uploader.object) {
				Swiff.Uploader.object = new Swiff(url || Swiff.Uploader.swf, {callBacks: {'onLoad': Swiff.Uploader.onLoad}});
				(container || document.body).appendChild(Swiff.Uploader.object);
			}
		}
		else callBack.delay(10);
		return Swiff.Uploader.object;
	},

	onLoad: function(){
		Swiff.Uploader.loaded = true;
		Swiff.Uploader.callBacks.each(function(fn){
			fn.delay(10);
		});
		Swiff.Uploader.callBacks.length = 0;
	}

});
/**
 * FancyUpload - Flash meets Ajax for beauty uploads
 * 
 * Based on Swiff.Base and Swiff.Uploader.
 * 
 * Its intended that you edit this class to add your
 * own queue layout/text/effects. This is NO include
 * and forget class. If you want custom effects or
 * more output, use Swiff.Uploader as interface
 * for your new class or change this class.
 * 
 * USAGE:
 *  var inputElement = $E('input[type="file"]');
 * 	new FancyUpload(inputElement, {
 * 		swf: '../swf/Swiff.Uploader.swf'
 * 		// more options
 * 	})
 * 
 * 	The target element has to be in an form, the upload starts onsubmit
 * 	by default.
 * 
 * OPTIONS:
 * 
 * 	url: Upload target URL, default is form-action if given, otherwise current page
 *  swf: Path & filename of the swf file, default: Swiff.Uploader.swf
 *  multiple: Multiple files selection, default: true
 *  queued: Queued upload, default: true
 *  types: Object with (description: extension) pairs, default: Images (*.jpg; *.jpeg; *.gif; *.png)
 *  limitSize: Maximum size for one added file, bigger files are ignored, default: false
 *  limitFiles: Maximum files in the queue, default: false
 *  createReplacement: Function that creates the replacement for the input-file, default: false, so a button with "Browse Files" is created
 *  instantStart: Upload starts instantly after selecting a file, default: false
 *  allowDuplicates: Allow duplicate filenames in the queue, default: true
 *  container: Container element for the swf, default: document.body, used only for the first FancyUpload instance, see QUIRKS
 *  optionFxDuration: Fx duration for highlight, default: 250
 *  queueList: The Element or ID for the queue list
 *  onComplete: Event fired when one file is completed
 *  onAllComplete: Event fired when all files uploaded
 * 
 * NOTE:
 * 
 * 	Flash FileReference is stupid, the request will have no cookies
 * 	or additional post data. Only the file is send in $_FILES['Filedata'],
 * 	with a wrong content-type (application/octet-stream).
 * 	When u have sessions, append them as get-data to the the url.
 * 
 * 
 * @version		1.0rc1
 * 
 * @license		MIT License
 * 
 * @author		Harald Kirschner <mail [at] digitarald [dot] de>
 * @copyright	Authors
 */
var Uploader = new Class({

	options: {
		url: false,
		method: 'flash',
		swf: 'swiff.swf',
		multiple: true,
		queued: true,
		types: {},
		size: false,
		limitFiles: false,
		instantStart: false,
		allowDuplicates: false,
		optionFxDuration: 250,
		container: null,
		queueList: 'upload-queue',
		onStart: Class.empty,
		onEditList : Class.empty,
		onComplete: Class.empty,
		onError: Class.empty,
		onCancel: Class.empty,
		onAllComplete: Class.empty,
		errorCodes : {
			400: "400 : Upload error!",
			401: "401 : Unauthorized!",
			403: "403 : Forbidden!",
			404: "404 : Not Found!",
			406: "406 : Not Acceptable!",
			408: "408 : Timeout!",
			409: "409 : Unable to process file!",
			415: "415 : Unsupported file type!",
			412: "412 : Invalid target!",
			417: "417 : Unsupported file size!",
			500: "500 : Internal server error!",
			503: "503 : Service Unavailable!"
		},
		buttonLabels : {
			browse: 'Add File...',
			clear: 'Clear Queue'
		}
	},
	initialize: function(el, options){
		this.element = $(el);
		this.setOptions(options);
		this.options.url = this.options.url || this.element.form.action || location.href;
		this.fileList = [];
		
		if(this.options.method == 'flash'){
			this.uploader = new Swiff.Uploader({
				onOpen: this.onOpen.bind(this),
				onProgress: this.onProgress.bind(this),
				onComplete: this.onComplete.bind(this),
				onError: this.onError.bind(this),
				onSelect: this.onSelect.bind(this)
			}, this.initFlash.bind(this), this.onLoadFail.bind(this), {
				swf: this.options.swf,
				types: this.options.types,
				multiple: this.options.multiple,
				queued: this.options.queued,
				container: this.options.container
			});
		}else{
			this.initIframe();
		}
	},
	initFlash: function() {
		this.queue = $(this.options.queueList);
		$(this.element.form).addEvent('submit', this.upload.bindWithEvent(this));
		
		new Element('input', {
			type: 'button',
			'class': 'uploadButtonAdd',
			value: this.options.buttonLabels.browse,
			events: {
				click: this.browse.bind(this)
			}
		}).addClass('add').injectBefore(this.element);
		new Element('input', {
			type: 'button',
			'class': 'uploadButtonRemove',
			styles: {
				position: 'absolute',
				top: '20px',
				right: '10px'
			},
			value: this.options.buttonLabels.clear,
			events: {
				click: this.clearList.bind(this)
			}
		}).addClass('remove').injectBefore(this.element);
		this.element.remove();
	},	
	initIframe : function(){
		this.options.method = 'html';
		new IFrame({
			action : 'upload',
			onComplete : function(o){					
					if(o.error){
						alert(o.error);	
					}
					if(o.result.error){
						$$('span.queue-text').setText($$('span.queue-text').getText() + ' - ' + o.result.text);
						$$('div.queue-status').removeClass('queue-delete').addClass('queue-error');	
					}else{
						$$('div.queue-progress').setHTML('100%');
						$$('div.queue-loader').setStyle('width', 75 * (100 / 100) + '%');
						$$('div.queue-status').removeClass('queue-delete').addClass('queue-complete');	
					}
					this.fireEvent('onComplete', [o.result.text]);
					this.fireEvent('onAllComplete');
			}.bind(this)
		});
	},	
	onLoadFail : function(){
		this.initIframe();
	},
	browse: function() {
		this.uploader.browse();
	},
	upload: function(e) {
		if (e) e.stop();
		this.fireEvent('onStart', [this.fileList]);
		this.uploader.send(this.options.url);
	},
	onSelect: function(name, size) {
		if (this.uploadTimer) this.uploadTimer = $clear(this.uploadTimer);
		if(this.options.size && Math.round(size/1024) > this.options.size){
			alert(this.options.errorCodes[417]);
			return false;
		}
		if(this.options.limitFiles && this.fileList.length >= this.options.limitFiles){
			alert('Upload limit reached!');
			return false;	
		}
		if(!this.options.allowDuplicates && this.findFile(name, size) != -1){
			return false;
		}
		this.addFile(name, size);
		if (this.options.instantStart) this.uploadTimer = this.upload.delay(250, this);
		return true;
	},
	onOpen: function(name, size) {
		var index = this.findFile(name, size);
		this.fileList[index].status = 1;
	},
	onProgress: function(name, bytes, total, percentage) {
		this.uploadStatus(name, total, percentage);
	},
	onComplete: function(name, size) {
		var index = this.uploadStatus(name, size, 100);
		this.fileList[index].status = 2;
		this.checkComplete(name, size, 'onComplete');
		$E('div.queue-status', this.fileList[index].element).removeClass('queue-delete').addClass('queue-complete');
	},
	onError: function(name, size, error) {
		var code 	= this.options.errorCodes[error.toInt()] || error;
		var msg 	= "Upload failed - " + code;
		var index = this.uploadStatus(name, size, 100);
		$E('span.queue-text', this.fileList[index].element).setHTML(msg);
		this.fileList[index].status = 2;
		$E('div.queue-status', this.fileList[index].element).removeClass('queue-delete').addClass('queue-error');
		this.checkComplete(name, size, 'onError');
	},
	checkComplete: function(name, size, fire) {
		this.fireEvent(fire, [name, size]);
		if(this.nextFile() == -1){
			this.fireEvent('onAllComplete');
		}
	},
	addFile: function(name, size) {
		if (!this.options.multiple && this.fileList.length) this.remove(this.fileList[0].name, this.fileList[0].size);
		var ext = name.substring(name.length, name.lastIndexOf('.')+1);
		var index = this.fileList.length;
		this.fileList.include({
			name: name,
			size: size,
			status: 0,
			percentage: 0,
			element: new Element('li', {
				'class': 'file'
			}).addClass(ext).adopt(
				new Element('span', {
					'title': string.safe(name),
					'class': 'queue-text'			
				}).setHTML(string.safe(name))
			).adopt(
				new Element('div', {
					'class': 'queue-status',
					events: {
						click: this.cancelFile.bindWithEvent(this, [name, size, index])
					}
				}).addClass('queue-delete')
			).adopt(
				new Element('div', {
					'class': 'queue-progress'
				}).setHTML('0%')
			).injectInside(this.queue)
		});
		if (this.fileList[index].fx) return;
		
		this.fileList[index].fx = new Element('div', {
			'class': 'queue-loader'
		}).injectBefore(this.fileList.getLast().element.getFirst()).effect('width', {
			duration: 200,
			wait: false,
			unit: '%',
			transition: Fx.Transitions.linear
		}).set(0);
		this.fireEvent('onEditList', [this.fileList]);
	},
	uploadStatus: function(name, size, percentage) {
		var index = this.findFile(name, size);
		this.fileList[index].fx.start(75 * (percentage / 100));		
		$E('div.queue-progress', this.fileList[index].element).setHTML(percentage +'%');
		this.fileList[index].percentage = percentage;
		
		return index;
	},
	uploadOverview: function() {
		var percentage = 0, len = this.fileList.length;
		for(var i=0; i<len; i++){
			percentage += this.fileList[i].percentage;
		}
		return Math.ceil(percentage / len);
	},	
	cancelFile: function(e, name, size, index) {
		e.stop();
		this.remove(name, size, index);
	},
	remove: function(name, size, index) {
		if(name) index = this.findFile(name, size);
		if(index == -1){ 
			return;
		}
		if(this.fileList[index].status < 2) {
			this.uploader.remove(name, size);
			this.checkComplete(name, size, 'onCancel');
		}
		this.fileList[index].element.remove();
		this.fileList.splice(index, 1);
		this.fireEvent('onEditList', [this.fileList]);
		return;
	},
	findFile: function(name, size) {
		var i = -1;
		while(++i < this.fileList.length){
			if (this.fileList[i].name == name && this.fileList[i].size == size){
				return i;
			}
		}
		return -1;
	},
	nextFile: function() {
		var i = -1;
		while(++i < this.fileList.length) {
			if (this.fileList[i].status != 2){
				return i;
			}
		}
		return -1;
	},
	clearList: function() {
		var i = -1;
		while(++i < this.fileList.length){
			this.remove(0, 0, 0, i--);
		}
	}
});
Uploader.implement(new Events, new Options);