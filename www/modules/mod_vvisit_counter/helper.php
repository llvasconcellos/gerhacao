<?php
/**
* @version		$Id: helper.php 2009-04-17 vinaora $
* @package		VINAORA VISITORS COUNTER
* @copyright	Copyright (C) 2007 - 2009 VINAORA.COM. All rights reserved.
* @license		GNU/GPL
* @website		http://vinaora.com
* @email		admin@vinaora.com
* 
* @warning		DON'T EDIT OR DELETE LINK HTTP://VINAORA.COM ON THE FOOTER OF MODULE. CONTACT ME IF YOU WANT.
*
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<?php
require_once( dirname(__FILE__) . DS . "browsers.php" );

// Birth day of Joomla: 15 Sept 2005
define( 'BIRTH_DAY_JOOMLA' , 1126713600 );

class modVisitCounterHelper
{	
	function render(&$params)
	{
		global $mainframe;

		/* ------------------------------------------------------------------------------------------------ */
		// Read our Parameters
		$today			=	@$params->get( 'today', 'Today' );
		$yesterday		=	@$params->get( 'yesterday', 'Yesterday' );
		$x_week			=	@$params->get( 'week', 'This week' );
		$l_week			=	@$params->get( 'lweek', 'Last week' );
		$x_month		=	@$params->get( 'month', 'This month' );
		$l_month		=	@$params->get( 'lmonth', 'Last month' );
		$all			=	@$params->get( 'all', 'All days' );
		
		$beginday		=	@$params->get( 'beginday', '' );
		$online			=	@$params->get( 'online', 'Online Now: ' );
		$guestip		=	@$params->get( 'guestip', 'Your IP: ' );
		$guestinfo		=	@$params->get( 'guestinfo', 'Your: ' );
		$formattime		=	@$params->get( 'formattime', 'Now: %Y-%m-%d %H:%M' );
		
		$digit_type 	= 	@$params->get( 'digit_type', 'mechanical' );
		$widthtable		=	@$params->get( 'widthtable', 90 );
		
		$sessiontime	=	@$params->get( 'sessiontime', 10 );
		$initialvalue	=	@$params->get( 'initialvalue', 0 );
		$issunday		=	@$params->get( 'issunday', 1 );
		
		$deldays		=	@$params->get( 'deldays', 0 );
		$delday			=	@$params->get( 'delday', 15 );
		
		$pretext  		= 	@$params->get( 'pretext', "" );
		$posttext  		= 	@$params->get( 'posttext', "" );
		
		$iscache		=	@$params->get( 'iscache', 0 );
		/* ------------------------------------------------------------------------------------------------ */
		
		
		
		/* ------------------------------------------------------------------------------------------------ */
		$s_today		=	modVisitCounterHelper::showParam( $today );
		$s_yesterday	=	modVisitCounterHelper::showParam( $yesterday );
		$s_week			=	modVisitCounterHelper::showParam( $x_week );
		$s_lweek		=	modVisitCounterHelper::showParam( $l_week );
		$s_month		=	modVisitCounterHelper::showParam( $x_month );
		$s_lmonth		=	modVisitCounterHelper::showParam( $l_month );
		$s_all			=	modVisitCounterHelper::showParam( $all );
		
		$s_online		=	modVisitCounterHelper::showParam( $online );
		$s_ip			=	modVisitCounterHelper::showParam( $guestip );
		$s_guestinfo	=	modVisitCounterHelper::showParam( $guestinfo );
		$s_timenow		=	modVisitCounterHelper::showParam( $formattime );
		
		$s_digit		=	modVisitCounterHelper::showParam( $digit_type );
		$s_delrecords	=	modVisitCounterHelper::showParam( $deldays );
		/* ------------------------------------------------------------------------------------------------ */
			

		// From minutes to seconds
		$sessiontime	=	$sessiontime * 60;
		
		// Get Time Offset from Global Configuration of Joomla!
		$offset			=	$mainframe->getCfg( 'offset' );
	
		// Get a reference to the global cache object.
		$cache = & JFactory::getCache();
		
		// Check if jos_vvisitcounter table exists. When not, create it
		if ( $iscache ){
			$cache->call( array( 'modVisitCounterHelper', 'createTable' ) );
		}
		else{
			modVisitCounterHelper::createTable();
		}
		
		
		/* ------------------------------------------------------------------------------------------------ */
		// Detect Guest's IP Address and Insert new records
		$ip = "0.0.0.0";
		if(!empty($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
		
		$now			=	& JFactory::getDate(  );

		// Now we are checking if the ip was logged in the database. 
		// Depending of the value in minutes in the sessiontime variable.
		// Check session time, insert new record if timeout
		modVisitCounterHelper::insertVisitor( $sessiontime, $ip, $now->toUnix() );
		
		// Update $now
		$now			=	& JFactory::getDate(  );
		
		/* ------------------------------------------------------------------------------------------------ */
	

		/* ------------------------------------------------------------------------------------------------ */
		// Determine TIME	
		// Determine this hour, this day, this month, this year
		$minute			=	$now->toFormat( "%W" );
		$hour			=	$now->toFormat( "%H" );
		$day			=	$now->toFormat( "%d" );
		$month			=	$now->toFormat( "%m" );
		$year			=	$now->toFormat( "%Y" );
		
		// Determine Starting Time of Today
		$daystart		=	mktime( 0,0,0,$month,$day,$year );
		$local_daystart	=	modVisitCounterHelper::localTimeStart( $daystart, $offset, "day");
		
		// Starting Time of Yesterday
		$yesterdaystart			=	strtotime( "-1 day", $daystart ) ;
		$local_yesterdaystart	=	strtotime( "-1 day", $local_daystart ) ;
		
		// Starting Time of This Week
		// If Sunday is starting day of week then Sunday = 0 ... Saturday = 6
		// If Monday is starting day of week then Monday = 0 ... Sunday = 6
		$weekday			=	$now->toFormat( "%w" );
		if ( !$issunday )	$weekday--;
		$weekstart			=	strtotime( "-$weekday days", $daystart );
		$local_weekstart	=	modVisitCounterHelper::localTimeStart( $weekstart, $offset, "week");
		
		// Starting Time of Last Week
		$lweekstart			=	strtotime( "-1 week", $weekstart ) ;
		$local_lweekstart	=	strtotime( "-1 week", $local_weekstart ) ;
		
		// Determine Starting Time of This Month
		$monthstart			=	mktime( 0,0,0,$month,1,$year );
		$local_monthstart	=	modVisitCounterHelper::localTimeStart( $monthstart, $offset, "month");
		
		// Determine Starting Time of Last Month
		$lmonthstart		=	strtotime( "-1 month", $monthstart );
		$local_lmonthstart	=	strtotime( "-1 month", $local_monthstart );

		/* ------------------------------------------------------------------------------------------------ */		
		
		
		/* ------------------------------------------------------------------------------------------------ */
		// BEGIN: Caculate number visitors of Today, Yesterday, This Week, Last Week, This Month, Last Month		// Count All Visitors
		$all_visitors	=	modVisitCounterHelper::getAllVisitors();
		$all_visitors	+=	$initialvalue;
		
		// Count Today's Visitors
		$today_visitors		= modVisitCounterHelper::getVisitors( $local_daystart );
		
		// Count Yesterday's Visitors
		if( $s_yesterday ){
			if ( $iscache ){
				$yesterday_visitors = $cache->call( array( 'modVisitCounterHelper', 'getVisitors' ), $local_yesterdaystart, $local_daystart );
			}
			else {
				$yesterday_visitors	= modVisitCounterHelper::getVisitors( $local_yesterdaystart, $local_daystart );
			}
		}
		
		// Count This Week's Visitors
		if( $s_week ){
			if ( $iscache ){
				$week_visitors	= $cache->call( array( 'modVisitCounterHelper', 'getVisitors' ), $local_weekstart, $local_daystart );
				$week_visitors	+=	$today_visitors;
			}
			else{
				$week_visitors	= modVisitCounterHelper::getVisitors( $local_weekstart, $local_daystart );
				$week_visitors	+=	$today_visitors;
			}
		}
		
		// Count Last Week's Visitors
		if( $s_lweek ){
			if ( $iscache ){
				$lweek_visitors	= $cache->call( array( 'modVisitCounterHelper', 'getVisitors' ), $local_lweekstart, $local_weekstart );
			}
			else{
				$lweek_visitors	= modVisitCounterHelper::getVisitors( $local_lweekstart, $local_weekstart );
			}
		}

		// Count This Month's Visitors
		if( $s_month ){
			if ( $iscache ){
				$month_visitors		= $cache->call( array( 'modVisitCounterHelper', 'getVisitors' ), $local_monthstart, $local_daystart );
				$month_visitors		+=  $today_visitors;
			}
			else{
				$month_visitors		= modVisitCounterHelper::getVisitors( $local_monthstart, $local_daystart );
				$month_visitors		+=  $today_visitors;
			}
		}

		// Count Last Month's Visitors
		if( $s_lmonth ){
			if ( $iscache ){
				$lmonth_visitors = $cache->call( array( 'modVisitCounterHelper', 'getVisitors' ), $local_lmonthstart, $local_monthstart );
			}
			else{
				$lmonth_visitors = modVisitCounterHelper::getVisitors( $local_lmonthstart, $local_monthstart );
			}
		}
		
		// Count Online in 20 minutes
		if( $s_online ){
			$online_visitors	= modVisitCounterHelper::getVisitors( $now->toUnix() - 60*60 );
		}
		
		// END: CACULATE VISITORS
		/* ------------------------------------------------------------------------------------------------ */
		
		
		
		/* ------------------------------------------------------------------------------------------------ */
		// BEGIN: SHOW DIGIT COUNTER
		$content = '<div>';
		if ($pretext != "") $content .= '<div>'.$pretext.'</div>';
		
		// Show digit counter
		if($s_digit){
		
			$content .= '<div style="text-align: center;">';
		
			$n				= 	$all_visitors;
			$div = 100000;
			while ($n > $div) {
				$div *= 10;
			}
			
			while ($div >= 1) {
				$digit = $n / $div % 10;
				$content .= '<img src="'.JURI::base().'modules/mod_vvisit_counter/images/'.$digit_type.'/'.$digit.'.gif"';
				$content .= ' style="margin:0; border:0; "';
				$content .= ' alt="mod_vvisit_counter"';
				//$content .= ' title="Vinaora Visitors Counter"';
				$content .= ' />';
				$n -= $digit * $div;
				$div /= 10;
			}
			$content .= '</div>';
		}
		// END: SHOW DIGIT COUNTER
		/* ------------------------------------------------------------------------------------------------ */
		
		
		
		/* ------------------------------------------------------------------------------------------------ */
		// BEGIN: TABLE STATISTICS
		if ( $s_today || $s_yesterday || $s_week || $s_month || $s_all ){
		
			$content		.=	'<div><table cellpadding="0" cellspacing="0" ';
			$content		.=	'style="margin: 3px; text-align: center; ';
			$content		.=	'width: '.$widthtable.'%;" class="vinaora_counter">';
			$content		.=	'<tbody align="center">';
			
			// Show today, yestoday, this week, this month, and all visitors
			if($s_today){
				$timeline	=	modVisitCounterHelper::showTimeLine( $local_daystart );
				$content	.=	modVisitCounterHelper::spaceer("vtoday.png", $timeline, $today, $today_visitors);
			}
			if($s_yesterday){
				$timeline	=	modVisitCounterHelper::showTimeLine( $local_yesterdaystart );
				$content	.=	modVisitCounterHelper::spaceer("vyesterday.png", $timeline, $yesterday, $yesterday_visitors);
			}
			if($s_week){
				$timeline	=	modVisitCounterHelper::showTimeLine( $local_weekstart, $local_daystart );
				$content	.=	modVisitCounterHelper::spaceer("vweek.png", $timeline, $x_week, $week_visitors);
			}
			if($s_lweek){
				$timeline	=	modVisitCounterHelper::showTimeLine( $local_lweekstart, $local_weekstart );
				$content	.=	modVisitCounterHelper::spaceer("vweek.png", $timeline, $l_week, $lweek_visitors);
			}
			if($s_month){
				$timeline	=	modVisitCounterHelper::showTimeLine( $local_monthstart, $local_daystart );
				$content	.=	modVisitCounterHelper::spaceer("vmonth.png", $timeline, $x_month, $month_visitors);
			}
			if($s_lmonth){
				$timeline	=	modVisitCounterHelper::showTimeLine( $lmonthstart, $local_monthstart );
				$content	.=	modVisitCounterHelper::spaceer("vmonth.png", $timeline, $l_month, $lmonth_visitors);
			}
			if($s_all){
				if ( !$beginday ) $beginday = "Visitors Counter";
				$content	.=	modVisitCounterHelper::spaceer("vall.png", $beginday, $all, $all_visitors);
			}
			
			$content		.= "</tbody></table></div>";
			//$content 		.= '<hr style="width: 90%" />';
		}
		// END: TABLE STATISTICS
		/* ------------------------------------------------------------------------------------------------ */
		
		
		/* ------------------------------------------------------------------------------------------------ */
		// BEGIN: SHOW GUEST'S INFO
		// Show Guest's Info
		if ( $s_online || $s_ip || $s_guestinfo || $s_timenow ){
			
			$content		.=	'<div style="text-align: center;">';

			if($s_online){
				$content	.= $online . " " . $online_visitors . "<br />";
			}
			
			if($s_ip){
				$content	.= $guestip . " " . $ip . "<br />";
			}
			
			if($s_guestinfo){
				//$content	.=	$guestinfo;
				$browser 	= 	modVisitCounterBrowser::browser();
				if (!empty( $browser )){
					$content	.= strtoupper($browser['name']);
					$content	.= " ";
					$content	.= $browser['version'];
					$content	.= ", ";
					$content	.= strtoupper($browser['platform']) ;
					$content		.= "<br /> ";
				}
			}
			
			if ( $s_timenow ){
				$now->setOffset( $offset );
				$content		.=	$now->toFormat( $formattime );
			}
			
			$content		.=	'</div>';
			//$content		.=	'<hr>';
		}

		
		if ($posttext != ""){
			$content		.= '<div>'.$posttext.'</div>';
		}
		
		$content .= '<div style="text-align: center;"><!--<a href="http://vina';
		$content .= 'ora.com" target="_self" title="Vinaora Visit';
		$content .= 'ors Counter">Visitors Counter 1.6</a>--></div>';
		$content .= '</div>';
		// END: SHOW GUEST'S INFO
		/* ------------------------------------------------------------------------------------------------ */
		
		echo $content;
		
		/* ------------------------------------------------------------------------------------------------ */
		// BEGIN: Delete old records if today is the 1st, 10th or 20th of the month.
		if ( $s_delrecords ){
			if ( $day == 1 || $day == 10 || $day == 20 ){
				if ( $minute == 5 ){
				$temp = $daystart - $deldays*24*60*60;
				
					// Using caching to improve performance
					if ( $iscache ){
						$cache->call( array( 'modVisitCounterHelper', 'delVisitors' ), 0, $temp );
					}
					else{
						modVisitCounterHelper::delVisitors(0, $temp);
					}
				}
			}
		}
		// END: Delete old records
		/* ------------------------------------------------------------------------------------------------ */
		
	}
	
	
	/*
	** Show Statistics Table 
	*/	
	/* ------------------------------------------------------------------------------------------------ */
	function spaceer( $image, $timeline = "", $time = "", $visitors = "")
	{
		$ret	=	'<tr align="left"><td>';
		$ret	.=	'<img src="'.JURI::base().'modules/mod_vvisit_counter/images/'.$image.'"';
		$ret	.=	' alt="mod_vvisit_counter"';
		$ret	.=	' title="'.$timeline.'" /></td>';
		$ret	.=	'<td>'.$time.'</td>';
		$ret	.=	'<td align="right">'.$visitors.'</td></tr>';
		return $ret;
	}
	/* ------------------------------------------------------------------------------------------------ */
	
	
	
	/*
	** Create Table if Not Exist
	*/
	/* ------------------------------------------------------------------------------------------------ */	
	function createTable (){
		// Check if table exists. When not, create it
		$query	=	" CREATE TABLE IF NOT EXISTS #__vvisitcounter (
						id int(11) unsigned NOT NULL auto_increment, 
						tm int NOT NULL, 
						ip varchar(16) NOT NULL DEFAULT '0.0.0.0', 
						PRIMARY KEY (`id`)
					) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; ";
					
		$db	=& JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
	}
	/* ------------------------------------------------------------------------------------------------ */
	
	
	
	/*
	** Insert New Visisitor
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function insertVisitor( $sessiontime, $ip, $time ){
		// Check session time, insert new record if timeout
		$db				=	& JFactory::getDBO();
		$query			=	' SELECT COUNT(*) FROM #__vvisitcounter ';
		$query			.=	' WHERE ip=' . $db->quote( $ip );
		$query			.=	' AND (tm + ' . $db->quote( $sessiontime ) . ') > ' . $db->quote( $time );
							$db->setQuery($query);
		$items			=	$db->loadResult();
		
		if ( empty($items) )
		{
			$query = " INSERT INTO #__vvisitcounter (id, tm, ip) VALUES ('', " . $db->quote ( $time ) . ", " . $db->quote ( $ip ) . ")";
			$db->setQuery($query);
			$db->query();
			$e = $db->getErrorMsg();
		}
	}
	/* ------------------------------------------------------------------------------------------------ */
	
	
	
	/*
	** Get Total of Visisitors Until $time
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function getAllVisitors( $time=0 ){
	
		$visitors	=	0;
		$db			=	& JFactory::getDBO();
		
		// Query total visitors
		$query = ' SELECT MAX(id) FROM #__vvisitcounter ';
		if ( $time > BIRTH_DAY_JOOMLA ){
			$query	.=	' WHERE tm < ' . $db->quote( $time );
		}
		$db->setQuery($query);
		
		// Total visitors
		$visitors	=	$db->loadResult();
		return $visitors;
	}
	/* ------------------------------------------------------------------------------------------------ */
	
	
	
	/*
	** Get Number of Visisitors from $timestart to $timestop
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function getVisitors( $timestart = 0, $timestop = 0 ){
		
		$visitors	=	0;

		if ( $timestart < BIRTH_DAY_JOOMLA ) $timestart = 0;
		if ( $timestop < BIRTH_DAY_JOOMLA ) $timestop = 0;
		
		$db				=	& JFactory::getDBO();
		$query			=	' SELECT COUNT(*) FROM #__vvisitcounter ';
		
		if ( !$timestart ){
			if ( !$timestop ) return 0;
			$query		.= ' WHERE tm < ' . $db->quote( $timestop );
		}
		else{
			if ( !$timestop ){
				$query		.= ' WHERE tm >= ' . $db->quote( $timestart );
			}
			else{
			
				if ( $timestop == $timestart ){
					$query		.= ' WHERE tm = ' . $db->quote( $timestart );
				}
				
				if ( $timestop > $timestart ){
					$query		.= ' WHERE tm >= ' . $db->quote( $timestart );
					$query		.= ' AND tm < ' . $db->quote( $timestop );					
				}
				
				if ( $timestop < $timestart ){
					$query		.= ' WHERE tm >= ' . $db->quote( $timestop );
					$query		.= ' AND tm < ' . $db->quote( $timestart );					
				}
			}
		}
		
		$db->setQuery($query);
		$visitors	=	$db->loadResult();
		
		return $visitors;
	}
	/* ------------------------------------------------------------------------------------------------ */
	
	
	
	/*
	** Remove Visisitors from $timestart to $timestop
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function delVisitors( $timestart = 0, $timestop = 0 ){
		
		if ( $timestart < BIRTH_DAY_JOOMLA ) $timestart = 0;
		if ( $timestop < BIRTH_DAY_JOOMLA ) $timestop = 0;
		
		$db				=	& JFactory::getDBO();
		$query			=	' DELETE FROM #__vvisitcounter ';
		
		if ( !$timestart ){
			if ( !$timestop ) return 0;
			$query		.= ' WHERE tm < ' . $db->quote( $timestop );
		}
		else{
			if ( !$timestop ){
				$query		.= ' WHERE tm >= ' . $db->quote( $timestart );
			}
			else{
			
				if ( $timestop == $timestart ){
					$query		.= ' WHERE tm = ' . $db->quote( $timestart );
				}
				
				if ( $timestop > $timestart ){
					$query		.= ' WHERE tm >= ' . $db->quote( $timestart );
					$query		.= ' AND tm < ' . $db->quote( $timestop );					
				}
				
				if ( $timestop < $timestart ){
					$query		.= ' WHERE tm >= ' . $db->quote( $timestop );
					$query		.= ' AND tm < ' . $db->quote( $timestart );					
				}
			}
		}
		
		$db->setQuery($query);
		$db->query();
	}
	/* ------------------------------------------------------------------------------------------------ */
	
	
	
	/*
	** Check Parameter
	** Return False if Parameter equal to "0" (zero) or "o" or "O" or "none" or empty
	*/
	/* ------------------------------------------------------------------------------------------------ */	
	function showParam( $param = "" ){
		
		// $param is Undefined variable
		if ( empty( $param ) ) return false;
		
		// $param Defined variable
		$param = strtolower( trim($param) );
		if ( $param == "" ) return false;
		if ( $param == "0" ) return false;
		if ( $param == "o" ) return false;
		if ( $param == "none" ) return false;
		return true;
	}
	/* ------------------------------------------------------------------------------------------------ */
	
	
	
	/*
	** Show Timeline.
	** Format: %Y-%m-%d -> %Y-%m-%d
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function showTimeLine( $timeBegin = 0, $timeEnd = 0, $offset = "" ){
		global $mainframe;
		$str	=	"";
		if ( empty( $offset ) ) $offset	= $mainframe->getCfg( 'offset' );
		
		if ( $timeBegin ){
			$time	=	& JFactory::getDate( $timeBegin );
			$time->setOffset( $offset );
			
			$str1 	=	$time->toFormat( '%Y-%m-%d' ) ;
			$str	=	$str1;
			if ( $timeEnd ){
				$time	=	& JFactory::getDate( $timeEnd );
				$time->setOffset( $offset );
				
				$str2 	= 	$time->toFormat( '%Y-%m-%d' ) ;
				$str	.=	" -> ";
				$str	.=	$str2;
			}
		}
		return $str;
	}
	/* ------------------------------------------------------------------------------------------------ */



	/*
	** Determine Local Starting Time.
	** Return Unix Time
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function localTimeStart( $timestart, $offset=0, $type="day" ){
		$now	=	& JFactory::getDate(  );
		$type	=	strtolower( trim ($type) );
		if ( $type != "day" && $type != "week" && $type != "month" ) $type = "day";

		$nexttimestart	=	strtotime( "+1 " . $type, $timestart ) ;
		$lasttimestart	=	strtotime( "-1 " . $type, $timestart ) ;
		
		if ( $offset > 0 ) {
			if ( ( $nexttimestart - $now->toUnix() ) <= $offset*60*60 ) {
				$timestart = $nexttimestart - $offset*60*60;
			}
			else $timestart -=  $offset*60*60;
		}
		if ( $offset < 0 ) {
			if ( ( $now->toUnix() - $timestart ) < -$offset*60*60 ) $timestart = $lasttimestart + -$offset*60*60;
			else $timestart += -$offset*60*60;
		}
		return $timestart;
	}
	/* ------------------------------------------------------------------------------------------------ */
	
}