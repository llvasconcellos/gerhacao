<?php
defined('_JEXEC') or die('Restricted access'); 

if(!empty($this->categories)) {
	$columns 			= (int)$this->tmpl['categoriescolumnscv'];
	$countCategories 	= count($this->categories);
	$begin				= array();
	$end				= array();
	$begin[0]			= 0;// first
	$begin[1]			= ceil ($countCategories / $columns);
	$end[0]				= $begin[1] -1;

	for ( $j = 2; $j < $columns; $j++ ) {
		$begin[$j]	= ceil(($countCategories / $columns) * $j);
		$end[$j-1]	= $begin[$j] - 1;
	}
	$end[$j-1]		= $countCategories - 1;// last
	$endFloat		= $countCategories - 1;
} else {
	$countCategories 	= 0;
}
// -------------------
// TABLE LAYOUT
// -------------------
if ($this->tmpl['displayimagecategoriescv'] == 1) {	
	for ($i = 0; $i < $countCategories; $i++) {
		
		// Change the thumbnail for Category View
		// We are in Category View but need Categories View Settings
	
		switch((int)$this->tmpl['imagetypecv']) {
			case 0:
			case 2:
			case 4:
			case 6:
				$imageThumbnail = str_replace('medium', 'small-main', $this->categories[$i]->linkthumbnailpath);
				$imageThumbnail = str_replace('phoca_thumb_m_', 'phoca_thumb_s_', $imageThumbnail);
			break;
			default:
				$imageThumbnail = str_replace('small-main', 'medium', $this->categories[$i]->linkthumbnailpath);
				$imageThumbnail = str_replace('phoca_thumb_s_', 'phoca_thumb_m_', $imageThumbnail);
			break;
		 } 
		// - - - - - - - - - - - - - - -
	
		if ( $columns == 1 ) {
			echo '<table>';
		} else {
			$float = 0;
			foreach ($begin as $k => $v)
			{
				if ($i == $v) {
					$float = 1;
				}
			}
			if ($float == 1) {		
				echo '<div style="position:relative;float:left;margin:10px;"><table>';
			}
		}

		echo '<tr>';		
		echo '<td align="center" valign="middle" style="'.$this->tmpl['imagebgcv'].';text-align:center;"><a href="'.$this->categories[$i]->link.'">'.JHTML::_( 'image.site',$imageThumbnail, '', '', '', $this->categories[$i]->title, 'style="border:0"' ).'</a></td>';
		echo '<td><a href="'.$this->categories[$i]->link.'" class="category'.$this->params->get( 'pageclass_sfx' ).'">'.$this->categories[$i]->title.'</a>&nbsp;';
		
		if ($this->categories[$i]->numlinks > 0) {echo '<span class="small">('.$this->categories[$i]->numlinks.')</span>';}
		
		echo '</td>';
		echo '</tr>';
		
		if ( $columns == 1 ) {
			echo '</table>';
		} else {
			if ($i == $endFloat) {
				echo '</table></div><div style="clear:both"></div>';
			} else {
				$float = 0;
				foreach ($end as $k => $v)
				{
					if ($i == $v) {
						$float = 1;
					}
				}
				if ($float == 1) {		
					echo '</table></div>';
				}
			}
		}
	}
} 
// -------------------
// UL LAYOUT
// -------------------
else {
	for ($i = 0; $i < $countCategories; $i++)
	{
		// Change the thumbnail for Category View
		// We are in Category View but need Categories View Settings
	
		switch((int)$this->tmpl['imagetypecv']) {
			case 0:
			case 2:
			case 4:
			case 6:
				$imageThumbnail = str_replace('medium', 'small-main', $this->categories[$i]->linkthumbnailpath);
				$imageThumbnail = str_replace('phoca_thumb_m_', 'phoca_thumb_s_', $imageThumbnail);
			break;
			default:
				$imageThumbnail = str_replace('small-main', 'medium', $this->categories[$i]->linkthumbnailpath);
				$imageThumbnail = str_replace('phoca_thumb_s_', 'phoca_thumb_m_', $imageThumbnail);
			break;
		 } 
		// - - - - - - - - - - - - - - -
		
		if ( $columns == 1 ) {
			echo '<ul>';
		} else {
			$float = 0;
			foreach ($begin as $k => $v)
			{
				if ($i == $v) {
					$float = 1;
				}
			}
			if ($float == 1) {		
				echo '<div style="position:relative;float:left;margin:10px"><ul>';
			}
		}
		
		echo '<li><a href="'.$this->categories[$i]->link.'" class="category'.$this->params->get( 'pageclass_sfx' ).'">'.$this->categories[$i]->title.'</a>&nbsp;';
		
		if ($this->categories[$i]->numlinks > 0) {echo '<span class="small">('.$this->categories[$i]->numlinks.')</span>';}
		
		echo '</li>';
		
		if ( $columns == 1 ) {
			echo '</ul>';
		} else {
			if ($i == $endFloat) {
				echo '</ul></div><div style="clear:both"></div>';
			} else {
				$float = 0;
				foreach ($end as $k => $v)
				{
					if ($i == $v) {
						$float = 1;
					}
				}
				if ($float == 1) {		
					echo '</ul></div>';
				}
			}
		}
	}
}
?>
<div class="phoca-hr"></div>