<?php
/**
* @version $Id: toolbar.mosce.html.php,v 1.0 2005/02/27 22:15:00 Ryan Demmer$
* @package mosCE Admin
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Mambo_4.5.1
*/
class TOOLBAR_JCE {
        /**
    	* Writes a common 'publish' button
    	* @param string An override for the task
    	* @param string An override for the alt text
    	*/
    	function accessButton( $task='applyaccess', $alt='Access' ) {
    		$image2 = mosAdminMenus::ImageCheckAdmin( 'security_f2.png', '/administrator/images/', NULL, NULL, $alt, $task, 1 );
    		?>
    	 	<td>
    			<a class="toolbar" href="javascript:if (document.adminForm.boxchecked.value == 0){ alert('Please make a selection from the list to apply Access Level to'); } else {submitbutton('<?php echo $task;?>', '');}">
    				<?php echo $image2; ?>
    				<br /><?php echo $alt; ?></a>
    		</td>
    	 	<?php
	   }
	   function helpButton( $section, $alt='Help') {
	   		$image2 = mosAdminMenus::ImageCheckAdmin( 'help_f2.png', '/administrator/images/', NULL, NULL, $alt, '', 1 );
			?>
    	 	<td>
    			<a class="toolbar" href="javascript:void(0);" onclick="window.open('http://www.cellardoor.za.net/index2.php?option=com_content&amp;task=findkey&amp;pop=1&amp;keyref=<?php echo $section;?>', 'Help', 'width=750,height=500,top=20,left=20,scrollbars=yes,resizable=yes');">
    				<?php echo $image2; ?>
    				<br /><?php echo $alt; ?></a>
    		</td>
    	 	<?php
	   }
        function _CONFIG() {
                mosMenuBar::startTable();
                mosMenuBar::save();
                mosMenuBar::custom('main', 'back.png', 'back_f2.png', 'Main', false);
                mosMenuBar::spacer();
                mosMenuBar::cancel();
                mosMenuBar::endTable();
        }
        function _PLUGINS() {
    		mosMenuBar::startTable();
    		mosMenuBar::publishList();
    		mosMenuBar::spacer();
    		mosMenuBar::unpublishList();
    		mosMenuBar::spacer();
    		mosMenuBar::custom('newplugin', 'new.png', 'new_f2.png', 'New', false);
    		mosMenuBar::spacer();
    		mosMenuBar::custom('installplugin', 'upload.png', 'upload_f2.png', 'Install',false);
    		mosMenuBar::spacer();
    		mosMenuBar::custom('editlayout', 'css.png', 'css_f2.png', 'Layout',false);
    		mosMenuBar::spacer();
    		TOOLBAR_JCE::accessButton();
    		mosMenuBar::spacer();
			TOOLBAR_JCE::helpButton('admin.plugins.view');
			mosMenuBar::spacer();
    		mosMenuBar::custom('cancel', 'cancel.png', 'cancel_f2.png', 'Cancel', false);
    		mosMenuBar::endTable();
        }
        function _EDIT_PLUGINS() {
    		global $id;

    		mosMenuBar::startTable();
    		mosMenuBar::custom('saveplugin', 'save.png', 'save_f2.png', 'Save', false);
    		mosMenuBar::spacer();
    		if ( $id ) {
    			// for existing content items the button is renamed `close`
    			mosMenuBar::custom('canceledit', 'cancel.png', 'cancel_f2.png', 'Close', false);
    		} else {
                mosMenuBar::custom('canceledit', 'cancel.png', 'cancel_f2.png', 'Cancel', false);
    		}
    		mosMenuBar::spacer();
    		mosMenuBar::endTable();
    	}
    	function _INSTALL( $element ) {
            if( $element == 'plugins' ){
                mosMenuBar::startTable();
                mosMenuBar::custom('showplugins', 'new.png', 'new_f2.png', 'Plugins', false);
                mosMenuBar::spacer();
                mosMenuBar::custom('removeplugin', 'delete.png', 'delete_f2.png', 'Uninstall', false);
                mosMenuBar::spacer();
				TOOLBAR_JCE::helpButton('admin.plugins.install');
				mosMenuBar::spacer();
                mosMenuBar::custom('cancel', 'cancel.png', 'cancel_f2.png', 'Cancel', false);
    		    mosMenuBar::endTable();
            }
        }
        function _LAYOUT() {
    		mosMenuBar::startTable();
    		mosMenuBar::custom('savelayout', 'save.png', 'save_f2.png', 'Save', false);
    		mosMenuBar::spacer();
			TOOLBAR_JCE::helpButton('admin.layout');
			mosMenuBar::spacer();
    		mosMenuBar::custom('cancel', 'cancel.png', 'cancel_f2.png', 'Cancel', false);
    		mosMenuBar::endTable();
        }
        function _LANGS() {
    		mosMenuBar::startTable();
    		mosMenuBar::publishList('publishlang');
    		mosMenuBar::spacer();
    		mosMenuBar::custom('removelang', 'delete.png', 'delete_f2.png', 'Delete', false);
    		mosMenuBar::spacer();
    		mosMenuBar::custom('newlang', 'upload.png', 'upload_f2.png', 'Install',false);
			mosMenuBar::spacer();
			TOOLBAR_JCE::helpButton('admin.languages');
			mosMenuBar::spacer();
    		mosMenuBar::custom('cancel', 'cancel.png', 'cancel_f2.png', 'Cancel', false);
    		mosMenuBar::endTable();
        }
}
?>
