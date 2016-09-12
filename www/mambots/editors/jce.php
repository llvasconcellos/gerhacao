<?php
/**
* @version $Id: jce.php,v 1.1.1 2007/06/04$
* @package Joomla 1.0.x
* @Based on tinymce.php by stingrey
* @copyright (C) 2006 - 2007 Ryan Demmer
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$_MAMBOTS->registerFunction( 'onInitEditor', 'jceEditorInit' );
$_MAMBOTS->registerFunction( 'onGetEditorContents', 'jceEditorGetContents' );
$_MAMBOTS->registerFunction( 'onEditorArea', 'jceEditorArea' );

/**
* TinyMCE WYSIWYG Editor - javascript initialisation
*/
function jceEditorInit() {
        global $my, $database, $mainframe;

		require_once( $mainframe->getCfg('absolute_path') . "/mambots/editors/jce/jscripts/tiny_mce/libraries/classes/jce.class.php" );

		$jce = new JCE();
        $isSuperAdmin = $jce->isAdmin();
        $userid = $jce->id;
		$params = $jce->getParams();
		
		$editor_state 		= $params->get( 'editor_state', 'mceEditor' );
		$editor_toggle 		= $params->get( 'editor_toggle', '[show/hide]' );   	
    	$toolbar 			= $params->get( 'toolbar', 'top' );
    	$html_height		= $params->get( 'html_height', '550' );
    	$html_width			= $params->get( 'html_width', '750' );
    	$text_direction		= $params->get( 'text_direction', 'ltr' );
    	$invalid_elms	    = $params->get( 'invalid_elements', 'applet' );
    	$event_elms	        = $params->get( 'event_elements', '' );   	
    	$preview_height		= $params->get( 'preview_height', '550' );
	    $preview_width		= $params->get( 'preview_width', '750' );
	    $editor_width       = $params->get( 'editor_width', '500' );
        $editor_height      = $params->get( 'editor_height', '600' );
        $font_size_type     = $params->get( 'font_size_type', 'length' );
		$template_colors	= $jce->cleanParam( $params->get( 'template_colors', '' ) );
		$entity_encoding	= $params->get( 'entity_encoding', 'named' );
		
		$relative 			= $jce->getBool( $params->get( 'relative_url', '1' ) );
		$verify_html		= $jce->getBool( $params->get( 'verify_html', '0' ) );
		$mambot_mode		= $jce->getBool( $params->get( 'mambot_mode', '0' ) );        
        $table_inline 		= $jce->getBool( $params->get( 'table_inline', 0 ) );
		$fix_list_elements 	= $jce->getBool( $params->get( 'fix_list_elements', 1 ) );
		$fix_table_elements = $jce->getBool( $params->get( 'fix_table_elements', 1 ) );			

		$editor_resizing 			= $jce->getBool( $params->get( 'editor_resizing', 1 ) );
		$editor_resizing_cookie 	= $jce->getBool( $params->get( 'editor_resizing_cookie', 1 ) );

        //Plugin settings and Authorisation
		$query = "SELECT template"
		. "\n FROM #__templates_menu"
		. "\n WHERE client_id = 0"
		. "\n AND menuid = 0"
		;
		$database->setQuery( $query );
		$template = $database->loadResult();
		
        $template_path = $mainframe->getCfg('live_site') . "/templates/" . $template . "/css";
		$css_template = $template_path . "/template_css.css";
		
		if( $params->get( 'content_css', 1 ) == 0 ){
			$css_template = $template_path . "/" . $params->get( 'content_css_custom', '' );
		}
		
		$invalid_elements[] = $invalid_elms; 
        $elements = $jce->getElements(); 
		
		if( !$jce->getAuthOption( 'allow_script', '0' ) ){
			$invalid_elements[] = 'script';
		} else{
			$elements = $jce->addKey( $elements, 'script[*]', ',' );
			$jce->removeKey( $invalid_elements, 'script' );
		}
		//Mutually exclusive plugins 
		$me_plugins = array('imgmanager', 'advlink');
		
		$paste_values = "";
		if( $jce->isLoaded( 'paste' ) ){
			$paste_params = $jce->getPluginParams( 'paste' );
			$paste_values = "		paste_create_paragraphs : " . $jce->getBool( $paste_params->get( 'paste_create_paragraphs', 'false' ) ) . ",\n";
			$paste_values .= "		paste_create_linebreaks : " . $jce->getBool( $paste_params->get( 'paste_create_linebreaks', 'false' ) ) . ",\n";
			$paste_values .= "		paste_use_dialog : " . $jce->getBool( $paste_params->get( 'paste_use_dialog', 'false' ) ) . ",\n";
			$paste_values .= "		paste_auto_cleanup_on_paste : " . $jce->getBool( $paste_params->get( 'paste_auto_cleanup_on_paste', 'false' ) ) . ",\n";
			$paste_values .= "		paste_strip_class_attributes : \"" . $paste_params->get( 'paste_strip_class_attributes', 'all' ) . "\",\n";
			$paste_values .= "		paste_remove_spans : " . $jce->getBool( $paste_params->get( 'paste_remove_spans', 'true' ) ) . ",\n";
			$paste_values .= "		paste_remove_styles : " . $jce->getBool( $paste_params->get( 'paste_remove_styles', 'true' ) ) . ",";
		}
		$media_values = "";
		if( $jce->isLoaded( 'mediamanager' ) ){
			$mm_params = $jce->getPluginParams( 'mediamanager' );
			$mm_use_script = $jce->getBool( $mm_params->get( 'use_script', '0' ) );
			$media_values = "		media_use_script : " . $mm_use_script . ",";
			if( $mm_use_script && !$jce->getAuthOption( 'allow_script', '0' ) ){
				$elements = $jce->addKey( $elements, 'script[*]', ',' );
				$jce->removeKey( $invalid_elements, 'script' );
			}
		}
		$template_values = "";
		if( $jce->isLoaded( 'templatemanager' ) ){
			$tpl_params = $jce->getPluginParams( 'templatemanager' );
			$rv = $jce->cleanParam( $tpl_params->get( 'replace_values', '' ) );
			if( strpos( $rv, ',' ) == strlen( $rv ) ){
				$rv = substr( $rv, 0, -1 );
			}
			$template_values = "		template_replace_values : {" . $rv . "},\n";
			$template_values .= "		template_selected_content_classes : \"" . $tpl_params->get( 'selected_content_classes', '' ) . "\",\n";
			$template_values .= "		template_cdate_classes : \"" . $tpl_params->get( 'cdate_classes', 'cdate creationdate' ) . "\",\n";
			$template_values .= "		template_mdate_classes : \"" . $tpl_params->get( 'mdate_classes', 'mdate modifieddate' ) . "\",\n";
			$template_values .= "		template_cdate_format : \"" . $tpl_params->get( 'cdate_format', '%m/%d/%Y : %H:%M:%S' ) . "\",\n";
			$template_values .= "		template_mdate_format : \"" . $tpl_params->get( 'mdate_format', '%m/%d/%Y : %H:%M:%S' ) . "\",";
		}
		$spell_params = $jce->getPluginParams( 'spellchecker' );
		$spell_languages = '+' . $spell_params->get( 'languages', 'English=en' );

        $plugins = $jce->getPlugins( $me_plugins );
		$remove_buttons = $jce->getRemovePlugins();
        
        $row1 = $jce->getRow( 1 );
		$row2 = $jce->getRow( 2 );
		$row3 = $jce->getRow( 3 );
		$row4 = $jce->getRow( 4 );
		$row5 = $jce->getRow( 5 );
    
        $jce_langs = $jce->getLangs();
		$jce_curr_lang = $jce->getLanguage();
		$invalid_elements = implode( ',', $invalid_elements );

	    $br_newlines = ( $params->get( 'newlines', '0' ) == '1' ) ? 'true' : 'false';
		$p_newlines	 = ( $params->get( 'newlines', '0' ) == '0' ) ? 'true' : 'false';
		
    	$font_size_type = ( $font_size_type == 'length' ) ? '8pt,10pt,12pt,14pt,18pt,24pt,36pt' : 'xx-small,x-small,small,medium,large,x-large,xx-large';
        
		$base_url = $mainframe->getCfg('live_site');
		$tiny_url = $jce->getTinyUrl();
				
		if( $params->get('compression', '0') ){
			$tiny_file = 'tiny_mce_gzip.js';
			$gzip_init = "<script type=\"text/javascript\">\n";
			$gzip_init .= "	tinyMCE_GZ.init({\n";
			$gzip_init .= "	plugins : '" . $plugins . "',\n";
			$gzip_init .= "	themes : 'advanced',\n";
			$gzip_init .= "	languages : '" . $jce_curr_lang . "',\n";
			$gzip_init .= "	disk_cache : false,\n";
			$gzip_init .= "	debug : false\n";
			$gzip_init .= "});\n";
			$gzip_init .= "</script>\n"; 
		}else{
			$tiny_file = 'tiny_mce.js';
			$gzip_init = '';
		}
		
	$return = "var site_url = jceFunctions.getURL();
	jceFunctions.relative = $relative;
	jceFunctions.mambotMode = " . $mambot_mode . ";
	jceFunctions.state = \"" . $editor_state . "\";
	jceFunctions.toggle = \"" . $editor_toggle . "\";
	tinyMCE.init({
		site : site_url,
		document_base_url: \"" . $base_url . "/\",
		theme : \"advanced\",
		language : \"" . $jce_curr_lang . "\",
		lang_list : \"" . $jce_langs . "\",
		width : \"" . $editor_width . "\",
		height : \"" . $editor_height . "\",
		mode : \"specific_textareas\",
		browsers : \"msie,safari,gecko,opera\",
		event_elements : \"" . $event_elms . "\",
		entity_encoding : \"" . $entity_encoding . "\",
		verify_html : " . $verify_html . ",
		relative_urls : false,
		remove_script_host : false,
		remove_linebreaks : false,
		apply_source_formatting : true,
		convert_fonts_to_spans : true,
		inline_styles : true,
		fix_list_elements : " . $fix_list_elements . ",
		fix_table_elements : " . $fix_table_elements . ",
		save_callback : \"jceSave\",
		content_css : \"" . $css_template . "\",
		template_colors : \"" . $template_colors . "\",\n";
		if( $paste_values ){
		$return .= "" . $paste_values . "\n";
		}
		if( $media_values ){
		$return .= "" . $media_values . "\n";
		}
		if( $template_values ){
		$return .= "" . $template_values . "\n";
		}
		$return .= "		spellchecker_languages : \"" . $spell_languages . "\",
		font_size_style_values : \"" . $font_size_type . "\",
		table_inline_editing : " . $table_inline . ",
		invalid_elements : \"" . $invalid_elements . "\",
		force_br_newlines : " . $br_newlines . ",
		force_p_newlines : " . $p_newlines . ",
		directionality : \"" . $text_direction . "\",
		theme_advanced_layout_manager : \"SimpleLayout\",
		theme_advanced_resizing : " . $editor_resizing . ", 
		theme_advanced_resizing_use_cookie : " . $editor_resizing_cookie . ",
		theme_advanced_toolbar_location : \"top\",
		theme_advanced_statusbar_location : \"bottom\",
		theme_advanced_disable : \"" . $remove_buttons . "\",
		theme_advanced_buttons1 : \"" . $row1 . "\",
		theme_advanced_buttons2 : \"" . $row2 . "\",
		theme_advanced_buttons3 : \"" . $row3 . "\",
		theme_advanced_buttons4 : \"" . $row4 . "\",
		theme_advanced_buttons5 : \"" . $row5 . "\",
		theme_advanced_blockformats : \"p,div,h1,h2,h3,h4,h5,h6,blockquote,dt,dd,code,samp\",
		plugins : \"" . $plugins . "\",
		extended_valid_elements : \"" . $elements . "\"
	});\n";
?>
<!--//TinyMCE/JCE-->
<script type="text/javascript" src="<?php echo $tiny_url;?>/<?php echo $tiny_file;?>"></script>
<script type="text/javascript" src="<?php echo $tiny_url;?>/jce.functions.js"></script>
<?php echo $gzip_init;?>
<script type="text/javascript">
	<?php echo $return;?>
	function jceSave(element_id, html, body){
		return jceFunctions.save(html);
	};
</script>
<!--//End TinyMCE/JCE-->
<?php }
/**
* TinyMCE WYSIWYG Editor - copy editor contents to form field
* @param string The name of the editor area
* @param string The name of the form field
*/
function jceEditorGetContents( $editorArea, $hiddenField ){?>
	tinyMCE.triggerSave();
<?php
}
/**
* mosce WYSIWYG Editor - display the editor
* @param string The name of the editor area
* @param string The content of the field
* @param string The name of the form field
* @param string The width of the editor area
* @param string The height of the editor area
* @param int The number of columns for the editor area
* @param int The number of rows for the editor area
*/
function jceEditorArea( $name, $content, $hiddenField, $width, $height, $col, $row ) {
global $_MAMBOTS, $mainframe;
        
	$results = $_MAMBOTS->trigger( 'onCustomEditorButton' );
	$buttons = array();
	foreach( $results as $result ){
		$buttons[] = '<img src="'.$mainframe->getCfg('live_site').'/mambots/editors-xtd/'.$result[0].'" onclick="tinyMCE.execCommand(\'mceInsertContent\',false,\''.$result[1].'\')" />';
	}
	$buttons = implode( "", $buttons );
?>
	<a id="editor_toggle" href="javascript:jceFunctions.toggleEditor('<?php echo $hiddenField;?>');">[show/hide]</a>
	<textarea id="<?php echo $hiddenField;?>" name="<?php echo $hiddenField;?>" cols="<?php echo $col;?>" rows="<?php echo $row;?>" style="width:<?php echo $width;?>px; height:<?php echo $height;?>px;" mce_editable="true" class="mceEditor"><?php echo $content;?></textarea>
	<script type="text/javascript">
		jceFunctions.initEditorMode('<?php echo $hiddenField;?>');
	</script>
	<br />
	<?php echo $buttons;?>
<?php }?>