function preinit(){
	// Initialize
	tinyMCE.setWindowArg('mce_windowresize', false);
	tinyMCE.setWindowArg('mce_replacevariables', false);
};
function getImageSrc(str){
	var pos = -1;

	if (!str)
		return "";

	if ((pos = str.indexOf('this.src=')) != -1) {
		var src = str.substring(pos + 10);

		src = src.substring(0, src.indexOf('\''));

		if (tinyMCE.getParam('convert_urls'))
			src = convertURL(src, null, true);

		return src;
	}
	return "";
};
function init(){
	tinyMCEPopup.resizeToInnerSize();
	
	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
	var elm = inst.getFocusElement();
	var action = "insert";
	var html = "";
	
	setHTML('border_color_pickcontainer', getColorPickerHTML('border_color_pick','border_color'));

	// Check action
	if (elm != null && elm.nodeName == "IMG")
		action = "update";
		
	setValue('insert', tinyMCE.getLang('lang_' + action, 'Insert', true));
	
	var align = jce.get('align');
	if(align == 'default') align = '';
    
    setValue('hspace', jce.get('hspace'));
    setValue('vspace', jce.get('vspace'));
	check('border', parseInt(jce.get('border')));
    selectByValue('border_width', jce.get('border_width') + 'px');
	selectByValue('border_style', jce.get('border_style'));
	setValue('border_color', jce.get('border_color'));
    selectByValue('align', align);
	setBorder();
	updateColor('border_color_pick', 'border_color');
	
	changeAppearance();
    
	if (action == "update") {
		var src = tinyMCE.getAttrib(elm, 'src');
		var onmouseoversrc = getImageSrc(tinyMCE.cleanupEventStr(tinyMCE.getAttrib(elm, 'onmouseover')));
		var onmouseoutsrc = getImageSrc(tinyMCE.cleanupEventStr(tinyMCE.getAttrib(elm, 'onmouseout')));

		src = convertURL(src, elm, true);

		// Use mce_src if found
		var mceRealSrc = tinyMCE.getAttrib(elm, 'mce_src');
		if (mceRealSrc != "") {
			src = mceRealSrc;

			if (tinyMCE.getParam('convert_urls'))
				src = convertURL(src, elm, true);
		}
		if (onmouseoversrc != "" && tinyMCE.getParam('convert_urls'))
			onmouseoversrc = convertURL(onmouseoversrc, elm, true);

		if (onmouseoutsrc != "" && tinyMCE.getParam('convert_urls'))
			onmouseoutsrc = convertURL(onmouseoutsrc, elm, true);

		// Setup form data
		var style = tinyMCE.parseStyle(tinyMCE.getAttrib(elm, "style"));

		setValue('src', src);
		setValue('alt', tinyMCE.getAttrib(elm, 'alt'));
		setValue('title', tinyMCE.getAttrib(elm, 'title'));
		
		if(tinyMCE.getAttrib(elm, 'border') || trimSize(getStyle(elm, 'border', 'borderLeftWidth'))){
			check('border', true);	
			setBorder();
		}
		selectByValue('border_width', tinyMCE.getAttrib(elm, 'border') || trimSize(getStyle(elm, 'border', 'borderLeftWidth')));			
		selectByValue('border_style', getStyle(elm, 'border', 'borderLeftStyle'));
		
		setValue('border_color', rgbToHex(getStyle(elm, 'borderColor')) || '#000000');		
		updateColor('border_color_pick', 'border_color');
		
		setValue('vspace', tinyMCE.getAttrib(elm, 'vspace') || trimSize(getStyle(elm, 'marginTop')) || trimSize(getStyle(elm, 'marginBottom')) || 0);
		setValue('hspace', tinyMCE.getAttrib(elm, 'hspace') || trimSize(getStyle(elm, 'marginLeft')) || trimSize(getStyle(elm, 'marginRight')) || 0);
		
		setValue('width', tinyMCE.getAttrib(elm, 'width') || trimSize(getStyle(elm, 'width')));
		setValue('tmp_width', getValue('width'));
		setValue('height', tinyMCE.getAttrib(elm, 'height') || trimSize(getStyle(elm, 'height')));
		setValue('tmp_height', getValue('height'));
		
		setValue('onmouseoversrc', onmouseoversrc);
		setValue('onmouseoutsrc', onmouseoutsrc);
		setValue('id', tinyMCE.getAttrib(elm, 'id'));
		setValue('dir',  tinyMCE.getAttrib(elm, 'dir'));
		setValue('lang', tinyMCE.getAttrib(elm, 'lang'));
		setValue('longdesc', tinyMCE.getAttrib(elm, 'longdesc'));
		setValue('usemap', tinyMCE.getAttrib(elm, 'usemap'));
				
		// Select by the values
		if (tinyMCE.isMSIE)
			selectByValue('align', getStyle(elm, 'align', 'styleFloat'));
		else
			selectByValue('align', getStyle(elm, 'align', 'cssFloat'));

		addClassesToList('classlist', 'advimage_styles');

		selectByValue('classlist', tinyMCE.getAttrib(elm, 'class'));

		updateStyle();
		changeAppearance();

		window.focus();
	}else{
		addClassesToList('classlist', 'advimage_styles');
		updateStyle();
	}
	// Check swap image if valid data
	if (getValue('onmouseoversrc') != "" || getValue('onmouseoutsrc') != "")
		setSwapImageDisabled(false);
	else
		setSwapImageDisabled(true);
		
	setupIframe(getValue('src'));
	jce.tree = new dTree('jce.tree', jce.getLibUrl());
	TinyMCE_EditableSelects.init();
};
function changeAppearance(){
	var img = getObj('alignSampleImg');
	if (img){
		img.align = getSelectValue('align');
		img.hspace = getValue('hspace');
		img.vspace = getValue('vspace');
		if(ischecked('border')){
			img.style.borderLeftWidth = img.style.borderRightWidth = img.style.borderTopWidth = img.style.borderBottomWidth = getSelectValue('border_width');
			img.style.borderStyle = getValue('border_style');
			img.style.borderColor = getValue('border_color');
		}else{
			img.style.borderLeftWidth = img.style.borderRightWidth = img.style.borderTopWidth = img.style.borderBottomWidth = '';
			img.style.borderStyle = '';
			img.style.borderColor = '';
		}
	}
};
function setSwapImageDisabled(state){
	check('onmousemovecheck', !state);
	disable('onmouseoversrc', state);
	disable('onmouseoutsrc', state);
};
function insertAction(){
	var inst = tinyMCE.getInstanceById(tinyMCE.getWindowArg('editor_id'));
	var elm = inst.getFocusElement();
	var src = convertURL(getValue('src'), tinyMCE.imgElement);
	var onmouseoversrc = getValue('onmouseoversrc');
	var onmouseoutsrc = getValue('onmouseoutsrc');
	
	updateStyle();

	if (getValue('alt') == "") {
		if (getValue('alt') == "") {
			var answer = confirm(jce.getLang('missing_alt', true, 'Are you sure you want to continue without including an Image Description? Without  it the image may not be accessible to some users with disabilities, or to those using a text browser, or browsing the Web with images turned off.'));
			if (answer == true) {
				setValue('alt', "");
			}
		} else {
			var answer = true;
		}

		if (!answer)
			return;
	}

	if (onmouseoversrc && onmouseoversrc != "" && ischecked('onmousemovecheck'))
		onmouseoversrc = "this.src='" + convertURL(onmouseoversrc, tinyMCE.imgElement) + "';";

	if (onmouseoutsrc && onmouseoutsrc != "" && ischecked('onmousemovecheck'))
		onmouseoutsrc = "this.src='" + convertURL(onmouseoutsrc, tinyMCE.imgElement) + "';";
		
	if (elm != null && elm.nodeName == "IMG") {
		setAttrib(elm, 'src', src);
		setAttrib(elm, 'mce_src', src);
		setAttrib(elm, 'alt');
		setAttrib(elm, 'title');
		setAttrib(elm, 'width');
		setAttrib(elm, 'height');
		if(ischecked('onmousemovecheck')){
			setAttrib(elm, 'onmouseover', onmouseoversrc);
			setAttrib(elm, 'onmouseout', onmouseoutsrc);
		}
		setAttrib(elm, 'id');
		setAttrib(elm, 'dir');
		setAttrib(elm, 'lang');
		setAttrib(elm, 'longdesc');
		setAttrib(elm, 'usemap');
		setAttrib(elm, 'style');
		setAttrib(elm, 'class', getSelectValue('classlist'));
		
		var align = getSelectValue('align');
		
		if(align == 'left' || align == 'right'){
			setAttrib(elm, 'align','');	
		}else{
			setAttrib(elm, 'align', align);
		}

		// Refresh in old MSIE
		if (tinyMCE.isMSIE5)
			elm.outerHTML = elm.outerHTML;
	} else {
		var html = "<img";

		html += makeAttrib('src', src);
		html += makeAttrib('mce_src', src);
		html += makeAttrib('alt');
		html += makeAttrib('title');
		//html += makeAttrib('border');
		html += makeAttrib('width');
		html += makeAttrib('height');
		if(ischecked('onmousemovecheck')){
			html += makeAttrib('onmouseover', onmouseoversrc);
			html += makeAttrib('onmouseout', onmouseoutsrc);
		}
		html += makeAttrib('id');
		html += makeAttrib('dir');
		html += makeAttrib('lang');
		html += makeAttrib('longdesc');
		html += makeAttrib('usemap');
		html += makeAttrib('style');
		html += makeAttrib('class', getSelectValue('classlist'));
		var align = getSelectValue('align');
		if(align == 'left' || align == 'right'){
			html += makeAttrib('align', '');	
		}else{
			html += makeAttrib('align',align);
		}
		html += " />";
		tinyMCEPopup.execCommand("mceInsertContent", false, html);
	}
	tinyMCE._setEventsEnabled(inst.getBody(), false);
	tinyMCEPopup.close();
};
function changeMouseMove(){
	setSwapImageDisabled(!ischecked('onmousemovecheck'));
};
function setBorder(){
	if(ischecked('border')){
		disable('border_width', false); 
		disable('border_style', false);
		disable('border_color', false);
	}else{
		disable('border_width', true); 
		disable('border_style', true);
		disable('border_color', true);
	}
	changeAppearance();
	updateStyle();
};
function updateStyle(){
	var st = tinyMCE.parseStyle(getValue('style'));

	st['width'] = getValue('width') == '' ? '' : getValue('width') + "px";
	st['height'] = getValue('height') == '' ? '' : getValue('height') + "px";
	
	if(getSelectValue('align') == 'left' || getSelectValue('align') == 'right'){
		st['float'] = getSelectValue('align');
	}else{
		st['float'] = '';
	}
	if(ischecked('border')){
		st['border'] = '';
		if(getSelectValue('border_width') != ''){
			st['border'] += getSelectValue('border_width') + 'px ';
		}
		if(getSelectValue('border_style') != ''){
			st['border'] += getSelectValue('border_style') + ' ';
		}
		if(getValue('border_color') != ''){
			st['border'] += getValue('border_color');
		}
	}else{
		st['border'] = '';
	}
	
	if(parseInt(getValue('vspace')) == 0){
		st['margin-top'] = st['margin-bottom'] = '';
	}else{
		st['margin-top'] = st['margin-bottom'] = getValue('vspace') + 'px';
	}
	if(parseInt(getValue('hspace')) == 0){
		st['margin-left'] = st['margin-right'] = '';	
	}else{
		st['margin-left'] = st['margin-right'] = getValue('hspace') + 'px';	
	}
	setValue('style', tinyMCE.serializeStyle(st));
};
function changeHeight(){
	if( !ischecked('constrain') )
        return;

    if (getValue('width') == "" || getValue('height') == "")
		return;

	var temp = (getValue('width') / getValue('tmp_width')) * getValue('tmp_height');
	setValue('height', temp.toFixed(0));
};
function changeWidth(){	
	if( !ischecked('constrain') )
        return;

    if (getValue('width') == "" || getValue('height') == "")
		return;

	var temp = (getValue('height') / getValue('tmp_height')) * getValue('tmp_width');
	setValue('width', temp.toFixed(0));
};
function viewImage(){
	var url = makePath(makePath(tinyMCE.getParam('document_base_url'), jce.get('base_url')), getValue('itemsList'));	
	new imagePreview(basename(getValue('itemsList')), url);
};
function getDimensions(w, h){
	setValue('width', w);
	setValue('tmp_width', w);
	setValue('height', h);
	setValue('tmp_height', h);
	
	setHTML('dim_loader', '');
	disable('insert', false);
	
	updateStyle();
};
function showProperties(html, width, height){
	if(document.getElementById('fileProperties')){
		setHTML('fileProperties', xmlDecode(html));	
		jce.set('showProperties', true);
		if(jce.get('fileSelected')){			
			setValue('width', width);
			setValue('tmp_width', width);
			setValue('height', height);
			setValue('tmp_height', height);
			setHTML('dim_loader', '');
			disable('insert', false);
			
			updateStyle();
		}
	}
};
function selectFile(id){
	var dir = getDir();
	var name = getObj('manager').contentWindow.document.getElementById(id).title;
	var path = makePath(dir,name);
	
	var url = makePath(tinyMCE.getParam('document_base_url'), jce.get('base_url'));
	var src = makePath(url, makePath(dir, name));
		
	if(hasClass('swap_panel', 'current') && ischecked('onmousemovecheck') )
    {
        if( getValue('onmouseoutsrc') == '' ){
            setValue('onmouseoutsrc', src);
        }else{
            setValue('onmouseoversrc', src);
        }
    }else{		
		disable('insert', true);
		setValue('title', name);
		setValue('alt', name);
		setValue('onmouseoutsrc', src);
		setValue('src', src);
				
		setHTML('dim_loader', '<img src="' + jce.getLibImg('load.gif') + '" style="margin-left:2px;" />');
		jce.ajaxSend('getDimensions', path);
	}
};
function showFileDetails(id){
	var dir = getDir();
    var name = getObj('manager').contentWindow.document.getElementById(id).title;
    var path = makePath(dir,name);
    var ext = getExtension(name).toUpperCase();

	setIcons(1);
	show('viewIcon');
	
	var prev = makePath( makePath(tinyMCE.getParam('document_base_url'), jce.get('base_url')), path);
	
	var html = '';
    html += '<div style="font-weight:bold">' + stripExtension(name) + '</div>';
    html += '<div>' + ext + ' ' + jce.getLang('file', false, 'File') + '</div>';
	html += '<div id="fileProperties"></div>';	
	
	setHTML('fileDetails', html);
	setLoader();
	
	jce.ajaxSend('getProperties', path);
	setValue('itemsList', path);
};
// While loading
preinit();
