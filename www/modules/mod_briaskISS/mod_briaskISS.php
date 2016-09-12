<?php
/*
 * This file is part of briaskISS.
 *   briaskISS is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   briaskISS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with briaskISS.  If not, see <http://www.gnu.org/licenses/>.
 * Created on 4 Mar 2008
 */
 defined('_JEXEC') or die ('Restricted access');
 $doc = &Jfactory::getDocument();
 $doc->addStyleSheet(JURI::root(true) . '/modules/mod_briaskISS/mod_briaskISS.css');
 $doc->addScript(JURI::root(true) . '/modules/mod_briaskISS/mod_briaskISS.js');
 ?>
 <noscript>
	 <div>ImageSlideShow requires Javascript</div>
 </noscript>

<?php
  include_once('mod_briaskUtility.php');
  briask_collectPics($params, $module);
?>

<script type="text/javascript">
var briaskPics<?php echo $module->id?> = [0];
var briaskInstance<?php echo $module->id?> =
	new briaskISS(<?php echo $module->id; ?>,<?php echo $params->get('Sequence', 0); ?>,<?php echo $params->get('nextDelay',0); ?>,<?php echo $params->get('transDelay',0); ?>, briaskPics<?php echo $module->id?>);
</script>