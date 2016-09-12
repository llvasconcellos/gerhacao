<?php
/**
* @package RokSlide
* @copyright Copyright (C) 2007 RocketWerx. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'front_html', 'com_content') );

if (!defined( '_ROKSLIDE_MODULE' )) {
	/** ensure that functions are declared only once */
	define( '_ROKSLIDE_MODULE', 1 );

	function output_rokslide( &$row, &$params, &$access, $counter ) {
		global $mainframe, $database;

		$query = "SELECT id"
			. "\n FROM #__menu"
			. "\n WHERE type = 'content_typed'"
			. "\n AND componentid = $row->id"
			;

		$database->setQuery( $query );
		$Itemid = $database->loadResult();
		if ($Itemid == NULL) {
			$Itemid = '';
		} else {
			$Itemid = '&amp;Itemid='. $Itemid;
		}

		$row->text 		= $row->introtext;
		$row->groups 	= '';
		$row->readmore 	= sefRelToAbs( 'index.php?option=com_content&amp;task=view&amp;id='. $row->id . $Itemid );
		$row->metadesc 	= '';
		$row->metakey 	= '';
		$row->access 	= '';
		$row->created 	= '';
		$row->modified 	= '';

		echo "<div class=\"tab-pane\" id=\"tab-$counter-pane\">\n";
		echo "<h1 class=\"tab-title\">$row->title</h1>\n";
		showContent( $row, $params, $access, 0 );
		echo "</div>\n";
	}
}

global $my, $mosConfig_shownoauth, $mosConfig_offset, $mosConfig_link_titles, $acl;

// Disable edit ability icon
$access = new stdClass();
$access->canEdit 	= 0;
$access->canEditOwn = 0;
$access->canPublish = 0;

$now 				= _CURRENT_SERVER_TIME;
$noauth 			= !$mainframe->getCfg( 'shownoauth' );
$nullDate 			= $database->getNullDate();
$jslib 				= $params->get( 'jslib', 0);
$jssource			= $params->get( 'jssource', 0);
$catid 				= intval( $params->get( 'catid' ) );
$items 				= $params->get( 'items' );
$moduleclass_sfx    = $params->get( 'moduleclass_sfx' );

$width				= trim($params->get( 'width', 722 ));
$height				= trim($params->get( 'height', 150 ));

$params->set( 'intro_only', 		1 );
$params->set( 'hide_author', 		1 );
$params->set( 'hide_createdate', 	0 );
$params->set( 'hide_modifydate', 	1 );

// query to determine article count
$query = "SELECT a.id, a.introtext, a.fulltext , a.images, a.title, a.state"
."\n FROM #__content AS a"
."\n INNER JOIN #__categories AS cc ON cc.id = a.catid"
."\n INNER JOIN #__sections AS s ON s.id = a.sectionid"
."\n WHERE a.state = 1"
. ( $noauth ? "\n AND a.access <= " . (int) $my->gid . " AND cc.access <= " . (int) $my->gid . " AND s.access <= " . (int) $my->gid : '' )
."\n AND (a.publish_up = " . $database->Quote( $nullDate ) . " OR a.publish_up <= " . $database->Quote( $now ) . " ) "
."\n AND (a.publish_down = " . $database->Quote( $nullDate ) . " OR a.publish_down >= " . $database->Quote( $now ) . " )"
."\n AND a.catid = " . (int) $catid
."\n AND cc.published = 1"
."\n AND s.published = 1"
."\n ORDER BY a.ordering"
;

$database->setQuery( $query, 0, intval($items) );
$rows = $database->loadObjectList();
$numrows = count( $rows );
$counter = 0;

// check if any results returned
if ( $numrows ) {
		if ($jslib == 1) {
			echo "<script type=\"text/javascript\" src=\"modules/rokslide/mootools.r597.js\"></script>\n";
		}
		if ($jssource == 1) {
			echo "<script type=\"text/javascript\" src=\"modules/rokslide/rokslidestrip.js\"></script>\n";
		}
		echo "<script type=\"text/javascript\">
					window.addEvent('domready', function() {
						var myFilm = new RokSlide($('rokslide'), {
							fx: {
								wait: true,
								duration: 1000
							},
							scrollFX: {
								transition: Fx.Transitions.Cubic.easeIn
							},
							dimensions: {
								width: $width,
								height: $height
							}
						});
					});
					</script>\n";
		echo '<div id="rokslide" class="' . $moduleclass_sfx .'">';
		foreach ($rows as $row) {
			output_rokslide( $row, $params, $access, $counter++ );
		}
		echo '</div>';
}

	function showContent( &$row, &$params, &$access, $page=0 ) {
		global $mainframe, $hide_js;
		global $mosConfig_live_site;
		global $_MAMBOTS;

		// calculate Itemid
		HTML_content::_Itemid( $row );
		// determines the link and `link text` of the readmore button & linked title
		HTML_content::_linkInfo( $row, $params );

		// process the new bots
		$_MAMBOTS->loadBotGroup( 'content' );
		$results = $_MAMBOTS->trigger( 'onPrepareContent', array( &$row, &$params, $page ), true );

		if ( !$params->get( 'intro_only' ) ) {
			$results = $_MAMBOTS->trigger( 'onAfterDisplayTitle', array( &$row, &$params, $page ) );
			echo trim( implode( "\n", $results ) );
		}

		$results = $_MAMBOTS->trigger( 'onBeforeDisplayContent', array( &$row, &$params, $page ) );
		echo trim( implode( "\n", $results ) );

		// displays Item Text
		echo ampReplace( $row->text );

		// displays Readmore button
		readMore( $row, $params );

		$results = $_MAMBOTS->trigger( 'onAfterDisplayContent', array( &$row, &$params, $page ) );
		echo trim( implode( "\n", $results ) );
	}

		function readMore ( &$row, &$params ) {
		if ( $params->get( 'readmore' ) ) {
			if ( $params->get( 'intro_only' ) && $row->link_text ) {
				?>
				<a href="<?php echo $row->link_on;?>" class="readon<?php echo $params->get( 'pageclass_sfx' ); ?>">
							<?php echo $row->link_text;?></a>
				<?php
			}
		}
	}

?>