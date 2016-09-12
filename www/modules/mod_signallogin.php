<?php
/**
* Alternative flexible Login-Module 1.0b
* $Id: mod_signallogin.php 100 2008-02-17 14:36:00 chris $
*
* @version 1.0 Beta
* @copyright (C) 2008 Chris Schafflinger
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $sl_embedded, $my, $mosConfig_lang, $mainframe, $mosConfig_live_site, $_SERVER, $Itemid, $mosConfig_frontend_login;

$absolute_path			= 	$mainframe->getCfg('absolute_path');
$registration_enabled 	= 	$mainframe->getCfg( 'allowUserRegistration' );

if ( $mosConfig_frontend_login != NULL && ($mosConfig_frontend_login === 0 || $mosConfig_frontend_login === '0')) {
	return;
}

// url of current page that user will be returned to after login
if ($query_string = mosGetParam( $_SERVER, 'QUERY_STRING', '' )) {
	$return = 'index.php?' . $query_string;
} else {
	$return = 'index.php';
}

// converts & to &amp; for xtml compliance
$return 				= str_replace( '&', '&amp;', $return );

// Get Module-Config
$theme				= 	$params->get( 'module_theme', 'default' );
$horizontal 		=	$params->def( 'horizontal', '0' );
$pretext 			=	$params->def( 'pretext' );
$posttext 			=	$params->def( 'posttext' );
$login 				= 	$params->def( 'login', $return );
$logout 			= 	$params->def( 'logout', $return );
$show_lostpass 		=	$params->def( 'show_lostpass', 1 );
$show_newaccount 	=	$params->def( 'show_newaccount', 1 );
$name_length 		=	$params->def( 'name_length', '10' );
$pass_length 		=	$params->def( 'pass_length', '10' );
$message_login 		= 	$params->def( 'login_message', 	0 );
$message_logout 	= 	$params->def( 'logout_message', 0 );
$remember_enabled	=	$params->def( 'remember_enabled', 1 );
$greeting 			= 	$params->def( 'greeting', '1' );
$name 				= 	$params->def( 'name', 0 );

if(!$sl_embedded && $theme!="none"){			//only embed Stylesheet once
	echo "<script type = \"text/javascript\">\n
		  <!--\n
		  var link = document.createElement('link');\n
		  link.setAttribute('href', '".$mosConfig_live_site."/modules/mod_signallogin/".$theme."/signal.css');\n
		  link.setAttribute('rel', 'stylesheet');\n
		  link.setAttribute('type', 'text/css');\n
		  var head = document.getElementsByTagName('head').item(0);\n
		  head.appendChild(link);\n
		  //-->\n
	</script>\n
	<noscript><link rel=\"stylesheet\" type=\"text/css\" href=\"".$mosConfig_live_site."/modules/mod_signallogin/".$theme."/signal.css\" /></noscript>\n";
	$sl_embedded = true;
}

if ( $my->id ) {
	//User already logged in
	if($horizontal != "0"){
		//horizontal form
		echo "<div id=\"sl_horiz\" class=\"logout\">";
		if ( $name ) {
			$name = $my->name;
		} else {
			$name = $my->username;
		}	
		?>
		<form action="<?php echo sefRelToAbs( 'index.php?option=logout' ); ?>" method="post" name="logout">	
		<?php
		if ( $greeting ) {
	        echo "<div id=\"greeting\">";
			echo _HI;
			echo $name;
        	echo "</div>";
		}
		?>
		<div id="sl_submitbutton"><input type="submit" id="logout" name="Submit" class="button" value="<?php echo _BUTTON_LOGOUT; ?>" /></div>
	
		<input type="hidden" name="option" value="logout" />
		<input type="hidden" name="op2" value="logout" />
		<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
		<input type="hidden" name="return" value="<?php echo htmlspecialchars( sefRelToAbs( $logout ) ); ?>" />
		<input type="hidden" name="message" value="<?php echo htmlspecialchars( $message_logout ); ?>" />
		</form>
		<?php
		
		echo "</div>";
	}else{
		//vertical form
		echo "<div id=\"sl_vert\" class=\"logout\">";
		if ( $name ) {
			$name = $my->name;
		} else {
			$name = $my->username;
		}	
		?>
		<form action="<?php echo sefRelToAbs( 'index.php?option=logout' ); ?>" method="post" name="logout">	
		<?php
		if ( $greeting ) {
	        echo "<div id=\"greeting\">";
			echo _HI;
			echo $name;
        	echo "</div>";
		}
		?>

		<div id="sl_submitbutton"><input type="submit" name="Submit" class="button" value="<?php echo _BUTTON_LOGOUT; ?>" /></div>
	
		<input type="hidden" name="option" value="logout" />
		<input type="hidden" name="op2" value="logout" />
		<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
		<input type="hidden" name="return" value="<?php echo htmlspecialchars( sefRelToAbs( $logout ) ); ?>" />
		<input type="hidden" name="message" value="<?php echo htmlspecialchars( $message_logout ); ?>" />
		</form>
		<?php
		
		echo "</div>";
	}
}else{
	//Show Login-Form
	if($horizontal != "0"){
		//horizontal form
		echo "<div id=\"sl_horiz\" class=\"login\">";
			// used for spoof hardening
			$validate = josSpoofValue(1);
			?>
			<form action="<?php echo sefRelToAbs( 'index.php' ); ?>" method="post" name="login" >
			<div id="sl_username"><input name="username" id="mod_login_username" type="text" alt="username" size="10" value="<?php echo _USERNAME; ?>" onfocus="if (this.value=='<?php echo _USERNAME; ?>') this.value=''" onblur="if(this.value=='') { this.value='<?php echo _USERNAME; ?>'; return false; }" /></div>

			<div id="sl_pass"><input type="password" id="mod_login_password" name="passwd" size="10" alt="password" value="<?php echo _PASSWORD; ?>" onfocus="if (this.value=='<?php echo _PASSWORD; ?>') this.value=''" onblur="if(this.value=='') { this.value='<?php echo _PASSWORD; ?>'; return false; }" /></div>
			
            <?php
			switch($remember_enabled){
            	case 1:
                	?>            
            		<div id="sl_rememberme"><input type="checkbox" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="Remember Me" /> <?php echo _REMEMBER_ME; ?></div>
                	<?php
                    break;
            	case 2:
                	?>            
            		<input type="hidden" name="remember" value="yes" />
                	<?php
                    break;
            	case 3:
                	?>            
            		<div id="sl_rememberme"><input type="checkbox" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="Remember Me" checked="checked" /> <?php echo _REMEMBER_ME; ?></div>
                	<?php
                    break;
				default:
					break;
            }
			?>
                   
			<div id="sl_submitbutton"><input type="submit" name="Submit" class="button" value="<?php echo _BUTTON_LOGIN; ?>" /></div>

			<?php if ( $show_lostpass) { ?>
				<div id="sl_lostpass"><a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword' ); ?>"><?php echo _LOST_PASSWORD; ?></a></div>
            <?php } ?>        
			<?php if ( $registration_enabled && $show_newaccount) { ?>
					<div id="sl_register"><a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=register' ); ?>"><?php echo _CREATE_ACCOUNT; ?></a></div>
			<?php } ?>
            
			<input type="hidden" name="option" value="login" />
			<input type="hidden" name="op2" value="login" />
			<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
			<input type="hidden" name="return" value="<?php echo htmlspecialchars( sefRelToAbs( $login ) ); ?>" />
			<input type="hidden" name="message" value="<?php echo htmlspecialchars( $message_login ); ?>" />
			<input type="hidden" name="force_session" value="1" />
			<input type="hidden" name="<?php echo $validate; ?>" value="1" />
			</form>

			<?php		
		echo "</div>";
	}else{
		//vertical form
		echo "<div id=\"sl_vert\" class=\"login\">";
			// used for spoof hardening
			$validate = josSpoofValue(1);
			?>
			<form action="<?php echo sefRelToAbs( 'index.php' ); ?>" method="post" name="login" >
			<?php if($pretext) {
				echo "<div id=\"sl_pretext\">" . $pretext . "</div>";
			} ?>
			<div id="sl_username"><input name="username" id="mod_login_username" type="text" alt="username" size="10" value="<?php echo _USERNAME; ?>" onfocus="if (this.value=='<?php echo _USERNAME; ?>') this.value=''" onblur="if(this.value=='') { this.value='<?php echo _USERNAME; ?>'; return false; }" /></div>

			<div id="sl_pass"><input type="password" id="mod_login_password" name="passwd" size="10" alt="password" value="<?php echo _PASSWORD; ?>" onfocus="if (this.value=='<?php echo _PASSWORD; ?>') this.value=''" onblur="if(this.value=='') { this.value='<?php echo _PASSWORD; ?>'; return false; }" /></div>

            <?php
			switch($remember_enabled){
            	case 1:
                	?>            
            		<div id="sl_rememberme"><input type="checkbox" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="Remember Me" /> <?php echo _REMEMBER_ME; ?></div>
                	<?php
                    break;
            	case 2:
                	?>            
            		<input type="hidden" name="remember" value="yes" />
                	<?php
                    break;
            	case 3:
                	?>            
            		<div id="sl_rememberme"><input type="checkbox" name="remember" id="mod_login_remember" class="inputbox" value="yes" alt="Remember Me" checked="checked" /> <?php echo _REMEMBER_ME; ?></div>
                	<?php
                    break;
				default:
					break;
            }
			?>
                    
					<div id="sl_submitbutton"><input type="submit" name="Submit" class="button" value="<?php echo _BUTTON_LOGIN; ?>" /></div>

			<?php if ( $show_lostpass) { ?>
				<div id="sl_lostpass"><a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword' ); ?>"><?php echo _LOST_PASSWORD; ?></a></div>
            <?php } ?>        
			<?php if ( $registration_enabled && $show_newaccount) { ?>
					<div id="sl_register"><?php echo _NO_ACCOUNT; ?> <a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=register' ); ?>"><?php echo _CREATE_ACCOUNT; ?></a></div>
			<?php } ?>
            
			<?php if ($posttext){
				echo "<div id=\"sl_posttext\">" . $posttext . "</div>";
			} ?>
		
			<input type="hidden" name="option" value="login" />
			<input type="hidden" name="op2" value="login" />
			<input type="hidden" name="lang" value="<?php echo $mosConfig_lang; ?>" />
			<input type="hidden" name="return" value="<?php echo htmlspecialchars( sefRelToAbs( $login ) ); ?>" />
			<input type="hidden" name="message" value="<?php echo htmlspecialchars( $message_login ); ?>" />
			<input type="hidden" name="force_session" value="1" />
			<input type="hidden" name="<?php echo $validate; ?>" value="1" />
			</form>
			<?php
		
		echo "</div>";
	}
}
?>