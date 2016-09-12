<?php
/**
* @version $Id: toolbar.tinymce_exp.php,v 1.0 2005/02/27 22:15:00 Ryan Demmer$
* @package TinyMCE-EXP Admin
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

$element = mosGetParam( $_REQUEST, 'element', '' );

switch ( $task ) {
        case 'default':
                //TOOLBAR_mosceConfig::_CONFIG();
        break;
        case 'config':
                TOOLBAR_JCE::_CONFIG();
        break;
        case 'plugins':
        case 'showplugins':
        case 'cancelaccess':
                TOOLBAR_JCE::_PLUGINS();
        break;
        case 'newplugin':
        case 'editplugin':
        case 'editpluginA':
                TOOLBAR_JCE::_EDIT_PLUGINS();
        break;
        case 'canceledit':
                TOOLBAR_JCE::_PLUGINS();
        break;
        case 'install':
                TOOLBAR_JCE::_INSTALL( $element );
        break;
        case 'editlayout':
        case 'savelayout':
                TOOLBAR_JCE::_LAYOUT();
        break;
        case 'lang':
                TOOLBAR_JCE::_LANGS();
        break;
}
?>
