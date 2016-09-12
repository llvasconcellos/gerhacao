<?php
/**
* @version $Id: mambot.class.php 393 2005-10-08 13:37:52Z akede $
* @package Joomla
* @subpackage Installer
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* Module installer
* @package Joomla
* @subpackage Installer
*/
class JCEPluginInstaller extends mosInstaller {
	/**
	* Custom install method
	* @param boolean True if installing from directory
	*/
	function install( $p_fromdir = null ) {
		global $mosConfig_absolute_path, $database;

		if (!$this->preInstallCheck( $p_fromdir, 'jceplugin' )) {
			return false;
		}

		$xmlDoc 	= $this->xmlDoc();
		$mosinstall =& $xmlDoc->documentElement;

		// Set some vars
		$e = &$mosinstall->getElementsByPath( 'name', 1 );
		$this->elementName( $e->getText() );

		$folder = $mosinstall->getAttribute( 'plugin' );
		$this->elementDir( mosPathName( $mosConfig_absolute_path . '/mambots/editors/jce/jscripts/tiny_mce/plugins/' . $folder ) );

		if(!file_exists($this->elementDir()) && !mosMakePath($this->elementDir())) {
			$this->setError( 1, 'Failed to create directory "' . $this->elementDir() . '"' );
			return false;
		}

		if ($this->parseFiles( 'files', 'plugin', 'No file is marked as plugin file' ) === false) {
			return false;
		}

		// Insert mambot in DB
		$query = "SELECT id"
		. "\n FROM #__jce_plugins"
		. "\n WHERE plugin = '" . $this->elementName() . "'"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			$this->setError( 1, 'SQL error: ' . $database->stderr( true ) );
			return false;
		}

		$id = $database->loadResult();

		if (!$id) {
            $row = new jcePlugins( $database );

            $row->name 		= $this->elementName();
            $row->plugin 	= $folder;

            $element = &$mosinstall->getElementsByPath( 'icon', 1 );
            $row->icon = $element ? $element->getText() : '';

            $element = &$mosinstall->getElementsByPath( 'layout_icon', 1 );
            $row->layout_icon = $element ? $element->getText() : '';

            $row->type = 'plugin';
			$row->access = 18;
			$row->row = 4;
			$row->ordering = 1;
			$row->published = 1;
			$row->editable = 1;

            $element = &$mosinstall->getElementsByPath( 'elements', 1 );
			$row->elements = $element ? $element->getText() : '';

            $row->iscore = 0;

			if (!$row->store()) {
				$this->setError( 1, 'SQL error: ' . $row->getError() );
				return false;
			}
		} else {
			$this->setError( 1, 'Plugin "' . $this->elementName() . '" already exists!' );
			return false;
		}
		if ($e = &$mosinstall->getElementsByPath( 'description', 1 )) {
			$this->setError( 0, $this->elementName() . '<p>' . $e->getText() . '</p>' );
		}

		return $this->copySetupFile('front');
	}
	/**
	* Custom install method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function uninstall( $id, $option, $client=0 ) {
		global $database, $mosConfig_absolute_path;

		$id = intval( $id );
		$query = "SELECT name, plugin, iscore"
		. "\n FROM #__jce_plugins"
		. "\n WHERE id = $id"
		;
		$database->setQuery( $query );

		$row = null;
		$database->loadObject( $row );
		if ($database->getErrorNum()) {
			HTML_installer::showInstallMessage( $database->stderr(), 'Uninstall -  error',
			$this->returnTo( $option, 'install&element=plugins', $client ) );
			exit();
		}
		if ($row == null) {
			HTML_installer::showInstallMessage( 'Invalid object id', 'Uninstall -  error',
			$this->returnTo( $option, 'install&element=plugins', $client ) );
			exit();
		}

		if (trim( $row->plugin ) == '') {
			HTML_installer::showInstallMessage( 'Folder field empty, cannot remove files', 'Uninstall -  error',
			$this->returnTo( $option, 'install&element=plugins', $client ) );
			exit();
		}

		$basepath 	= $mosConfig_absolute_path . '/mambots/editors/jce/jscripts/tiny_mce/plugins/' . $row->plugin . '/';
		$xmlfile 	= $basepath . $row->plugin . '.xml';

		// see if there is an xml install file, must be same name as element
		if (file_exists( $xmlfile )) {
			$this->i_xmldoc = new DOMIT_Lite_Document();
			$this->i_xmldoc->resolveErrors( true );

			if ($this->i_xmldoc->loadXML( $xmlfile, false, true )) {
				$mosinstall =& $this->i_xmldoc->documentElement;
				// get the files element
				$files_element =& $mosinstall->getElementsByPath( 'files', 1 );
				if (!is_null( $files_element )) {
					$files = $files_element->childNodes;
					foreach ($files as $file) {
						// delete the files
						$filename = $file->getText();
						if (file_exists( $basepath . $filename )) {
							$parts = pathinfo( $filename );
							$subpath = $parts['dirname'];
							if ($subpath != '' && $subpath != '.' && $subpath != '..') {
								echo '<br />Deleting: '. $basepath . $subpath;
								$result = deldir(mosPathName( $basepath . $subpath . '/' ));
							} else {
								echo '<br />Deleting: '. $basepath . $filename;
								$result = unlink( mosPathName ($basepath . $filename, false));
							}
							echo intval( $result );
						}
					}

					// remove XML file from front
					echo "Deleting XML File: $xmlfile";
					@unlink(  mosPathName ($xmlfile, false ) );

					// define folders that should not be removed
					$sysFolders = array(
						'content',
						'search'
					);
					if (!in_array( $row->folder, $sysFolders )) {
						// delete the non-system folders if empty
						if (count( mosReadDirectory( $basepath ) ) < 1) {
							deldir( $basepath );
						}
					}
				}
			}
		}

		if ($row->iscore) {
			HTML_installer::showInstallMessage( $row->name .' is a core element, and cannot be uninstalled.<br />You need to unpublish it if you don\'t want to use it',
			'Uninstall -  error', $this->returnTo( $option, 'install&element=plugins', $client ) );
			exit();
		}

		$query = "DELETE FROM #__jce_plugins"
		. "\n WHERE id = $id"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			$msg = $database->stderr;
			die( $msg );
		}
		return true;
	}
}
?>
