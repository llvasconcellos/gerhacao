<?php
/**
* @version 1.0.1
* @package RSform!Pro 1.0.1
* @copyright (C) 2007-2008 www.rsjoomla.com
* @license Commercial License, http://www.rsjoomla.com/license/rsformpro.html
*/
  defined( '_JEXEC' ) or die( 'Restricted access' );   
  
  class RSadapter
  {
    //Database definition
    var $tbl_rsform_config                  = null;
    var $tbl_rsform_components              = null;
    var $tbl_rsform_component_types         = null;
    var $tbl_rsform_component_type_fields   = null;
    var $tbl_rsform_forms                   = null;
    var $tbl_rsform_properties              = null;
    var $tbl_rsform_submissions             = null;
    var $tbl_rsform_submission_values       = null;
    
    //Configuration
    var $config                             = null;
    var $version							= 'j15x';
    var $xmlversion							= 'a1J15x';
      
    function RSadapter()
    {
    	
    	$JCONFIG = new JConfig();

        //define tables
        $this->tbl_rsform_config                    = "{$JCONFIG->dbprefix}RSFORM_CONFIG";
        $this->tbl_rsform_components                = "{$JCONFIG->dbprefix}RSFORM_COMPONENTS";
        $this->tbl_rsform_component_types           = "{$JCONFIG->dbprefix}RSFORM_COMPONENT_TYPES";
        $this->tbl_rsform_component_type_fields     = "{$JCONFIG->dbprefix}RSFORM_COMPONENT_TYPE_FIELDS";
        $this->tbl_rsform_forms                     = "{$JCONFIG->dbprefix}RSFORM_FORMS";
        $this->tbl_rsform_properties                = "{$JCONFIG->dbprefix}RSFORM_PROPERTIES";
        $this->tbl_rsform_submissions               = "{$JCONFIG->dbprefix}RSFORM_SUBMISSIONS";
        $this->tbl_rsform_submission_values         = "{$JCONFIG->dbprefix}RSFORM_SUBMISSION_VALUES";
        $this->tbl_users				         	= "{$JCONFIG->dbprefix}users";
        
        //load configuration
        $this->config = $this->buildConfig();
    }

    function user($id = null)
    {
    	$my = & JFactory::getUser();
    	$user = array('id'=>'','username'=>'','fullname'=>'','email'=>'');
    	
    	if(!$id)
    	{    	
    		$user['id'] = $my->id;
    		$user['username'] = $my->username;
    		$user['fullname'] = $my->name;
    		$user['email'] = $my->email;
    	}
    	else 
    	{
    		$query = mysql_query("SELECT * FROM $this->tbl_users WHERE id = '$id'");
    		$r = mysql_fetch_assoc($query);
    		
    		if(isset($r['id']))
    		{
    			$user['id'] = $r['id'];
    			$user['username'] = $r['username'];
    			$user['fullname'] = $r['name'];
    			$user['email'] = $r['email'];
    		}
    	}
    	return $user;
    }
    
    
    function getParam($array, $name, $default_value = null)
    {
        if(isset($array[$name])) $val = $array[$name];
        else $val = $default_value;
        
        return $val;
    }

    function redirect($url, $msg = null)
    {
    	global $mainframe;
    	$mainframe->redirect($url, $msg);
    }
    
    function processPath($p_path,$p_addtrailingslash = true)
    {
    	jimport('joomla.filesystem.path');
		$path = JPath::clean($p_path);
		if ($p_addtrailingslash) {
			$path = rtrim($path, DS) . DS;
		}
		return $path;
    }
    function readDirectory($path, $filter = '.', $recurse = false, $fullpath = false)
    {
    	return JFolder::files($path, $filter, $recurse, $fullpath);
    }
    
    function makePath($base, $path = '', $mode = null)
    {
    	JFolder::create($base);
    }
    
    function cleanupInstall( $userfile_name, $resultdir)
    {
    	cleanupInstall( $userfile_name, $resultdir);
    }
    
    function chmod($path)
    {
    	JPath::setPermissions($path);
    }
    
    function mail($from, $fromname, $recipient, $subject, $body, $mode = 0, $cc = null, $bcc = null, $attachment = null)
    {
    	JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment);
    }
    
    
    //PLUGINS
    function registerEvent($event, $function)
    {
    	global $mainframe;
    	$mainframe->registerEvent( $event, $function );
    }
	
	
    //INSTALLER
    
    function renameComponentXML()
    {
    	$message = '';
    	//rename
    	rename(_RSFORM_BACKEND_ABS_PATH.'/a1J15x.xml',_RSFORM_BACKEND_ABS_PATH.'/rsform.xml');
    	
    }
    
    
    function installPlugin($filename, $title)
    {
    	$message = '';
    	
    	//copy
		@copy(_RSFORM_BACKEND_ABS_PATH.'/addons/plugins/'.$filename.'/'.$filename.'.php',$this->config['absolute_path'].'/plugins/content/'.$filename.'.php') or 
			$message .= _RSFORM_BACKEND_ABS_PATH.'/addons/plugins/'.$filename.'/'.$filename.'.php';
		@copy(_RSFORM_BACKEND_ABS_PATH.'/addons/plugins/'.$filename.'/a1J15x.xml',$this->config['absolute_path'].'/plugins/content/'.$filename.'.xml') or 
			$message .= _RSFORM_BACKEND_ABS_PATH.'/addons/plugins/'.$filename.'/'.$filename.'.xml';
		
		//register
		$q = "INSERT INTO `{$this->config['dbprefix']}plugins` 
		(`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
		('', '$title', '$filename', 'content', 0, 1, 1, 0, 0, 0, '0000-00-00 00:00:00', '')";
		mysql_query($q) or $message .= $q . mysql_error() . '<br/>';
		
		if($message == '') return 'ok';
		else return $message;
    }
    
    function updatePlugin($filename)
    {
    	//copy
		copy(_RSFORM_BACKEND_ABS_PATH.'/addons/plugins/'.$filename.'/'.$filename.'.php',$this->config['absolute_path'].'/plugins/content/'.$filename.'.php');
		copy(_RSFORM_BACKEND_ABS_PATH.'/addons/plugins/'.$filename.'/a1J15x.xml',$this->config['absolute_path'].'/plugins/content/'.$filename.'.xml');
	}
    
    function installModule($filename, $title)
    {
    	$message = '';
    	
    	//mkdir first
    	@mkdir(JPATH_SITE.'/modules/'.$filename.'/');
    	
    	//copy
		@copy(_RSFORM_BACKEND_ABS_PATH.'/addons/modules/'.$filename.'/'.$filename.'.php',$this->config['absolute_path'].'/modules/'.$filename.'/'.$filename.'.php') or 
			$message .= _RSFORM_BACKEND_ABS_PATH.'/addons/modules/'.$filename.'/'.$filename.'.php';
		@copy(_RSFORM_BACKEND_ABS_PATH.'/addons/modules/'.$filename.'/a1J15x.xml',$this->config['absolute_path'].'/modules/'.$filename.'/'.$filename.'.xml') or 
			$message .= _RSFORM_BACKEND_ABS_PATH.'/addons/modules/'.$filename.'/'.$filename.'.xml';
		
		//register
		$q = "INSERT INTO `{$this->config['dbprefix']}modules` 
		(`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`, `control`) VALUES
		('', '$title', '', 1, 'left', 0, '0000-00-00 00:00:00', 0, '$filename', 0, 0, 1, '', 0, 0, '')";
		mysql_query($q) or $message .= $q . mysql_error() . '<br/>';
		
		
		if($message == '') return 'ok';
		else return $message;
    }
    
    function updateModule($filename)
    {
    	//copy
		copy(_RSFORM_BACKEND_ABS_PATH.'/addons/modules/'.$filename.'/'.$filename.'.php',$this->config['absolute_path'].'/modules/'.$filename.'/'.$filename.'.php');
		copy(_RSFORM_BACKEND_ABS_PATH.'/addons/modules/'.$filename.'/a1J15x.xml',$this->config['absolute_path'].'/modules/'.$filename.'/'.$filename.'.xml');
    }
    
    function removePlugin($filename){
    	@unlink($this->config['absolute_path'].'/plugins/content/'.$filename.'.php');
    	@unlink($this->config['absolute_path'].'/plugins/content/'.$filename.'.xml');
    	
    	//remove from db
    	$q = "DELETE FROM `{$this->config['dbprefix']}plugins` WHERE `element` = '$filename'";
    	mysql_query($q);
    }
    
    function removeModule($filename){
    	@unlink($this->config['absolute_path'].'/modules/'.$filename.'/'.$filename.'.php');
    	@unlink($this->config['absolute_path'].'/modules/'.$filename.'/'.$filename.'.xml');
    	@rmdir($this->config['absolute_path'].'/modules/'.$filename.'/');
    	
    	//remove from db
    	$q = "DELETE FROM `{$this->config['dbprefix']}modules` WHERE `module` = '$filename'";
    	mysql_query($q);
    }
    
    function getMenuParam($var,$default)
    {
    	$query = "
            SELECT params
                FROM `{$this->config['dbprefix']}menu`
                WHERE id='".$_GET['Itemid']."' AND type='component'";
    	$rez = mysql_query($query) or die($query.mysql_error());
   		if(mysql_num_rows($rez))
   		{
			$r = @mysql_fetch_assoc($rez);
			$menurow = @$r['params'];
			$registry = new JRegistry();
			$registry->loadINI( $menurow );
			$configs = $registry->toObject( );
			$return = @$configs->$var;
		}else{
			$return = $default;
		}
		return $return;
    }
    
    function getMenus()
    {
    	require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_menus'.DS.'helpers'.DS.'helper.php' );
    	$menuTypes 	= MenusHelper::getMenuTypes();
    	
    	return $menuTypes;
    }
    
    function addMenu($formId, $title, $menu)
    {
    	
    	//get RSform componentId
    	$q = "SELECT id FROM `{$this->config['dbprefix']}components` WHERE `option`='com_rsform' and parent=0";
    	$rez = mysql_query($q) or die($q.mysql_error());
    	$r = mysql_fetch_assoc($rez);
    	
    	$q = "INSERT INTO `{$this->config['dbprefix']}menu` 
    	(`id`, `menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) VALUES
    	('', '$menu', '$title', 'index.php?option=com_rsform', 'component', 1, 0, '{$r['id']}', 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'formId=$formId')";
    	mysql_query($q) or die($q.mysql_query());
    }
    
    //Menu
    function menuStartTable()
    {
    	
    }
    
    function menuSpacer()
    {
    	JToolBarHelper::spacer();
    }
        
    function menuCustom($task,$icon,$iconOver,$alt,$listSelect=true, $onclick='')
    {
    	if($onclick=='')JToolBarHelper::custom($task,$icon,$iconOver,$alt,$listSelect);
    	else JToolBarHelper::custom($task."');".$onclick.";void('",$icon,$iconOver,$alt,$listSelect);
    }
    
    function menuCancel($task = 'cancel', $alt = 'Cancel')
    {
    	JToolBarHelper::cancel($task, $alt);
    }
    
    function menuBack($alt = 'Back', $href = '')
    {
    	JToolBarHelper::back($alt, $href);
    }
    
    function menuAddNewX($task = 'new', $alt = 'New')
    {
    	JToolBarHelper::addNewX($task, $alt);
    }
    
    function menuDeleteList($msg = '',$task = 'remove',$alt = 'Delete')
    {
    	JToolBarHelper::deleteList($msg, $task, $alt);
    }
    
    function menuSave($task='save',$alt='Save')
    {
    	JToolBarHelper::save($task, $alt);
    }
    
    function menuApply($task='apply',$alt='Apply')
    {
    	JToolBarHelper::apply($task, $alt);
    }
    
    function menuPublishList($task='publish',$alt='Publish')
    {
    	JToolBarHelper::publishList($task, $alt);
    }
    
    function menuUnpublishList($task='unpublish',$alt='Unpublish')
    {
    	JToolBarHelper::unpublishList($task, $alt);
    }
    
    function menuEndTable(){
    	
    }
    
    
//////////////////////////////////////// HTML METHODS ////////////////////////////////////////   
    
    function addHeadTag($str, $type, $destination = 'head')
    {
    	$document =& JFactory::getDocument();
    	switch($type)
    	{
    		case 'css':
    			$document->addStyleSheet($str);	
    		break;
    		case 'js':
    			$document->addScript($str);
    		break;
    	}
    }
    
    function initTabs($number)
    {
    	jimport('joomla.html.pane');
    	$this->tabs = & JPane::getInstance('Tabs',array(),true);
    }
    function startPane($id)
    {
    	echo $this->tabs->startPane($id);
    }
    function startTab($title, $id)
    {
    	echo $this->tabs->startPanel($title, $id);
    }
    function endTab()
    {
    	echo $this->tabs->endPanel();
    }
    function endPane()
    {
    	echo $this->tabs->endPane();
    }
    
    function WYSIWYG($name, $content, $hiddenField, $width, $height, $col, $row)
    {
    	$editor =& JFactory::getEditor();
    	echo $editor->display( $name,  $content , $width, $height, $col, $row ) ;
    }
    
    function YesNoRadio($tag_name, $tag_attribs, $selected, $yes = _CMN_YES, $no = _CMN_NO)
    {
    	return JHTML::_('select.booleanlist', $tag_name, $tag_attribs, $selected,$yes,$no );
    }
    
    
    /**
    * @desc Builds RSform_config
    */
    function buildConfig()
    {
        $RSform_config = array(); 
        $query = @mysql_query("SELECT SettingName, SettingValue FROM `{$this->tbl_rsform_config}`");
        if($query)
        {
        	while ($RSform_config_row = mysql_fetch_array($query))
        	{
	            $RSform_config[$RSform_config_row['SettingName']] = $RSform_config_row['SettingValue'];
    	    }    
        }
         
        $JCONFIG = new JConfig();
        
        //add Joomla Configuration
        $RSform_config['list_limit'] 	= $JCONFIG->list_limit;
        $RSform_config['absolute_path'] = JPATH_SITE;
        $RSform_config['live_site'] 	= JURI :: root();
        $RSform_config['mail_from'] 	= $JCONFIG->mailfrom;
        $RSform_config['sitename'] 		= $JCONFIG->sitename;
        $RSform_config['dbprefix'] 		= $JCONFIG->dbprefix;
        $RSform_config['db'] 			= $JCONFIG->db;
           
        if(isset($RSform_config['global.debug.mode']))
        {
        	if($RSform_config['global.debug.mode'] == 1)
			{
				error_reporting(E_ALL);
				ini_set('display_errors',1);
			}
        }
        
        return $RSform_config;
    }
    
  }
  
	  
	function botMosRSform(&$row, &$params, $page=0)
	{	
		$RSadapter = $GLOBALS['RSadapter'];
		if(isset($row->text)){
			// simple performance check to determine whether bot should process further
			if ( strpos( $row->text, 'rsform' ) === false ) {
				return true;
			}
		 	// expression to search for
		 	$regex = '/{rsform\s*.*?}/i';
	
	
			// find all instances of mambot and put in $matches
			preg_match_all( $regex, $row->text, $matches );
	
			// Number of mambots
		 	$count = count( $matches[0] );
	
		 	// mambot only processes if there are any instances of the mambot in the text
		 	if ( $count ) {
		 		processRSform( $row, $matches, $count, $regex );
		 	}
			$row->text = preg_replace( $regex, '', $row->text );
		}
	}

  
  
  
  //global product constants                        
  define('_RSFORM_BACKEND_ABS_PATH',JPATH_SITE.'/administrator/components/com_rsform');
  define('_RSFORM_BACKEND_REL_PATH',JURI :: root().'administrator/components/com_rsform');
  
  define('_RSFORM_FRONTEND_ABS_PATH',JPATH_SITE.'/components/com_rsform');
  define('_RSFORM_FRONTEND_REL_PATH',JURI :: root().'components/com_rsform');
  
  
  //global script constants
  define('_RSFORM_BACKEND_SCRIPT_PATH',JURI :: root().'administrator/index.php');
  define('_RSFORM_FRONTEND_SCRIPT_PATH',JURI :: root().'index.php');
  
  //Other paths
  define('_RSFORM_JOOMLA_XML_PATH', JPATH_SITE . '/libraries/domit/xml_domit_lite_include.php');
  
    //backend active language
    $backend_language = 'default';
    //if(file_exists(_RSFORM_FRONTEND_ABS_PATH.'/languages/'.$mosConfig_lang.'.php')) $backend_language = $mosConfig_lang;
    
    //get frontend language
    $frontend_language = 'default';
    //if(file_exists(_RSFORM_FRONTEND_ABS_PATH.'/languages/'.$mosConfig_lang.'.php')) $frontend_language = $mosConfig_lang;
    //check jfish
    if(isset($_COOKIE['mbfcookie']['lang'])) 
    {
    	if(file_exists(_RSFORM_FRONTEND_ABS_PATH.'/languages/'.$_COOKIE['mbfcookie']['lang'].'.php')) $frontend_language = $_COOKIE['mbfcookie']['lang'];
    }
    if(isset($_COOKIE['jfcookie']['lang'])) 
    {
    	if(file_exists(_RSFORM_FRONTEND_ABS_PATH.'/languages/'.$_COOKIE['jfcookie']['lang'].'.php')) $frontend_language = $_COOKIE['jfcookie']['lang'];
    }
    if(isset($_REQUEST['lang'])){
    	$lang = RSadapter::getParam($_REQUEST,'lang');
    	if(file_exists(_RSFORM_FRONTEND_ABS_PATH.'/languages/'.$lang.'.php')) $frontend_language = $lang;
    }
    
    DEFINE('_RSFORM_BACKEND_LANGUAGE',$backend_language);                                                                                                                    
    DEFINE('_RSFORM_FRONTEND_LANGUAGE',$frontend_language);                                                                                                                    


        
    //load db  
    $JCONFIG = new JConfig();      
	mysql_connect($JCONFIG->host,$JCONFIG->user,$JCONFIG->password) or die(mysql_error());
	mysql_selectdb($JCONFIG->db) or die(mysql_error());    
	//mysql_query("SET NAMES 'utf8'");
    
	
	
	
	
?>
