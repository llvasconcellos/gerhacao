<?php
/**
* @version $Id: help.php 2005-12-27 09:23:43Z Ryan Demmer $
* @package JCE
* @copyright Copyright (C) 2005 Ryan Demmer. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
defined( '_VALID_MOS' ) or die( 'Restricted Access.' );

$version = "1.1.0";

require_once( $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/libraries/classes/jce.class.php' );
require_once( $mainframe->getCfg('absolute_path') . '/mambots/editors/jce/jscripts/tiny_mce/libraries/classes/jce.utils.class.php' );
$jce = new JCE();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php $cl['iso'];?>" />
	<title><?php echo $pl['title'].'&nbsp;'.$cl['help'];?></title>
	<?php echo $jce->printLibJs( 'dtree' );?>
	<link href="<?php echo $jce->getTinyUrl();?>/themes/advanced/css/editor_popup.css" rel="stylesheet" type="text/css" />
	<?php echo $jce->printLibCss( 'common' );?>
	<?php echo $jce->printLibCss( 'dtree' );?>
</head>
<body>
	<div id="helpTree"></div>
<?php
	$params = $jce->getParams();
	$helpurl = strval( $params->get( 'help_url', 'http://www.cellardoor.za.net' ) );
	
	$lang = $jce->getLanguage();	
	include_once( $jce->getLibPath() . '/langs/' . $lang . '.php' );
	
	$plugin = mosGetParam( $_REQUEST, 'plugin', '' );
	$jce->setPlugin( $plugin );
	include_once( $jce->getPluginPath() . '/langs/' .  $jce->getPluginLanguage() . '.php' );
	
	$fullhelpurl = $helpurl . '/index2.php?option=com_content&amp;task=findkey&amp;pop=1&amp;lang=' . $lang . '&amp;keyref=';
	?>
			<script type="text/javascript">
				function loadFrame(h){
					parent.document.getElementById('helpFrame').src = h;
				}
				var lib_url = "<?php echo $jce->getLibUrl();?>";
				d = new dTree('d', lib_url);
				d.icon.root = lib_url + '/images/help.gif';
				d.icon.folder = lib_url + '/images/book.gif';
				d.icon.folderOpen = lib_url + '/images/book_open.gif';
				d.icon.node = lib_url + '/images/page.gif';
				d.add(0,-1,'<?php echo $cl["index"];?>');					
				<?php 
				$x = 2;
				if( strpos( $plugin, 'manager' ) ){?>
					d.add(1,0,'<?php echo $cl["common"];?>');
				<?php
					foreach( $ch as $k=>$v ) {
						$url = $fullhelpurl . urlencode( 'manager.' . $k );
					?>
						d.add(<?php echo $x;?>, 1, '<?php echo $v;?>', 'javascript:loadFrame(\'<?php echo $url;?>\');', '<?php echo $v;?>');
					<?php 	
						$x++;
					}
				}
				$i = $x;
				$x++;
				?>
				d.add(<?php echo $i;?>,0,'<?php echo $cl["plugin_specific"];?>','');
				<?php 
					foreach ($ph as $k=>$v) {
						$url = $fullhelpurl . urlencode( $plugin . '.' . $k );
				?>
						d.add(<?php echo $x;?>, <?php echo $i;?>, '<?php echo $v;?>', 'javascript:loadFrame(\'<?php echo $url;?>\');', '<?php echo $v;?>');
				<?php 	
						$x++;
					}?>	
				document.getElementById('helpTree').innerHTML = d;
			</script>
</body>
</html>
