<?php header("Content-type: text/css"); ?>
<?php
$template_path = dirname( dirname( $_SERVER['SCRIPT_NAME'] ) );
?>


/** IE6 is a hunk of crap!!! due to limitations in the CSS capabilities of IE, these hacks are required **/

/* font tweaking for optima/lucida font */
#ff-optima h1,#ff-optima h2,#ff-optima h3,#ff-optima h4,#ff-optima h5,#ff-optima h6,
#ff-lucida h1,#ff-lucida h2,#ff-lucida h3,#ff-lucida h4,#ff-lucida h5,#ff-lucida h6 {
	letter-spacing: -0.07em;
}

body#ff-optima ,
body#ff-lucida {
	letter-spacing: -0.03em;
}

body#ff-georgia,
body#ff-georgia.f-default {
	font-size: 12px;
}

/* menu fixes */


/** end **/

#featured-shadow .wrapper {
	margin-left: 83px;
}

#top-tab {
	margin-right: 39px;
}

#accessibility-section {
	width: 120px;
}

.rotator-title {
	line-height: 45px;
}


.rok-content-rotator,
.rok-content-rotator .rotator-2,
.rok-content-rotator .rotator-3,
.rok-content-rotator .rotator-4 {
	zoom: 1;
}

.rok-content-rotator div.clr {
	display: none;
}


.rok-content-rotator div.content {
	width: 360px;
	background: #f1f1f1;
	margin-left: 4px;
	padding-bottom: 0;
}

.rok-content-rotator h2 {
	text-indent: 20px;
}

.rok-content-rotator li {
	display: block;
	float: left;
}

.rok-content-rotator .arrow {
	right: 26px;
}

span.pathway {
	display: block;
	float: left;
	line-height: 27px;
}

span.pathway a {
	display: block;
	float: left;
}

span.pathway img {
	vertical-align: middle;
	display: block;
	float: left;
}

#moduleslider-size {
	background-position: 50% 170px;
}

#header {
	position: relative;
	z-index: 1;
}

#showcase, #body-bg {
	position: relative;
	z-index: 0;
}

#bottom {
	zoom: 1;
}

/* login fixes */

#sl_vert {
	zoom: 1;
	margin-bottom: -20px;
}

#sl_username, #sl_pass {
	padding-bottom: 0px;
}

#sl_submitbutton {
	top: 32px;
	right: 20px;
}

/* rokslide fixes */

#rokslide-toolbar li {
	float: left;
}

#rokslide-toolbar li span {
	float: left;
	padding: 0 30px;
}

.spacer.w99 .block {
	width: 99.99%;
}

.spacer.w49 .block {
	width: 49.99%;
}

.spacer.w33 .block {
	width: 33%;
}

.spacer.w24 .block {
	width: 24.9%;
}

#frame .mmpr-2 .module {
   width: 46%;
}

#frame .mmpr-3 .module {
   width: 30%;
}

#tabmodules div div div {
	height: 1%;
}

#rokslide-toolbar span {
	padding: 0 15px;
}

/* ie6 warning */

#iewarn {
	background: #C6D3DA url(../images/error.png) 10px 20px no-repeat;
	position: relative;
	z-index: 1;
	opacity: 0;
	margin: -150px auto 0;
	font-size: 110%;
	color: #001D29;
}

#iewarn div {
	position: relative;
	border-top: 5px solid #95B8C9;
	border-bottom: 5px solid #95B8C9;
	padding: 10px 80px 10px 220px;	
}

#iewarn h4 {
	color: #900;
	font-weight: bold;
	line-height: 120%;
}

#iewarn a {
	color: #296AC6;
	font-weight: bold;
}

#iewarn_close {
	background: url(../images/close.png) 50% 50% no-repeat;
	display: block;
	cursor: pointer;
	position: absolute;
	width: 61px;
	height: 21px;
	top: 25px;
	right: 12px;
}

#iewarn_close.cHover {
	background: url(../images/close_hover.png) 50% 50% no-repeat;
}

/* end ie6 warning */

#sl_horiz .button {
	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $template_path; ?>/images/login-button.png', sizingMethod='crop');
   	background-image: none;
	zoom: 1;
}

/*
   NEW PURE CSS PNG FIX SOLUTION  
   use class="png" to implement 
*/

html .png,
div .png {
	azimuth: expression(
		this.pngSet?this.pngSet=true:(this.nodeName == "IMG" && this.src.toLowerCase().indexOf('.png')>-1?(this.runtimeStyle.backgroundImage = "none",
		this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.src + "', sizingMethod='image')",
		this.src = "<?php echo $template_path; ?>/images/blank.gif"):(this.origBg = this.origBg? this.origBg :this.currentStyle.backgroundImage.toString().replace('url("','').replace('")',''),
		this.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + this.origBg + "', sizingMethod='crop')",
		this.runtimeStyle.backgroundImage = "none")),this.pngSet=true
	);
}

/* page peel overrides for demo site */
a.fliptip {
	display: block;
	z-index: 100000;
	position: relative;
}


