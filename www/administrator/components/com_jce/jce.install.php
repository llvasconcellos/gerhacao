<?php
// Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function com_install(){
//db operations
	global $database;
	
	$database->setQuery( "SELECT id FROM #__components WHERE name= 'JCE Admin'" );
	$id = $database->loadResult();

	//remove admin menu images
	$database->setQuery( "UPDATE #__components SET admin_menu_img = 'js/ThemeOffice/blank.png' WHERE parent = '$id'" );
	$database->query();

	//add new admin menu images
	$database->query();
	$database->setQuery( "UPDATE #__components SET admin_menu_img = 'js/ThemeOffice/controlpanel.png' WHERE parent='$id' AND name = 'JCE Configuration'");
	$database->query();
	$database->setQuery( "UPDATE #__components SET admin_menu_img = 'js/ThemeOffice/language.png' WHERE parent='$id' AND name = 'JCE Languages'");
	$database->query();
	$database->setQuery( "UPDATE #__components SET admin_menu_img = 'js/ThemeOffice/add_section.png' WHERE parent='$id' AND name = 'JCE Plugins'");
	$database->query();
	$database->setQuery( "UPDATE #__components SET admin_menu_img = 'js/ThemeOffice/content.png' WHERE parent='$id' AND name = 'JCE Layout'");
	$database->query();
	
	//Install Default English Language
	$database->setQuery( "INSERT INTO #__jce_langs VALUES ('', 'English', 'en', '1')" );
	$database->query();
	
	//Install Default Plugins
	//id, name, plugin/command, type, icon, layout_icon, access, row, ordering, published, editable, elements, iscore, clientid, checked out, checked out time, params.
	//Plugins
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Context Menu', 'contextmenu', 'plugin', '', '', 18, 0, 0, 0, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Directionality', 'directionality', 'plugin', 'ltr,rtl', 'directionality', 18, 3, 8, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Emotions', 'emotions', 'plugin', 'emotions', 'emotions', 18, 4, 12, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Fullscreen', 'fullscreen', 'plugin', 'fullscreen', 'fullscreen', 18, 4, 6, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Paste', 'paste', 'plugin', 'pasteword,pastetext', 'paste', 18, 1, 16, 1, 1, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Preview', 'preview', 'plugin', 'preview', 'preview', 18, 4, 1, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Tables', 'table', 'plugin', 'tablecontrols', 'buttons', 18, 2, 8, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Print', 'print', 'plugin', 'print', 'print', 18, 4, 3, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Search Replace', 'searchreplace', 'plugin', 'search,replace', 'searchreplace', 18, 1, 17, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();

	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Styles', 'style', 'plugin', 'styleprops', 'styleprops', 18, 4, 7, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Non-Breaking', 'nonbreaking', 'plugin', 'nonbreaking', 'nonbreaking', 18, 4, 8, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Visual Characters', 'visualchars', 'plugin', 'visualchars', 'visualchars', 18, 4, 9, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'XHTML Xtras', 'xhtmlxtras', 'plugin', 'cite,abbr,acronym,del,ins,attribs', 'xhtmlxtras', 18, 4, 10, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Image Manager', 'imgmanager', 'plugin', '', 'imgmanager', 18, 4, 13, 1, 1, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Advanced Link', 'advlink', 'plugin', '', 'advlink', 18, 4, 14, 1, 1, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Spell Checker', 'spellchecker', 'plugin', 'spellchecker', 'spellchecker', 18, 4, 15, 1, 1, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Layers', 'layer', 'plugin', 'insertlayer,moveforward,movebackward,absolute', 'layer', 18, 4, 11, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    //Commands
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Font ForeColour', 'forecolor', 'command', 'forecolor', 'forecolor', 18, 3, 4, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Bold', 'bold', 'command', 'bold', 'bold', 18, 1, 5, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Italic', 'italic', 'command', 'italic', 'italic', 18, 1, 6, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Underline', 'underline', 'command', 'underline', 'underline', 18, 1, 7, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Font BackColour', 'backcolor', 'command', 'backcolor', 'backcolor', 18, 3, 5, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Unlink', 'unlink', 'command', 'unlink', 'unlink', 18, 2, 11, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Font Select', 'fontselect', 'command', 'fontselect', 'fontselect', 18, 3, 2, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Font Size Select', 'fontsizeselect', 'command', 'fontsizeselect', 'fontsizeselect', 18, 3, 3, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Style Select', 'styleselect', 'command', 'styleselect', 'styleselect', 18, 3, 1, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'New Document', 'newdocument', 'command', 'newdocument', 'newdocument', 18, 1, 4, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Help', 'help', 'command', 'help', 'help', 18, 1, 3, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'StrikeThrough', 'strikethrough', 'command', 'strikethrough', 'strikethrough', 18, 1, 12, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Indent', 'indent', 'command', 'indent', 'indent', 18, 1, 11, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Outdent', 'outdent', 'command', 'outdent', 'outdent', 18, 1, 10, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Undo', 'undo', 'command', 'undo', 'undo', 18, 1, 1, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Redo', 'redo', 'command', 'redo', 'redo', 18, 1, 2, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Horizontal Rule', 'hr', 'command', 'hr', 'hr', 18, 2, 1, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'HTML', 'html', 'command', 'code', 'code', 18, 1, 13, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Numbered List', 'numlist', 'command', 'numlist', 'numlist', 18, 1, 9, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Bullet List', 'bullist', 'command', 'bullist', 'bullist', 18, 1, 8, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Clipboard Actions', 'clipboard', 'command', 'cut,copy,paste', 'clipboard', 18, 1, 16, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Subscript', 'sub', 'command', 'sub', 'sub', 18, 2, 2, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Superscript', 'sup', 'command', 'sup', 'sup', 18, 2, 3, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Visual Aid', 'visualaid', 'command', 'visualaid', 'visualaid', 18, 3, 7, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Character Map', 'charmap', 'command', 'charmap', 'charmap', 18, 3, 6, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Justify Full', 'full', 'command', 'justifyfull', 'justifyfull', 18, 2, 7, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Justify Center', 'center', 'command', 'justifycenter', 'justifycenter', 18, 2, 5, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Justify Left', 'left', 'command', 'justifyleft', 'justifyleft', 18, 2, 6, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Justify Right', 'right', 'command', 'justifyright', 'justifyright', 18, 2, 4, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Remove Format', 'removeformat', 'command', 'removeformat', 'removeformat', 18, 1, 15, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Anchor', 'anchor', 'command', 'anchor', 'anchor', 18, 2, 9, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
    $database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Format Select', 'formatselect', 'command', 'formatselect', 'formatselect', 18, 3, 9, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Image', 'image', 'command', 'image', 'image', 18, 4, 1, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
	$database->setQuery( "INSERT INTO #__jce_plugins VALUES ('', 'Link', 'link', 'command', 'link', 'link', 18, 4, 1, 1, 0, '', 1, 0, 0, '', '')" );
    $database->query();
}
?>
