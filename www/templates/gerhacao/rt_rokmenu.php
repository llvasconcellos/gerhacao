<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
/* returns a properly nested UL menu */

//mosShowListMenu('mainmenu'); // currently hard-coded to mainmenu
ini_set('arg_separator.output','&amp;');

class RokMenu {
    var $_menutype;
    var $_module_type;
    var $_openall;
    var $_start;
    var $_end;
    var $_open;
    var $_class_sfx;
    var $_menu_sfx;
    var $_menu_name;
    var $_menu_name_extra;
    var $_output;
    var $_showmenuname;
    var $_indents;
    
    function __construct($menutype, $menu_sfx, $module_sfx, $module_type=-1, $showmenuname=false, $openall=false, $start=0, $end=100) {
        $this->_menutype = $menutype;
        $this->_module_type = $module_type;
        $this->_openall = $openall;
        $this->_start = $start;
        $this->_end = $end;
        $this->_open = array();
        $this->_menu_sfx = $menu_sfx;
        $this->_module_sfx = $module_sfx;
        $this->_showmenuname = $showmenuname;
        $_class_sfx = null;
        
        $this->_indents = array(array( "<ul>", "<li>" , "</li>", "</ul>" ),	);
        $this->_menu_name = '';
        $this->_menu_name_extra = " Menu";
        
        $this->__init();
    }
    
    function __init() {
     	global $database, $my, $cur_template, $Itemid;
     	global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_shownoauth;
     	
     	

     	$class_sfx = null;
     	//error_reporting(E_ALL ^ E_NOTICE);
     	$hilightid = null;
     	$startid = 0;

     	if ($mosConfig_shownoauth) {
            $database->setQuery("SELECT m.*, count(p.parent) as cnt" .
            "\nFROM #__menu AS m" .
            "\nLEFT JOIN #__menu AS p ON p.parent = m.id" .
            "\nWHERE m.menutype='$this->_menutype' AND m.published='1'" .
            "\nGROUP BY m.id ORDER BY m.parent, m.ordering ");
        } else {
            $database->setQuery("SELECT m.*, sum(case when p.published=1 then 1 else 0 end) as cnt" .
            "\nFROM #__menu AS m" .
            "\nLEFT JOIN #__menu AS p ON p.parent = m.id" .
            "\nWHERE m.menutype='$this->_menutype' AND m.published='1' AND m.access <= '$my->gid'" .
            "\nGROUP BY m.id ORDER BY m.parent, m.ordering ");
        }

     	$rows = $database->loadObjectList( 'id' );
     	echo $database->getErrorMsg();
     	
     	
        // fix weird problem with itemid undefined
     	if ($Itemid > 999999) { 
     	    $current = current($rows);
     	    $Itemid = $current->id; 
     	}
     	
     	//work out if this should be highlighted
     	$sql = "SELECT m.* FROM #__menu AS m"
     	. "\nWHERE menutype='". $this->_menutype ."' AND m.published='1'"; 
     	$database->setQuery( $sql );
     	$subrows = $database->loadObjectList( 'id' );
     	$maxrecurse = 5;
     	$parentid = $Itemid;

     	//this makes sure toplevel stays hilighted when submenu active
     	while ($maxrecurse-- > 0) {
     		$parentid = $this->getTheParentRow($subrows, $parentid);
     		if (isset($parentid) && $parentid >= 0 && $subrows[$parentid]) {
     			$hilightid = $parentid;
     		} else {
     			break;	
     		}
     	}	

         // establish the hierarchy of the menu
     	$children = array();

         // first pass - collect children
         foreach ($rows as $v ) {
     		$pt = $v->parent;
     		$list = @$children[$pt] ? $children[$pt] : array();
     		array_push( $list, $v );

             $children[$pt] = $list;

         }

         // second pass - collect 'open' menus
     	$this->_open = array( $Itemid );
     	$count = 20; // maximum levels - to prevent runaway loop
     	$id = $Itemid;
     	while (--$count) {
     		if (isset($rows[$id]) && $rows[$id]->parent > 0) {
     			$id = $rows[$id]->parent;
     			$this->_open[] = $id;
     		} else {
     			break;
     		}
     	}
     	
        //get the name of the parent node
 	    $parentopen = array_reverse($this->_open);
 	    if (array_key_exists($parentopen[0],$rows)) 
 	        $this->_menu_name = $rows[$parentopen[0]]->name;
     	
     	$class_sfx = null;

     	if ($this->_start > 1) $startid = $id;

     	$this->_output = $this->recurseListMenu( $startid, $this->_start, $children, $hilightid );

        switch ($this->_module_type) {
            case -2:
                $pre = "<div class=\"moduletable$this->_module_sfx\">\n";
                $post = "</div>\n";
                break;
            case -3:
                $pre = "<div class=\"module$this->_module_sfx\"><div><div><div>\n";
                $post = "</div></div></div></div>\n";
                break;
            default:
                $pre = "";
                $post = "";
                break;
        }
        
        if ($this->_showmenuname) {
            $pre .= "<h3>" . $this->_menu_name . $this->_menu_name_extra . "</h3>\n";
        }
             
        if (isset($this->_output)) {
             $this->_output = $pre . $this->_output . $post;
         } 
      
    }
    
    function recurseListMenu( $id, $level, &$children, $highlight ) {
    	global $Itemid;
    	global $HTTP_SERVER_VARS, $mosConfig_live_site;

    	$output = "";
    	$ulstyle = "";


    	if (@$children[$id] && $level < $this->_end) {
    		$n = min( $level, count( $this->_indents )-1 );
    		
    		
    		if($this->_start==$level) {
    		    $ulstyle .= ' class="menu' . $this->_menu_sfx . '" ';
    		} 
    		if($level==1) {
    		    $ulstyle .= ' id="horiznav"'; 
    		}
    		
    		$output .= "<ul" . $ulstyle . ">";

    		foreach ($children[$id] as $row) {


    		    switch ($row->type) {
              		case 'separator':
              		// do nothing
              		$row->link = "seperator";

              		break;

              		case 'url':
              		if ( eregi( 'index.php\?', $row->link ) ) {
            				if ( !eregi( 'Itemid=', $row->link ) ) {
            					$row->link .= '&Itemid='. $row->id;
            				}
            			}
              		break;

              		default:
              			$row->link .= "&Itemid=$row->id";

              		break;
              	}

                $class =  "";
                $current_itemid = trim( mosGetParam( $_REQUEST, 'Itemid', 0 ) );
                if ($row->link != "seperator" 
    					&& $current_itemid == $row->id 
                        || in_array($row->id,$this->_open)
    					|| $row->id == $highlight
                    	|| (sefRelToAbs( substr($_SERVER['PHP_SELF'],0,-9) . $row->link)) == $_SERVER['REQUEST_URI']
                    	|| (sefRelToAbs( substr($_SERVER['PHP_SELF'],0,-9) . $row->link)) == $HTTP_SERVER_VARS['REQUEST_URI']) {
    							$class .= "active ";
    			}
    			if ($row->cnt > 0) {
    				$class .= "parent ";
    			}

    	        $output .= '<li class="' . trim($class) . '">' . "\n";

                $output .= $this->getLink( $row, $level, $this->_class_sfx );
                if ( $this->_openall || in_array( $row->id, $this->_open )) {
    			    $output .= $this->recurseListMenu( $row->id, $level+1, $children, "");
    		    }
                $output .= $this->_indents[$n][2];

            }
    		$output .= "\n".$this->_indents[$n][3];

    		return $output;

    	}
    }

    function getTheParentRow($rows, $id) {
    		if (isset($rows[$id]) && $rows[$id]) {
    			if($rows[$id]->parent > 0) {
    				return $rows[$id]->parent;
    			}	
    		}
    		return -1;
    	}

    /**
    * Utility function for writing a menu link
    */
    function getLink( $mitem, $level, $class_sfx='' ) {
    	global $Itemid, $mosConfig_live_site;
    	$txt = '';
    	$topdaddy = 'top';

    	$menuclass = '';

    	$mitem->link = str_replace( '&', '&amp;', $mitem->link );
    	$mitem->name = stripslashes( ampReplace($mitem->name) );

    	if (strcasecmp(substr($mitem->link,0,4),"http")) {
    		$mitem->link = sefRelToAbs($mitem->link);
    	}

        switch ($mitem->browserNav) {
    		// cases are slightly different
    		case 1:
    		// open in a new window
        if ($mitem->cnt > 0) {
    		   if ($level == 0) {
                    $txt = "<a class=\"topdaddy\" target=\"_window\"  href=\"$mitem->link\">$mitem->name</a>";
    								$topdaddy = "topdaddy";
    		   } else {
                    $txt = "<a class=\"daddy\" target=\"_window\"  href=\"$mitem->link\">$mitem->name</a>";
    		   }
    		} else {
    		   	$txt = "<a href=\"$mitem->link\" target=\"_window\" >$mitem->name</a>\n";
    		}
    		break;

    		case 2:
    		// open in a popup window
        if ($mitem->cnt > 0) {
    				if ($level == 0) {
                    $txt = "<a href=\"#\" class=\"topdaddy\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
    		   					$topdaddy = "topdaddy";
    		} else {
                    $txt = "<a href=\"#\" class=\"daddy\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
    		   }
    		} else {
    		    $txt = "<a href=\"#\" onClick=\"javascript: window.open('$mitem->link', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');\" class=\"$menuclass\">$mitem->name</a>\n";
    		}

    		break;

    		case 3:
    		// don't link it
        if ($mitem->cnt > 0) {
    		   if ($level == 0) {
                    $txt = "<a class=\"topdaddy\">$mitem->name</a>";
    								$topdaddy = "topdaddy";
    		   } else {
                    $txt = "<a class=\"daddy\">$mitem->name</a>";
    		   }
    		} else {
    		   	$txt = "<a>$mitem->name</a>\n";
    		}

    		break;

    		default:	// formerly case 2
    		// open in parent window
    		if (isset($mitem->cnt) && $mitem->cnt > 0) {
    		    if ($level == 0) {
                    $txt = "<a class=\"topdaddy\" href=\"$mitem->link\">$mitem->name</a>";
    								$topdaddy = "topdaddy";
    		   } else {
                    $txt = "<a class=\"daddy\" href=\"$mitem->link\"> $mitem->name</a>";
    		   }
    		} else {
    		   $txt = "<a href=\"$mitem->link\">$mitem->name</a>";
    		}
            break;
    	}
        return "<span class=\"" . $topdaddy . "\">" . $txt . "</span>";
    }
    
    function display() {
        return $this->_output;
    }
    
    function ismenu() {
        if (strlen($this->_output) > 5) return true;
        else return false;
    }
    
    /* for php4 */
    function RokMenu($menutype, $menusfx, $module_sfx, $module_type=-1, $showmenuname=false, $openall=false, $start=0, $end=100) {
        $this->__construct($menutype, $menusfx, $module_sfx, $module_type, $showmenuname, $openall, $start, $end);
    }
     
}

?>