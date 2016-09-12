<?php
/*********** XML PARAMETERS AND VALUES ************/
$xml_item = "component";// component | template
$xml_file = "phocagallery.xml";		
$xml_name = "PhocaGallery";
$xml_creation_date = "28/04/2009";
$xml_author = "Jan Pavelka (www.phoca.cz)";
$xml_author_email = "info[at]phoca[dot]cz";
$xml_author_url = "www.phoca.cz";
$xml_copyright = "Jan Pavelka";
$xml_license = "GNU/GPL";
$xml_version = "2.2.4";
$xml_description = "Phoca Gallery";
$xml_copy_file = 1;//Copy other files in to administration area (only for development), ./front, ./language, ./other

$xml_menu = array (0 => "Phoca Gallery", 1 => "option=com_phocagallery", 2 => "components/com_phocagallery/assets/images/icon-16-menu.png");
$xml_submenu[0] = array (0 => "Control Panel", 1 => "option=com_phocagallery", 2 => "components/com_phocagallery/assets/images/icon-16-control-panel.png");
$xml_submenu[1] = array (0 => "Images", 1 => "option=com_phocagallery&view=phocagallerys", 2 => "components/com_phocagallery/assets/images/icon-16-menu-gal.png");
$xml_submenu[2] = array (0 => "Categories", 1 => "option=com_phocagallery&view=phocagallerycs", 2 => "components/com_phocagallery/assets/images/icon-16-menu-cat.png");
$xml_submenu[3] = array (0 => "Themes", 1 => "option=com_phocagallery&view=phocagalleryt", 2 => "components/com_phocagallery/assets/images/icon-16-menu-theme.png");
$xml_submenu[4] = array (0 => "Rating", 1 => "option=com_phocagallery&view=phocagalleryra", 2 => "components/com_phocagallery/assets/images/icon-16-menu-vote.png");
$xml_submenu[5] = array (0 => "Comments", 1 => "option=com_phocagallery&view=phocagallerycos", 2 => "components/com_phocagallery/assets/images/icon-16-menu-comment.png");
//$xml_submenu[6] = array (0 => "Users Categories", 1 => "option=com_phocagallery&view=phocagalleryucs", 2 => "components/com_phocagallery/assets/images/icon-16-menu-users-cat.png");
$xml_submenu[6] = array (0 => "Info", 1 => "option=com_phocagallery&view=phocagalleryin", 2 => "components/com_phocagallery/assets/images/icon-16-menu-info.png");

$xml_install_file = 'install.phocagallery.php'; 
$xml_uninstall_file = 'uninstall.phocagallery.php';
/*********** XML PARAMETERS AND VALUES ************/
?>