<?php
/**
* @package RokContentRotator
* @copyright Copyright (C) 2008 RocketTheme LLC. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $mosConfig_offset, $mosConfig_live_site, $mainframe;

$type 		    = intval( $params->get( 'type', 1 ) );
$count 		    = intval( $params->get( 'count', 5 ) );
$catid 		    = trim( $params->get( 'catid' ) );
$secid 		    = trim( $params->get( 'secid' ) );
$show_front 	= trim( $params->get( 'show_front', 1 ) );
$click_title 	= trim( $params->get( 'click_title', 1 ) );
$length         = intval($params->get( 'preview_count', 300) );
$jslib 			= intval($params->get( 'jslib', 0 ) );
$duration       = trim( $params->get( 'duration', 600));
$readmore       = trim( $params->get( 'readmore', "Read More"));
$show_readmore  = trim($params->get( 'show_readmore', 1 ) );
$show_title  = trim($params->get( 'show_title', 1 ) );

$moduleclass_sfx= $params->get( 'moduleclass_sfx' );


$now 		= date( 'Y-m-d H:i:s', time() );
$access 	= !$mainframe->getCfg( 'shownoauth' );
$nullDate 	= $database->getNullDate();

if (!function_exists('prepareContent')) {

	function prepareContent( $text, $length=300 ) {
		// strips tags won't remove the actual jscript
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
		$text = preg_replace( '/{.+?}/', '', $text);
		// replace line breaking tags with whitespace
		//$text = preg_replace( "'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text );
		//$text = strip_tags( $text );
		if (strlen($text) > $length) $text = substr($text, 0, $length) . "...";
		return $text;
	}
}

// select between Content Items, Static Content or both
switch ( $type ) {
    case 4:
    //Frontpage Items only 
    	$query = "SELECT a.id, a.title, a.introtext"
    	. "\n FROM #__content AS a"
    	. "\n INNER JOIN #__content_frontpage AS f ON f.content_id = a.id"
    	. "\n WHERE ( a.state = 1 AND a.sectionid > 0 )"
    	. "\n AND ( a.publish_up = " . $database->Quote( $nullDate ) . " OR a.publish_up <= " . $database->Quote( $now ) . " )"
    	. "\n AND ( a.publish_down = " . $database->Quote( $nullDate ) . " OR a.publish_down >= " . $database->Quote( $now ) . " )"
    	. ( $access ? "\n AND a.access <= " . (int) $my->gid : '' )
    	. "\n ORDER BY a.created DESC"
    	;
    	$database->setQuery( $query, 0, $count );
    	$rows = $database->loadObjectList();
    	break;
	    
	case 2: 
	//Static Content only
		$query = "SELECT a.id, a.title, a.introtext"
		. "\n FROM #__content AS a"
		. "\n WHERE ( a.state = 1 AND a.sectionid = 0 )"
		. "\n AND ( a.publish_up = " . $database->Quote( $nullDate ) . " OR a.publish_up <= " . $database->Quote( $now ) . " )"
		. "\n AND ( a.publish_down = " . $database->Quote( $nullDate ) . " OR a.publish_down >= " . $database->Quote( $now ) . " )"
		. ( $access ? "\n AND a.access <= " . (int) $my->gid : '' )
		. "\n ORDER BY a.created DESC"
		;
		$database->setQuery( $query, 0, $count );
		$rows = $database->loadObjectList();
		break;

	case 3: 
	//Both
		$whereCatid = '';
		if ($catid) {
			$catids = explode( ',', $catid );
			mosArrayToInts( $catids );
			$whereCatid = "\n AND ( a.catid=" . implode( " OR a.catid=", $catids ) . " )";
		}
		$whereSecid = '';
		if ($secid) {
			$secids = explode( ',', $secid );
			mosArrayToInts( $secids );
			$whereSecid = "\n AND ( a.sectionid=" . implode( " OR a.sectionid=", $secids ) . " )";
		}
		$query = "SELECT a.id, a.introtext, a.title, a.sectionid, a.catid, cc.access AS cat_access, s.access AS sec_access, cc.published AS cat_state, s.published AS sec_state"
		. "\n FROM #__content AS a"
		. "\n LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id"
		. "\n LEFT JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n LEFT JOIN #__sections AS s ON s.id = a.sectionid"
		. "\n WHERE a.state = 1"
		. "\n AND ( a.publish_up = " . $database->Quote( $nullDate ) . " OR a.publish_up <= " . $database->Quote( $now ) . " )"
		. "\n AND ( a.publish_down = " . $database->Quote( $nullDate ) . " OR a.publish_down >= " . $database->Quote( $now ) . " )"
		. ( $access ? "\n AND a.access <= " . (int) $my->gid : '' )
		. $whereCatid
		. $whereSecid
		. ( $show_front == '0' ? "\n AND f.content_id IS NULL" : '' )
		. "\n ORDER BY a.created DESC"
		;
		$database->setQuery( $query, 0, $count );
		$temp = $database->loadObjectList();
		
		$rows = array();
		if (count($temp)) {
			foreach ($temp as $row ) {
				if (($row->cat_state == 1 || $row->cat_state == '') &&  ($row->sec_state == 1 || $row->sec_state == '') &&  ($row->cat_access <= $my->gid || $row->cat_access == '' || !$access) &&  ($row->sec_access <= $my->gid || $row->sec_access == '' || !$access)) {
					$rows[] = $row;
				}
			}
		}
		unset($temp);
		break;

	case 1:  
	default:
	//Content Items only
		$whereCatid = '';
		if ($catid) {
			$catids = explode( ',', $catid );
			mosArrayToInts( $catids );
			$whereCatid = "\n AND ( a.catid=" . implode( " OR a.catid=", $catids ) . " )";
		}
		$whereSecid = '';
		if ($secid) {
			$secids = explode( ',', $secid );
			mosArrayToInts( $secids );
			$whereSecid = "\n AND ( a.sectionid=" . implode( " OR a.sectionid=", $secids ) . " )";
		}
		$query = "SELECT a.id, a.title, a.sectionid, a.catid, a.introtext"
		. "\n FROM #__content AS a"
		. "\n LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id"
		. "\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n INNER JOIN #__sections AS s ON s.id = a.sectionid"
		. "\n WHERE ( a.state = 1 AND a.sectionid > 0 )"
		. "\n AND ( a.publish_up = " . $database->Quote( $nullDate ) . " OR a.publish_up <= " . $database->Quote( $now ) . " )"
		. "\n AND ( a.publish_down = " . $database->Quote( $nullDate ) . " OR a.publish_down >= " . $database->Quote( $now ) . " )"
		. ( $access ? "\n AND a.access <= " . (int) $my->gid . " AND cc.access <= " . (int) $my->gid . " AND s.access <= " . (int) $my->gid : '' )
		. $whereCatid
		. $whereSecid
		. ( $show_front == '0' ? "\n AND f.content_id IS NULL" : '' )
		. "\n AND s.published = 1"
		. "\n AND cc.published = 1"
		. "\n ORDER BY a.created DESC"
		;
		$database->setQuery( $query, 0, $count );
		$rows = $database->loadObjectList();
		break;
}



// needed to reduce queries used by getItemid for Content Items
if ( ( $type == 1 ) || ( $type == 3 ) ) {
	$bs 	= $mainframe->getBlogSectionCount();
	$bc 	= $mainframe->getBlogCategoryCount();
	$gbs 	= $mainframe->getGlobalBlogSectionCount();
}

// Output
if ($jslib == 1) {
	echo "<script src=\"modules/rokcontentrotator/mootools.js\" type=\"text/javascript\"></script>\n";
}
?>
<script type="text/javascript" src="modules/rokcontentrotator/rokcontentrotator-1.2-compressed.js"></script>
<script type="text/javascript">
    window.addEvent('domready', function() {
		var crotator = new RokContentRotator({hover: <?php echo $click_title==1 ? "true": "false"; ?>});
	});
</script>
<div class="rok-content-rotator<?php echo $moduleclass_sfx; ?>"><div class="rotator-2"><div class="rotator-3"><div class="rotator-4">
	<?php if ($show_title == 1) :?><div class="rotator-title"><?php echo $module->title; ?></div><?php endif; ?>
    <ul>
<?php
$counter = 0;
foreach ( $rows as $row ) {
	// get Itemid
	switch ( $type ) {
	    case 4:
		case 2:
			$query = "SELECT id"
			. "\n FROM #__menu"
			. "\n WHERE type = 'content_typed'"
			. "\n AND componentid = $row->id"
			;
			$database->setQuery( $query );
			$Itemid = $database->loadResult();
			break;

		case 3:
			if ( $row->sectionid ) {
				$Itemid = $mainframe->getItemid( $row->id, 0, 0, $bs, $bc, $gbs );
			} else {
				$query = "SELECT id"
				. "\n FROM #__menu"
				. "\n WHERE type = 'content_typed'"
				. "\n AND componentid = $row->id"
				;
				$database->setQuery( $query );
				$Itemid = $database->loadResult();
			}
			break;
		case 1:
		default:
			$Itemid = $mainframe->getItemid( $row->id, 0, 0, $bs, $bc, $gbs );
			break;
	}

	// Blank itemid checker for SEF
	if ($Itemid == NULL) {
		$Itemid = '';
	} else {
		$Itemid = '&amp;Itemid='. $Itemid;
	}
    
	$link = sefRelToAbs( 'index.php?option=com_content&amp;task=view&amp;id='. $row->id . $Itemid );
	
	?>
        <li>
            <h2><a class="rok-content-rotator-link" href="#"><?php echo $row->title; ?></a></h2>
            <div class="content">
                <?php echo prepareContent($row->introtext, $length); ?> 
                <?php if ($show_readmore == 1) :?>
                <a href="<?php echo $link; ?>" class="readon"><?php echo $readmore; ?></a> 
                <?php endif; ?>
            </div>
        </li>

	<?php
}
?>
    </ul>
</div></div></div></div>
