var JContentEditor = {
	options	: {
		pluginmode	: false,
		php			: false,
		javascript	: false,
		state 		: 'mceEditor',
		toggleText 	: '[show/hide]',
		allowToggle	: false
	},
	set : function(o){
		tinymce.extend(this.options, o);
	},
	cleanup : function(type, content){
		switch(type){
			case 'insert_to_editor':
				// Remove javascript if not enabled
				if(!this.options.javascript){
					content = content.replace(/<script(.*?)(javascript)(.*?)>(.*?)<\/script>/g, '');
				}
				// Convert to javascript type if not specified
				content = content.replace(/<script>/g, '<script type="text/javascript">');
				// Replace deprecated language attribute
				content = content.replace(/<script(.*)(language="javascript")(.*?)>/g, '<script$1type="text/javascript"$3>');
				// Process PHP tags
				content = content.replace(/<\?(\s+|php\s+)([\w\W]*?)\?>/gi, this.options.php ? '<script type="text/php">$2</script>' : '');	
				break;
			case 'get_from_editor':
				// Remove javascript if not enabled
				if(!this.options.javascript){
					content = content.replace(/<script(.*?)(javascript)(.*?)>(.*?)<\/script>/g, '');
				}
				// Process PHP tags
				content = content.replace(/<script type="text\/php"><!--\s*([\w\W]*?)\s*\/\/\s*--><\/script>/g, this.options.php ? '<?php $1?>' : '');
				// Convert to javascript type if not specified
				content = content.replace(/<script>/g, '<script type="text/javascript">');
				// Replace deprecated language attribute
				content = content.replace(/<script(.*)(language="javascript")(.*?)>/g, '<script$1type="text/javascript"$3>');
				content = content.replace(/<script(.*?)type="text\/javascript"(.*?)><!--\s*(.*?)\s*\/\/\s*--><\/script>/g, '<script$1type="text/javascript"$2>$3</script>');
				// Correct b and i tags
				if(!tinymce.EditorManager.activeEditor.getParam('verify_html')){
					content = content.replace(/<b\b(.*?)>([^>]*)<\/b>/gi, '<strong$1>$2</strong>');
					content = content.replace(/<i\b(.*?)>([^>]*)<\/i>/gi, '<em$1>$2</em>');
				}
				// Remove empty jceutilities anchors
				content = content.replace(/<a([^>]*)class="jce(box|popup|lightbox|tooltip|_tooltip)"([^>]*)><\/a>/gi, '');
				// Remove span elements with jceutilities classes
				content = content.replace(/<span class="jce(box|popup|lightbox|tooltip|_tooltip)">(.*?)<\/span>/gi, '$2');
				break;
			case 'insert_to_editor_dom':
				break;
			case 'get_from_editor_dom':
				break;
		}
		return content;
	},
	save : function(content){
		if(this.options.pluginmode){
			content = content.replace(/&#39;/gi, "'");
			content = content.replace(/&apos;/gi, "'");
			content = content.replace(/&amp;/gi, "&");
			content = content.replace(/&quot;/gi, '"');
		}
		return content;
	},
	browser : function(field_name, url, type, win){	
		var ed = tinymce.EditorManager.activeEditor;
		ed.windowManager.open({
			file : ed.getParam('site_url') + '/index.php?option=com_jce&task=plugin&plugin=browser&file=browser&type=' + type,
			width : 750,
			height : 420,
			resizable : "yes",
        	inline : "yes",
        	close_previous : "no"
    	}, {
        	window : win,
        	input : field_name,
			url: url,
			type: type
    	});
		return false;
	},
	initEditorMode : function(id){
		var d = document;
		if(this.options.allowToggle){			
			d.getElementById('jce_editor_' + id + '_toggle').innerHTML = '<a href="javascript:JContentEditor.toggleEditor(\''+ id +'\');">'+ this.options.toggleText +'</a>';
		}
		d.getElementById(id).className = tinymce.util.Cookie.get('jce_editor_' + id + '_state') || this.options.state;
	},
	toggleEditor : function(id) {
		var d = document;
		if(!tinyMCE.getInstanceById(id)){
			tinyMCE.execCommand('mceAddControl', false, id);
			tinymce.util.Cookie.set('jce_editor_' + id + '_state', 'mceEditor');
			d.getElementById(id).className = 'mceEditor';
		}else{
			tinyMCE.execCommand('mceRemoveControl', false, id);
			tinymce.util.Cookie.set('jce_editor_' + id + '_state', 'mceNoEditor');
			d.getElementById(id).className = 'mceNoEditor';
		}
	},
	getContent : function(id){
		var d = document;
		if(tinyMCE.getInstanceById(id)){
			return tinyMCE.activeEditor.getContent();
		}
		return d.getElementById(id).innerHTML || d.getElementById(id).value || '';
	}
};