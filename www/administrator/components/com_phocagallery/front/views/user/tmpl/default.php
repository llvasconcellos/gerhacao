<?php
defined('_JEXEC') or die('Restricted access'); 

$heading = '';
if ($this->params->get( 'page_title' ) != '') {
	$heading .= $this->params->get( 'page_title' );
}

if ($this->tmpl['showpagetitle'] != 0) {
	if ( $heading != '') {
	    echo '<div class="componentheading'.$this->params->get( 'pageclass_sfx' ).'">'
	        .$heading
			.'</div>';
	} 
}
$tab = 0;
switch ($this->tmpl['tab']) {
	case 'up':
		$tab = 1;
	break;
	
	case 'cc':
	default:
		$tab = 0;
	break;
}

echo '<div>&nbsp;</div>';

if ($this->tmpl['displaytabs'] > 0) {
	echo '<div id="phocagallery-pane">';
	$pane =& JPane::getInstance('Tabs', array('startOffset'=> $this->tmpl['tab']));
	echo $pane->startPane( 'pane' );


	echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-folder-small.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.$this->tmpl['createoredithead'], 'votes' );
	echo $this->loadTemplate('category');
	echo $pane->endPanel();



	echo $pane->startPanel( JHTML::_( 'image.site', 'components/com_phocagallery/assets/images/icon-upload.'.$this->tmpl['formaticon'],'', '', '', '', '') . '&nbsp;'.JText::_('Upload'), 'upload' );
	echo $this->loadTemplate('upload');
	echo $pane->endPanel();


	echo $pane->endPane();
	echo '</div>';
}

echo $this->tmpl['phocagalleryic'];

?>
