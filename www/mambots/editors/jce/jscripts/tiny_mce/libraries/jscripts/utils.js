function getObj(o){return document.getElementById(o)};function getAttr(o,a){return document.getElementById(o).getAttribute(a)};function getValue(o){return document.getElementById(o).value};function setValue(o,v){document.getElementById(o).value=v};function setStyle(o,s,v){document.getElementById(o).style.s=v};function setHTML(o,h){document.getElementById(o).innerHTML=h};function getHTML(o){return document.getElementById(o).innerHTML};function ischecked(o){return document.getElementById(o).checked};function check(o,b){document.getElementById(o).checked=b};function disable(o,b){document.getElementById(o).disabled=b};function hasClass(o,c){return document.getElementById(o).className==c};function setClass(o,c){document.getElementById(o).className=c};function getElementsByClassName(classname){var a=[];var re=new RegExp('\\b'+classname+'\\b');var els=document.getElementsByTagName("*");for(var i=0,j=els.length;i<j;i++)if(re.test(els[i].className))a.push(els[i]);return a};function openWin(url,w,h,r,s){tinyMCE.openWindow({file:url,width:w,height:h,close_previous:"no"},{editor_id:tinyMCE.selectedInstance.editorId,inline:"yes",win:window,resizable:r,scrollbars:s})};function checkUpload(v,o){if(ischecked(v)){setValue(v,true);setValue(o,false);check(o,false)}if(!ischecked(v))setValue(v,false);if(!ischecked(o))setValue(o,false)};function showMessage(msg,img,className){setClass('msgContainer',className);setHTML('msgContainer','<span>'+msg+'</span>');setImg(img)};function setImg(i){document.getElementById('imgMsgContainer').src=jce.getLibImg(i)};function hide(o){var d=document;if(d.getElementById(o)){d.getElementById(o).style.display='none'}};function show(o){var d=document;if(d.getElementById(o)){d.getElementById(o).style.display='block'}};function getExtension(file){var regexp=/\/|\\/;var parts=file.split(regexp);var name=parts[parts.length-1].split(".");if(name.length<=1){return false}return name[name.length-1].toLowerCase()};function basename(path){var regexp=/\/|\\/;var parts=path.split(regexp);return parts[parts.length-1]};function dirname(path){var name=this.basename(path);var pos=path.length-(name.length+1);return path.substring(0,pos)};function stripExtension(file){var ext=getExtension(file);var pos=file.length-(ext.length+1);return file.substring(0,pos)};function trim(string){return string.replace(/^\s+|\s+$/g,'')};function makePath(pathA,pathB){if(pathA.substring(pathA.length-1)!='/')pathA+='/';if(pathB.charAt(0)=='/')pathB=pathB.substring(1);return pathA+pathB};function makeSafe(string){return string.replace(/[^A-Za-z0-9\.\_\-\s]/gi,'').replace(/\s/gi,'_').toLowerCase()};function parseQuery(query){var params=new Object();if(!query)return params;var pairs=query.split(/[;&?]/);for(var i=0;i<pairs.length;i++){var key_val=pairs[i].split('=');if(!key_val||key_val.length!=2)continue;var key=unescape(key_val[0]);var val=unescape(key_val[1]);val=val.replace(/\+/g,' ');params[key]=val}return params};function in_array(item,array){for(var i=0;i<array.length;i++){if(array[i]==item){return true}}return false}function selectByValue(field_name,value,add_custom,ignore_case){var f=document.forms[0];if(!f||!f.elements[field_name])return;var sel=f.elements[field_name];var found=false;for(var i=0;i<sel.options.length;i++){var option=sel.options[i];if(option.value==value||(ignore_case&&option.value.toLowerCase()==value.toLowerCase())){option.selected=true;found=true}else option.selected=false}if(!found&&add_custom&&value!=''){var option=new Option(value,value);option.selected=true;sel.options[sel.options.length]=option;sel.selectedIndex=sel.options.length-1}return found};function getSelectValue(name){var elm=document.forms[0].elements[name];if(elm==null||elm.options==null)return"";return elm.options[elm.selectedIndex].value}function addSelectValue(el,name,value){var s=document.forms[0].elements[el];var o=new Option(name,value);s.options[s.options.length]=o};function removeSelectValue(field_name,name,value){var s=document.forms[0].elements[field_name];for(var i=0;i<s.options.length;i++){if(s.options[i].value==value&&s.options[i].text==name){s.options[i]=null}}};function getSelectName(field_name){var n;var s=document.forms[0].elements[field_name];var v=s.options[s.selectedIndex].value;for(var i=0;i<s.options.length;i++){if(s.options[i].value==v){n=s.options[i].text}}return n};function convertURL(url,node,on_save){return eval("tinyMCEPopup.windowOpener."+tinyMCE.settings['urlconverter_callback']+"(url, node, on_save);")};function getColorPickerHTML(id,target_form_element){var themeBaseURL=tinyMCE.baseURL+'/themes/'+tinyMCE.getParam("theme");var h="";h+='<a id="'+id+'_link" href="javascript:void(0);" onkeydown="pickColor(event,\''+target_form_element+'\');" onmousedown="pickColor(event,\''+target_form_element+'\');return false;">';h+='<img id="'+id+'" src="'+themeBaseURL+'/images/color.gif"';h+=' onmouseover="this.className=\'mceButtonOver\'"';h+=' onmouseout="this.className=\'mceButtonNormal\'"';h+=' onmousedown="this.className=\'mceButtonDown\'"';h+=' width="20" height="16" border="0" title="'+tinyMCE.getLang('lang_browse')+'"';h+=' class="mceButtonNormal" alt="'+tinyMCE.getLang('lang_browse')+'" /></a>';return h};function pickColor(e,target_form_element){if((e.keyCode==32||e.keyCode==13)||e.type=="mousedown")tinyMCEPopup.pickColor(e,target_form_element)};function updateColor(img_id,form_element_id){document.getElementById(img_id).style.backgroundColor=document.forms[0].elements[form_element_id].value};function rgbToHex(col){var re=new RegExp("rgb\\s*\\(\\s*([0-9]+).*,\\s*([0-9]+).*,\\s*([0-9]+).*\\)","gi");var rgb=col.replace(re,"$1,$2,$3").split(',');if(rgb.length==3){r=parseInt(rgb[0]).toString(16);g=parseInt(rgb[1]).toString(16);b=parseInt(rgb[2]).toString(16);r=r.length==1?'0'+r:r;g=g.length==1?'0'+g:g;b=b.length==1?'0'+b:b;return"#"+r+g+b}return col};function hexToRgb(col){if(col.indexOf('#')!=-1){col=col.replace(new RegExp('[^0-9A-F]','gi'),'');r=parseInt(col.substring(0,2),16);g=parseInt(col.substring(2,4),16);b=parseInt(col.substring(4,6),16);return"rgb("+r+","+g+","+b+")"}return col};function addClassesToList(list_id,specific_option){var styleSelectElm=document.getElementById(list_id);var styles=tinyMCE.getParam('theme_advanced_styles',false);styles=tinyMCE.getParam(specific_option,styles);if(styles){var stylesAr=styles.split(';');for(var i=0;i<stylesAr.length;i++){if(stylesAr!=""){var key,value;key=stylesAr[i].split('=')[0];value=stylesAr[i].split('=')[1];styleSelectElm.options[styleSelectElm.length]=new Option(key,value)}}}else{var csses=tinyMCE.getCSSClasses(tinyMCE.getWindowArg('editor_id'));for(var i=0;i<csses.length;i++)styleSelectElm.options[styleSelectElm.length]=new Option(csses[i],csses[i])}};function trimSize(size){return size.replace(new RegExp('[^0-9%]','gi'),'')};function getCSSSize(size){size=trimSize(size);if(size=="")return"";return size.indexOf('%')!=-1?size:size+"px"};function getStyle(elm,attrib,style){var val=tinyMCE.getAttrib(elm,attrib);if(val!='')return''+val;if(typeof(style)=='undefined')style=attrib;val=eval('elm.style.'+style);return val==null?'':''+val};function setAttrib(elm,attrib,value){var valueElm=document.forms[0].elements[attrib];if(typeof(value)=="undefined"||value==null){value="";if(valueElm)value=valueElm.value}if(value!=""){elm.setAttribute(attrib,value);if(attrib=="style")attrib="style.cssText";if(attrib=="longdesc")attrib="longDesc";if(attrib=="width"){attrib="style.width";value=value+"px"}if(attrib=="height"){attrib="style.height";value=value+"px"}if(attrib=="class")attrib="className";if(attrib=="href")elm.setAttribute("mce_real_href",value);if(attrib.substring(0,2)=='on')value='return true;'+value;eval('elm.'+attrib+"=value;")}else elm.removeAttribute(attrib)};function makeAttrib(attrib,value){var valueElm=document.forms[0].elements[attrib];if(typeof(value)=="undefined"||value==null){value="";if(valueElm)value=valueElm.value}if(attrib=="align"){value=(value=="left"||value=="right")?"":value}if(value=="")return"";if(tinyMCE.getParam('encoding')=='xml'){value=value.replace(/&/g,'&amp;');value=value.replace(/\"/g,'&quot;');value=value.replace(/</g,'&lt;');value=value.replace(/>/g,'&gt;')}return' '+attrib+'="'+value+'"'};function xmlEncode(s){return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')};function xmlDecode(s){return s.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&quot;/g,'"')};function openHelp(){var lang=document.body.lang||'en';var url=tinyMCE.getParam('site')+'/index2.php?option=com_jce&no_html=1&pop=1&task=help&lang='+lang+'&plugin='+jce.getPlugin()+'&file=help.jce.php';openWin(url,750,600,'yes','yes');return false};function jcePlugin(){this.url=null;this.plugin=null;this.tree=null;this.lang='en';this.param=new Object;this.iframeFunction=function(){return false}};jcePlugin.prototype={setUrl:function(){var u=document.location.href;u=u.substring(0,u.lastIndexOf('/index2.php'));this.url=(u.lastIndexOf('/administrator')!=-1)?u.substring(0,u.lastIndexOf('/administrator')):u},set:function(p,v){this.param[p]=v},get:function(p){return this.param[p]},setPlugin:function(p){this.plugin=p},getPlugin:function(){return this.plugin},getTinyUrl:function(){return makePath(this.url,'/mambots/editors/jce/jscripts/tiny_mce')},getLibUrl:function(){return makePath(this.getTinyUrl(),'/libraries')},getPluginUrl:function(){return makePath(this.getTinyUrl(),'plugins/'+this.getPlugin())},getTinyImg:function(m){return this.getTinyUrl()+'/themes/advanced/images/'+m},getLibImg:function(m){return this.getLibUrl()+'/images/'+m},getPluginImg:function(m){return this.getPluginUrl()+'/images/'+m},getLang:function(s,t,d){if(t){s='lang_'+this.getPlugin()+'_'+s}else{s='lang_'+s}return tinyMCE.getLang(s,d,false)},getAjaxHTTP:function(){try{return new ActiveXObject('Msxml2.XMLHTTP')}catch(e){try{return new ActiveXObject('Microsoft.XMLHTTP')}catch(e){return new XMLHttpRequest()}}},sendAjax:function(u,f,m,a){var x=this.getAjaxHTTP();x.open(m,u,true);x.onreadystatechange=function(){if(x.readyState==4)f(x.responseXML,x.responseText)};if(m=='post')x.setRequestHeader('Content-type','application/x-www-form-urlencoded');x.send(a)},evalAjax:function(xml,txt){document.body.style.cursor='default';var script,regexp=/<script[^>]*>([\s\S]*?)<\/script>/gi;while((script=regexp.exec(txt)))eval(script[1])},ajaxSend:function(fn,v){document.body.style.cursor='wait';var args='&ajaxfn='+fn;if(v!=''){if(v instanceof Array){for(var i=0;i<v.length;i++){if(v[i]!=''){args+='&ajaxargs[]='+encodeURIComponent(v[i])}}}else{args+='&ajaxargs[]='+encodeURIComponent(v)}}var self=this;this.sendAjax(document.location.href,self.evalAjax,'post',args)}};var jce=new jcePlugin();jce.setUrl();