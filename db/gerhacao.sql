-- phpMyAdmin SQL Dump
-- version 3.0.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Set 12, 2016 as 10:21 AM
-- Versão do Servidor: 5.0.51
-- Versão do PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `catalyst`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_banner`
--

CREATE TABLE IF NOT EXISTS `jos_banner` (
  `bid` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL default '0',
  `type` varchar(10) NOT NULL default 'banner',
  `name` varchar(50) NOT NULL default '',
  `imptotal` int(11) NOT NULL default '0',
  `impmade` int(11) NOT NULL default '0',
  `clicks` int(11) NOT NULL default '0',
  `imageurl` varchar(100) NOT NULL default '',
  `clickurl` varchar(200) NOT NULL default '',
  `date` datetime default NULL,
  `showBanner` tinyint(1) NOT NULL default '0',
  `checked_out` tinyint(1) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `editor` varchar(50) default NULL,
  `custombannercode` text,
  PRIMARY KEY  (`bid`),
  KEY `viewbanner` (`showBanner`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `jos_banner`
--

INSERT INTO `jos_banner` (`bid`, `cid`, `type`, `name`, `imptotal`, `impmade`, `clicks`, `imageurl`, `clickurl`, `date`, `showBanner`, `checked_out`, `checked_out_time`, `editor`, `custombannercode`) VALUES
(1, 1, 'banner', 'OSM 1', 0, 46, 0, 'osmbanner1.png', 'http://www.opensourcematters.org', '2004-07-07 15:31:29', 1, 0, '0000-00-00 00:00:00', NULL, NULL),
(2, 1, 'banner', 'OSM 2', 0, 53, 0, 'osmbanner2.png', 'http://www.opensourcematters.org', '2004-07-07 15:31:29', 1, 0, '0000-00-00 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_bannerclient`
--

CREATE TABLE IF NOT EXISTS `jos_bannerclient` (
  `cid` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL default '',
  `contact` varchar(60) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `extrainfo` text NOT NULL,
  `checked_out` tinyint(1) NOT NULL default '0',
  `checked_out_time` time default NULL,
  `editor` varchar(50) default NULL,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `jos_bannerclient`
--

INSERT INTO `jos_bannerclient` (`cid`, `name`, `contact`, `email`, `extrainfo`, `checked_out`, `checked_out_time`, `editor`) VALUES
(1, 'Open Source Matters', 'Administrator', 'admin@opensourcematters.org', '', 0, '00:00:00', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_bannerfinish`
--

CREATE TABLE IF NOT EXISTS `jos_bannerfinish` (
  `bid` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL default '0',
  `type` varchar(10) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `impressions` int(11) NOT NULL default '0',
  `clicks` int(11) NOT NULL default '0',
  `imageurl` varchar(50) NOT NULL default '',
  `datestart` datetime default NULL,
  `dateend` datetime default NULL,
  PRIMARY KEY  (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Extraindo dados da tabela `jos_bannerfinish`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_categories`
--

CREATE TABLE IF NOT EXISTS `jos_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `image` varchar(100) NOT NULL default '',
  `section` varchar(50) NOT NULL default '',
  `image_position` varchar(10) NOT NULL default '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `editor` varchar(50) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cat_idx` (`section`,`published`,`access`),
  KEY `idx_section` (`section`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Extraindo dados da tabela `jos_categories`
--

INSERT INTO `jos_categories` (`id`, `parent_id`, `title`, `name`, `image`, `section`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `editor`, `ordering`, `access`, `count`, `params`) VALUES
(1, 0, 'Latest', 'Latest News', 'taking_notes.jpg', '1', 'left', 'The latest news from the Joomla! Team', 1, 0, '0000-00-00 00:00:00', '', 2, 0, 1, ''),
(2, 0, 'Joomla!', 'Joomla!', 'clock.jpg', 'com_weblinks', 'left', 'A selection of links that are all related to the Joomla! Project.', 1, 0, '0000-00-00 00:00:00', NULL, 0, 0, 0, ''),
(3, 0, 'Newsflash', 'Newsflash', '', '2', 'left', '', 1, 0, '0000-00-00 00:00:00', '', 2, 0, 0, ''),
(4, 0, 'Joomla!', 'Joomla!', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 2, 0, 0, ''),
(5, 0, 'Business: general', 'Business: general', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, ''),
(7, 0, 'Examples', 'Example FAQs', 'key.jpg', '3', 'left', 'Here you will find an example set of FAQs.', 1, 0, '0000-00-00 00:00:00', NULL, 0, 0, 2, ''),
(9, 0, 'Finance', 'Finance', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 5, 0, 0, ''),
(10, 0, 'Linux', 'Linux', '', 'com_newsfeeds', 'left', '<br />\r\n', 1, 0, '0000-00-00 00:00:00', NULL, 6, 0, 0, ''),
(11, 0, 'Internet', 'Internet', '', 'com_newsfeeds', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 7, 0, 0, ''),
(12, 0, 'Contacts', 'Contacts', '', 'com_contact_details', 'left', 'Contact Details for this website', 1, 0, '0000-00-00 00:00:00', NULL, 0, 0, 0, ''),
(13, 0, 'Lead Story', 'Lead Story', '', '1', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 3, 0, 0, 'imagefolders=*1*'),
(15, 0, 'Latest', 'Latest', '', '4', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, 'imagefolders=*2*'),
(16, 0, 'RokSlide', 'RokSlide', '', '2', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, 'imagefolders=*2*'),
(17, 0, 'Blog Demo', 'Blog Demo', '', '5', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, 'imagefolders=*2*'),
(18, 0, 'Rotator', 'Rotator', '', '1', 'left', '', 1, 0, '0000-00-00 00:00:00', NULL, 1, 0, 0, 'imagefolders=*2*');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_components`
--

CREATE TABLE IF NOT EXISTS `jos_components` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `menuid` int(11) unsigned NOT NULL default '0',
  `parent` int(11) unsigned NOT NULL default '0',
  `admin_menu_link` varchar(255) NOT NULL default '',
  `admin_menu_alt` varchar(255) NOT NULL default '',
  `option` varchar(50) NOT NULL default '',
  `ordering` int(11) NOT NULL default '0',
  `admin_menu_img` varchar(255) NOT NULL default '',
  `iscore` tinyint(4) NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Extraindo dados da tabela `jos_components`
--

INSERT INTO `jos_components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`) VALUES
(1, 'Banners', '', 0, 0, '', 'Banner Management', 'com_banners', 0, 'js/ThemeOffice/component.png', 0, ''),
(2, 'Manage Banners', '', 0, 1, 'option=com_banners', 'Active Banners', 'com_banners', 1, 'js/ThemeOffice/edit.png', 0, ''),
(3, 'Manage Clients', '', 0, 1, 'option=com_banners&task=listclients', 'Manage Clients', 'com_banners', 2, 'js/ThemeOffice/categories.png', 0, ''),
(4, 'Web Links', 'option=com_weblinks', 0, 0, '', 'Manage Weblinks', 'com_weblinks', 0, 'js/ThemeOffice/globe2.png', 0, ''),
(5, 'Web Link Items', '', 0, 4, 'option=com_weblinks', 'View existing weblinks', 'com_weblinks', 1, 'js/ThemeOffice/edit.png', 0, ''),
(6, 'Web Link Categories', '', 0, 4, 'option=categories&section=com_weblinks', 'Manage weblink categories', '', 2, 'js/ThemeOffice/categories.png', 0, ''),
(7, 'Contacts', 'option=com_contact', 0, 0, '', 'Edit contact details', 'com_contact', 0, 'js/ThemeOffice/user.png', 1, ''),
(8, 'Manage Contacts', '', 0, 7, 'option=com_contact', 'Edit contact details', 'com_contact', 0, 'js/ThemeOffice/edit.png', 1, ''),
(9, 'Contact Categories', '', 0, 7, 'option=categories&section=com_contact_details', 'Manage contact categories', '', 2, 'js/ThemeOffice/categories.png', 1, ''),
(10, 'Front Page', 'option=com_frontpage', 0, 0, '', 'Manage Front Page Items', 'com_frontpage', 0, 'js/ThemeOffice/component.png', 1, ''),
(11, 'Polls', 'option=com_poll', 0, 0, 'option=com_poll', 'Manage Polls', 'com_poll', 0, 'js/ThemeOffice/component.png', 0, ''),
(12, 'News Feeds', 'option=com_newsfeeds', 0, 0, '', 'News Feeds Management', 'com_newsfeeds', 0, 'js/ThemeOffice/component.png', 0, ''),
(13, 'Manage News Feeds', '', 0, 12, 'option=com_newsfeeds', 'Manage News Feeds', 'com_newsfeeds', 1, 'js/ThemeOffice/edit.png', 0, ''),
(14, 'Manage Categories', '', 0, 12, 'option=com_categories&section=com_newsfeeds', 'Manage Categories', '', 2, 'js/ThemeOffice/categories.png', 0, ''),
(15, 'Login', 'option=com_login', 0, 0, '', '', 'com_login', 0, '', 1, ''),
(16, 'Search', 'option=com_search', 0, 0, '', '', 'com_search', 0, '', 1, ''),
(17, 'Syndicate', '', 0, 0, 'option=com_syndicate&hidemainmenu=1', 'Manage Syndication Settings', 'com_syndicate', 0, 'js/ThemeOffice/component.png', 0, ''),
(18, 'Mass Mail', '', 0, 0, 'option=com_massmail&hidemainmenu=1', 'Send Mass Mail', 'com_massmail', 0, 'js/ThemeOffice/mass_email.png', 0, ''),
(19, 'JCE Admin', 'option=com_jce', 0, 0, 'option=com_jce', 'JCE Admin', 'com_jce', 0, 'js/ThemeOffice/component.png', 0, ''),
(20, 'JCE Configuration', '', 0, 19, 'option=com_jce&task=config', 'JCE Configuration', 'com_jce', 0, 'js/ThemeOffice/controlpanel.png', 0, ''),
(21, 'JCE Languages', '', 0, 19, 'option=com_jce&task=lang', 'JCE Languages', 'com_jce', 1, 'js/ThemeOffice/language.png', 0, ''),
(22, 'JCE Plugins', '', 0, 19, 'option=com_jce&task=showplugins', 'JCE Plugins', 'com_jce', 2, 'js/ThemeOffice/add_section.png', 0, ''),
(23, 'JCE Layout', '', 0, 19, 'option=com_jce&task=editlayout', 'JCE Layout', 'com_jce', 3, 'js/ThemeOffice/content.png', 0, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_contact_details`
--

CREATE TABLE IF NOT EXISTS `jos_contact_details` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `con_position` varchar(50) default NULL,
  `address` text,
  `suburb` varchar(50) default NULL,
  `state` varchar(20) default NULL,
  `country` varchar(50) default NULL,
  `postcode` varchar(10) default NULL,
  `telephone` varchar(25) default NULL,
  `fax` varchar(25) default NULL,
  `misc` mediumtext,
  `image` varchar(100) default NULL,
  `imagepos` varchar(20) default NULL,
  `email_to` varchar(100) default NULL,
  `default_con` tinyint(1) unsigned NOT NULL default '0',
  `published` tinyint(1) unsigned NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  `user_id` int(11) NOT NULL default '0',
  `catid` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `jos_contact_details`
--

INSERT INTO `jos_contact_details` (`id`, `name`, `con_position`, `address`, `suburb`, `state`, `country`, `postcode`, `telephone`, `fax`, `misc`, `image`, `imagepos`, `email_to`, `default_con`, `published`, `checked_out`, `checked_out_time`, `ordering`, `params`, `user_id`, `catid`, `access`) VALUES
(1, 'Name', 'Position', 'Street', 'Suburb', 'State', 'Country', 'Zip Code', 'Telephone', 'Fax', 'Miscellanous info', 'asterisk.png', 'top', 'email@email.com', 1, 1, 0, '0000-00-00 00:00:00', 1, '', 0, 12, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_content`
--

CREATE TABLE IF NOT EXISTS `jos_content` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `title_alias` varchar(100) NOT NULL default '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL default '0',
  `sectionid` int(11) unsigned NOT NULL default '0',
  `mask` int(11) unsigned NOT NULL default '0',
  `catid` int(11) unsigned NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL default '0',
  `created_by_alias` varchar(100) NOT NULL default '',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(11) unsigned NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` text NOT NULL,
  `version` int(11) unsigned NOT NULL default '1',
  `parentid` int(11) unsigned NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(11) unsigned NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_section` (`sectionid`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_mask` (`mask`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Extraindo dados da tabela `jos_content`
--

INSERT INTO `jos_content` (`id`, `title`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`) VALUES
(24, 'Catalyst Basic Customisation', '', '<p>A guide to the basic <b>Catalyst</b> customisation options that can be configured in the template "index.php" file with a description of each of the available options and how they affect the presentation of the template.</p>\r\n\r\n<p><strong>Catalyst</strong> features several built in configuration options that have been designed to be easily changed with a single control. You can take advantage of these customisation options by making changes in the <strong>Catalyst</strong> &quot;index.php&quot; file. Here is a breakdown of the available options:</p>\r\n<pre style="line-height: 10px; width: 610px;">\r\n$default_theme 	= "theme1";		 // [theme1... theme6]\r\n\r\n$body_style 	= "style1";		 // [style1... style6]\r\n\r\n$header_style       = "header1";	 // [header1... header10]\r\n\r\n$enable_ie6warn     = "false";       // true | false\r\n\r\n$font_family        = "catalyst";    // catalyst | geneva | optima | helvetica  \r\n					trebuchet | lucida | georgia | palatino\r\n\r\n$template_width 	= "958";		 // width in px\r\n\r\n$leftcolumn_width	= "275";		 // width in px\r\n\r\n$rightcolumn_width	= "275";		 // width in px\r\n\r\n$splitmenu_col	= "rightcol";	 // leftcol | rightcol\r\n\r\n$menu_name 		= "mainmenu";	 // mainmenu by default, can be any \r\nJoomla menu name\r\n\r\n$menu_type 		= "moomenu";	 // moomenu | suckerfish | splitmenu | module\r\n\r\n$default_font 		 "default";     // smaller | default | larger\r\n\r\n$show_pathway 	= "true";		 // true | false\r\n\r\n$show_moduleslider	= "false";	     // true | false\r\n\r\n// module slider configuration\r\n\r\n$max_mods_per_row	= 3; // maximum number of modules per row \r\n\r\n$modules_list 		= array(array("title"=>"Tab 1", "module"=>"user8"),\r\n					array("title"=>"Tab 2", "module"=>"user9"),\r\n					array("title"=>"Tab 3", "module"=>"user10"),\r\n					array("title"=>"Tab 4", "module"=>"user11"),\r\n					array("title"=>"Tab 5", "module"=>"user12"));\r\n</pre>\r\n\r\n<div style="margin-bottom:0;float:left;width:99%" class="module-hilite1">\r\n<h3><a href="#d-style" name="d-style">Theme Presets</a></h3>\r\n<strong>Catalyst</strong> has 6 pre-made theme presets that can be easily selected from the option shown below. Simply change the settings between the two " " tags to your style of choice.\r\n<pre style="font-family:Arial;padding:5px;">$default_theme = "theme1"; \r\n// [theme1...theme6]</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;margin-right:15px;padding:10px;" class="module-hilite1">\r\n<h3><a href="#d-style" name="d-style">Body Style</a></h3>\r\n<strong>Catalyst</strong> has 6 pre-made body color styles that can be easily selected from the option shown below. Simply change the settings between the two " " tags to your style of choice.\r\n<pre style="font-family:Arial;padding:5px;">$body_style = "style1"; \r\n// [style1...style6]</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;" class="module-hilite2">\r\n<h3><a href="#h-style" name="h-style">Header Style</a></h3>\r\n<strong>Catalyst</strong> has 10 pre-made header textures that can be easily selected from the option shown below. Simply change the settings between the two " " tags to your style of choice.\r\n<pre style="font-family:Arial;padding:5px;">$header_style = "header1"; \r\n// [header1...header10]</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;margin-right:15px;padding:10px;" class="module-hilite3">\r\n<h3><a href="#ie-warn" name="ie-warn">IE6 Warning</a></h3>\r\nWarn your visitors using Internet Explorer 6 to upgrade to a more secure version with this toggle.\r\n<pre style="font-family:Arial;padding:5px;">$enable_ie6warn = "true"; \r\n// true | false</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;" class="module-hilite1">\r\n<h3><a name="t-width" href="#t-width">Template Width</a></h3>\r\nYou have the ability to select the width of the template. The setting below is what you edit to either reduce or increase templates width.\r\n<pre style="font-family:Arial;padding:5px;">$template_width = "958"; \r\n// width in px </pre>\r\n</div>\r\n\r\n<div class="clr" style="height:12px;"></div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;margin-right:15px" class="module-hilite2">\r\n<h3><a href="#m-name" name="m-name">Menu Name</a></h3>\r\nThe following parameter controls which Joomla! menu is loaded for the horizontal navigation bar.<br/>\r\n<pre style="font-family:Arial;padding:5px;">$menu_name ="mainmenu";  \r\n// mainmenu by default</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;" class="module-hilite3">\r\n<h3><a href="#m-type" name="m-type">Menu Type</a></h3>\r\nYou can either, select moomenu,  suckerfish, splitmenu, or as a separate module position.\r\n<pre style="font-family:Arial;padding:5px;">$menu_type = "moomenu"; \r\n// moomenu | suckerfish \r\nsplitmenu | module</pre>\r\n</div>\r\n\r\n<div class="clr" style="height:12px;"></div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;margin-right:15px" class="module-hilite4">\r\n<h3><a name="module-count" href="#module-count">Module Count</a></h3>\r\nThe module configuration variable below controls how many consecutive modules you can have for a position. \r\n<pre style="font-family:Arial;padding:5px;">\r\n$max_mods_per_row = 3; \r\n// max number of modules per row (if \r\nwraps, adjust height)\r\n</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;" class="module-hilite3">\r\n<h3 style="margin:0;"><a name="fontfamily" href="#fontfamily">Font Family</a></h3>\r\nYou can choose which font you would like to use for your titles.\r\n<pre style="margin-bottom: 1px;">$font_family= "geneva";    \r\n // geneva|optima|helvetica| \r\ntrebuchet|lucida|georgia|palatino</pre>\r\n</div>\r\n\r\n<div class="clr" style="height:12px;"></div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;margin-right:15px" class="module-hilite4">\r\n<h3 style="margin:0;"><a name="lc-width" href="#lc-width">LeftColumn Width</a></h3>\r\nWith the following setting, you can choose the width of the left column.\r\n<pre style="margin-bottom: 1px;">$lefctolumn_width= "275";	   \r\n// width in px</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px" class="module-hilite1">\r\n<h3 style="margin:0;"><a name="rc-width" href="#rc-width">RightColumn Width</a></h3>\r\nWith the following setting, you can choose the width of the right column.\r\n<pre style="margin-bottom: 1px;">$rightcolumn_width= "275";	   \r\n// width in px</pre>\r\n</div>\r\n\r\n<div class="clr" style="height:12px;"></div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;margin-right:15px" class="module-hilite2">\r\n<h3 style="margin:0;"><a name="splitmenu-side" href="#splitmenu-side">Splitmenu Location</a></h3>\r\nWith this setting, choose to have the sidebar placed on the left or right of the mainbody. The name of the module positions changes to reflect left or right.\r\n<pre style="margin-bottom:1px;">$splitmenu_col = "rightcol";\r\n// leftcol | rightcol</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px" class="module-hilite3">\r\n<h3 style="margin:0;"><a name="d-font" href="#d-font">Default Font</a></h3>\r\nBased on your own personal preference, you can set the default font size with this setting below.\r\n<pre style="margin-bottom: 1px;">$default_font ="default";   \r\n// smaller | default | larger</pre>\r\n</div>\r\n\r\n<div class="clr" style="height:12px;"></div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:10px;margin-right:15px;" class="module-hilite1">\r\n<h3 style="margin:0;"><a name="pathway" href="#pathway">Show Pathway</a></h3>\r\nThis setting controls whether the pathway appears on your site.<br />\r\n<pre style="margin-bottom:1px;">$show_pathway= "false";  \r\n// true | false</pre>\r\n</div>\r\n\r\n<div style="margin-bottom:0;float:left;width:45%;padding:5px;" class="module-hilite2"> \r\n<h3 style="margin:0;"><a name="module-slider" href="#module-slider">Module Slider</a></h3>\r\nThere is now a convenient toggle to show the tabbed modules or not.\r\n<pre style="margin-bottom:1px;">$show_moduleslider = "true"; \r\n// true | false\r\n</pre>\r\n</div>\r\n\r\n<div class="clr" style="height:12px;"></div>\r\n\r\n<h3>Module List</h3>\r\n<div>\r\nWith the setting illustrated below, you can control the aspects of the integrated rokslide feature to a great extent. You can control the tab title and also the module position which appears in each of the tabs.<br /><br />\r\n\r\nIn the code snippet, we have 5 lines, each line controls a single tab. Each line is segregated into 2 distinct parts. These are <b>&quot;title&quot;=&gt;&quot;<a name="tab-title">Tab Title</a>&quot;,</b> which controls the Tab Title. The second part is <b>&quot;module&quot;=&gt;&quot;<a name="select-module" href="#select-module">Module Position</a>&quot;),</b>\r\n</div>\r\n\r\n<pre>\r\n$modules_list = array(array("title"=>"Group 1 Title", "module"=>"user8"),\r\n\r\narray("title"=>"Group 2 Title", "module"=>"user9"),\r\n\r\narray("title"=>"Group 3 Title", "module"=>"user10"),\r\n\r\narray("title"=>"Group 4 Title", "module"=>"user11"),\r\n\r\narray("title"=>"Group 5 Title", "module"=>"user12"));\r\n</pre>', '', 1, 0, 0, 0, '2007-03-31 17:50:33', 62, '', '2008-04-29 23:35:44', 62, 0, '0000-00-00 00:00:00', '2007-03-31 17:50:04', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 8, 0, 7, '', '', 0, 16320),
(5, 'Joomla! License Guidelines', '', '<p>This website is powered by <a href="http://www.joomla.org/">Joomla!</a>  The software and default templates on which it runs are Copyright 2005 <a href="http://www.opensourcematters.org/">Open Source Matters</a>.  All other content and data, including data entered into this website and templates added after installation, are copyrighted by their respective copyright owners.</p><p>If you want to distribute, copy or modify Joomla!, you are welcome to do so under the terms of the <a href="http://www.gnu.org/copyleft/gpl.html#SEC1">GNU General Public License</a>.  If you are unfamiliar with this license, you might want to read <a href="http://www.gnu.org/copyleft/gpl.html#SEC4">''How To Apply These Terms To Your Program''</a> and the <a href="http://www.gnu.org/licenses/gpl-faq.html">''GNU General Public License FAQ''</a>.</p>\r\n', '', 1, 0, 0, 0, '2004-08-19 20:11:07', 62, '', '2007-06-30 11:43:25', 63, 0, '0000-00-00 00:00:00', '2004-08-19 00:00:00', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=', 1, 0, 19, '', '', 0, 1247),
(6, 'Example News Item 1', 'News1', '{mosimage}Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit\r\namet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At vero eos et accusam et justo duo dolores et ea rebum.', '<p>{mosimage}Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at\r\nvero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum\r\nzzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor\r\nsit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt\r\nut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n<p>Ut wisi enim ad minim veniam, quis nostrud exerci tation\r\nullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis\r\nautem vel eum iriure dolor in hendrerit in vulputate velit esse molestie\r\nconsequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan\r\net iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis\r\ndolore te feugait nulla facilisi.</p>\r\n\r\n<p>Nam liber tempor cum soluta nobis eleifend option congue\r\nnihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum\r\ndolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod\r\ntincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim\r\nveniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut\r\naliquip ex ea commodo consequat.</p>\r\n\r\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd\r\ngubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum\r\ndolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores\r\nduo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet\r\nclita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero\r\nvoluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,\r\nconsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore\r\net dolore magna aliquyam erat.</p>\r\n\r\n<p>Consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut\r\nlabore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam\r\net justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata\r\nsanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur\r\nsadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore\r\nmagna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo\r\ndolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est\r\nLorem ipsum dolor sit amet.</p>', 1, 1, 0, 1, '2004-07-07 11:54:06', 62, '', '2004-07-07 18:05:05', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'food/coffee.jpg|left||0\r\nfood/bread.jpg|right||0', '', '', 1, 0, 2, '', '', 0, 389),
(7, 'Example News Item 2', 'News2', '<p>{mosimage}Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit\r\namet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem\r\nipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '', 1, 1, 0, 1, '2004-07-07 11:54:06', 62, '', '2004-07-07 18:11:30', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'food/bun.jpg|right||0', '', '', 1, 0, 3, '', '', 0, 227),
(8, 'Example News Item 3', 'News3', '<p>{mosimage}Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit\r\namet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem\r\nipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '', 1, 1, 0, 1, '2004-04-12 11:54:06', 62, '', '2004-07-07 18:08:23', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'fruit/pears.jpg|right||0', '', '', 1, 0, 4, '', '', 0, 183),
(9, 'Example News Item 4', 'News4', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '<p>{mosimage}Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at\r\nvero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum\r\nzzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor\r\nsit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt\r\nut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n{mospagebreak}<p>{mosimage}Ut wisi enim ad minim veniam, quis nostrud exerci tation\r\nullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis\r\nautem vel eum iriure dolor in hendrerit in vulputate velit esse molestie\r\nconsequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan\r\net iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis\r\ndolore te feugait nulla facilisi.</p>\r\n\r\n<p>{mosimage}Nam liber tempor cum soluta nobis eleifend option congue\r\nnihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum\r\ndolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod\r\ntincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim\r\nveniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut\r\naliquip ex ea commodo consequat.</p>\r\n\r\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd\r\ngubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum\r\ndolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores\r\nduo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet\r\nclita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero\r\nvoluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,\r\nconsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore\r\net dolore magna aliquyam erat.</p>\r\n\r\n{mospagebreak}<p>Consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut\r\nlabore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam\r\net justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata\r\nsanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur\r\nsadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore\r\nmagna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo\r\ndolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est\r\nLorem ipsum dolor sit amet.</p>', 1, 1, 0, 1, '2004-07-07 11:54:06', 62, '', '2004-07-07 18:10:23', 62, 0, '0000-00-00 00:00:00', '2004-07-07 00:00:00', '0000-00-00 00:00:00', 'fruit/strawberry.jpg|left||0\r\nfruit/pears.jpg|right||0\r\nfruit/cherry.jpg|left||0', '', '', 1, 0, 5, '', '', 0, 371),
(10, 'Example FAQ Item 1', 'FAQ1', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '', 1, 3, 0, 7, '2004-05-12 11:54:06', 62, '', '2004-07-07 18:10:23', 62, 0, '0000-00-00 00:00:00', '2004-01-01 00:00:00', '0000-00-00 00:00:00', '', '', '', 1, 0, 5, '', '', 0, 420),
(11, 'Example FAQ Item 2', 'FAQ2', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,\r\nsed diam voluptua. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam\r\nvoluptua. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>', '<p>{mosimage}Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at\r\nvero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum\r\nzzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor\r\nsit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt\r\nut laoreet dolore magna aliquam erat volutpat.</p>\r\n\r\n<p>{mosimage}Ut wisi enim ad minim veniam, quis nostrud exerci tation\r\nullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis\r\nautem vel eum iriure dolor in hendrerit in vulputate velit esse molestie\r\nconsequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan\r\net iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis\r\ndolore te feugait nulla facilisi.</p>\r\n\r\n<p>{mosimage}Nam liber tempor cum soluta nobis eleifend option congue\r\nnihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum\r\ndolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod\r\ntincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim\r\nveniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut\r\naliquip ex ea commodo consequat.</p>\r\n\r\n<p>Duis autem vel eum iriure dolor in hendrerit in vulputate\r\nvelit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis. At\r\nvero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd\r\ngubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum\r\ndolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores\r\nduo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet\r\nclita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero\r\nvoluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet,\r\nconsetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore\r\net dolore magna aliquyam erat.</p>\r\n\r\n<p>Consetetur sadipscing elitr, sed diam nonumy eirmod tempor\r\ninvidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero\r\neos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no\r\nsea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit\r\namet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut\r\nlabore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam\r\net justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata\r\nsanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur\r\nsadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore\r\nmagna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo\r\ndolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est\r\nLorem ipsum dolor sit amet.</p>', 1, 3, 0, 7, '2004-05-12 11:54:06', 62, 'Web master', '2004-07-07 18:10:23', 62, 0, '0000-00-00 00:00:00', '2004-01-01 00:00:00', '0000-00-00 00:00:00', 'fruit/cherry.jpg|left||0\r\nfruit/peas.jpg|right||0\r\nfood/milk.jpg|left||0', '', '', 1, 0, 5, '', '', 0, 320),
(12, 'Joomla Core Stuff', '', '	<h3>Joomla! in Action</h3>   	<p>Joomla! is used all over the world to power everything from simple, personal homepages to complex corporate web applications. Here are just some of the ways people use our software:</p>  	<ul class="clip"> 	<li>Corporate websites or portals</li> 		<li>Online commerce</li> 		<li>Small business websites</li> 		<li>Non-profit and organizational websites</li> 		<li>Government applications</li>  		<li>Corporate intranets and extranets</li> 		<li>School and church websites</li> 		<li>Personal or family homepages</li> 		<li>Community-based portals</li> 		<li>Magazines and newspapers</li> 		<li>the possibilities are limitless&hellip;</li>  	</ul>   	<p>Joomla! can be used to easily manage every aspect of your website, from adding content and images to updating a product catalog or taking online reservations.</p>   	', '', 1, 0, 0, 0, '2007-02-21 18:11:10', 62, '', '2007-03-31 21:41:18', 63, 0, '0000-00-00 00:00:00', '2007-02-21 18:10:49', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=', 1, 0, 18, '', '', 0, 8578);
INSERT INTO `jos_content` (`id`, `title`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`) VALUES
(13, 'Typography', '', '<blockquote>This page shows all of the typography styles and settings for catalyst in action. If you would like to read more detailed information on inserting the included typography into your content, check out the <a href="index.php?option=com_content&amp;task=view&amp;id=28&amp;Itemid=48">Catalyst Typography Tutorial.</a></blockquote>\r\n\r\n<div class="module-style2"><div><div><div>\r\n\r\n<h3>Font Control</h3>\r\n\r\ncatalyst allows you to have the option of simply switching the font of all the text in the template with the following setting in <b>index.php</b> (select the font name to preview):-\r\n\r\n<pre>\r\n$font_family = "catalyst";  // <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=catalyst">catalyst</a> | <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=geneva">geneva</a> | <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=optima">optima</a> | <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=helvetica">helvetica</a> | <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=trebuchet">trebuchet</a> | <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=lucida">lucida</a> | <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=georgia">georgia</a> | <a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28&amp;fontfamily=palatino">palatino</a>\r\n</pre>\r\n\r\n\r\n</div></div></div></div>\r\n\r\n<div class="componentheading">This is a ComponentHeading</div> \r\n\r\n<p>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</p>\r\n\r\n<div class="contentheading">This is a Contentheading</div>\r\n\r\n<p>Proin ac nunc eu nunc condimentum accumsan. Phasellus odio justo, euismod vitae, egestas a, porttitor in, urna. Maecenas vitae mauris. Donec vestibulum, nunc eu varius pharetra, massa est sagittis odio, sit amet eleifend elit dolor id tortor. </p>\r\n\r\n<h1>This is an H1 Header</h1>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin sit amet odio quis sapien molestie ultrices. Vivamus quis lectus. Praesent eu mi. Curabitur pharetra leo sed nisl. Nunc vel nisi. Aliquam nulla. Etiam at est. Pellentesque arcu diam, tempus nec, sodales eu, ullamcorper quis, risus. </p>\r\n\r\n<h2>This is an H2 Header</h2>\r\n\r\n<p>Proin ac nunc eu nunc condimentum accumsan. Phasellus odio justo, euismod vitae, egestas a, porttitor in, urna. Maecenas vitae mauris. Donec vestibulum, nunc eu varius pharetra, massa est sagittis odio, sit amet eleifend elit dolor id tortor. </p>\r\n\r\n<h3>This is an H3 Header</h3>\r\n\r\n<p>Mauris euismod. In ac massa vitae quam tincidunt dapibus. Ut at tortor nec mi mattis blandit. Maecenas venenatis lorem at nulla. Phasellus a libero. Sed odio odio, eleifend dignissim, feugiat vel, tempor nec, ligula. Suspendisse lacinia convallis nulla. Vestibulum posuere, lacus aliquet pulvinar faucibus, tortor urna luctus diam, vitae ultrices ante magna non tellus.</p>\r\n\r\n<h4>This is an H4 Header</h4>\r\n\r\n<p>Mauris euismod. In ac massa vitae quam tincidunt dapibus. Ut at tortor nec mi mattis blandit. Maecenas venenatis lorem at nulla. Phasellus a libero. Sed odio odio, eleifend dignissim, feugiat vel, tempor nec, ligula. Suspendisse lacinia convallis nulla. Vestibulum posuere, lacus aliquet pulvinar faucibus, tortor urna luctus diam, vitae ultrices ante magna non tellus.</p>\r\n\r\n<h4>Notice Styles are shown below</h4>\r\n\r\n<span class="attention">This is a sample of the ''attention'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="attention"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="notice">This is a sample of the ''notice'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="notice"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="alert">This is a sample of the ''alert'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="alert"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="download">This is a sample of the ''download'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="download"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="approved">This is a sample of the ''approved'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="approved"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="media">This is a sample of the ''media'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="media"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="note">This is a sample of the ''note'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="note"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="cart">This is a sample of the ''cart'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="cart"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="camera">This is a sample of the ''camera'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="camera"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<span class="doc">This is a sample of the ''doc'' style.  Use this style to denote very important information to your users. To use this use the folllowing html: <b>&lt;span class="doc"&gt;....&lt;/span&gt;</b></span>\r\n\r\n<pre>\r\nThis is a sample <b>&lt;pre&gt;...&lt;/pre&gt;</b> tag:\r\n\r\ndiv.modulebox-black div.bx1 {\r\n  background: url(../images/black/box_bl.png) 0 100% no-repeat; \r\n}\r\n\r\ndiv.modulebox-black div.bx2 {\r\n  background: url(../images/black/box_tr.png) 100% 0 no-repeat;\r\n}\r\n\r\ndiv.modulebox-black div.bx3 {\r\n  background: url(../images/black/box_tl.png) 0 0 no-repeat;\r\n  padding: 0;\r\n  margin: 0;\r\n}\r\n</pre>\r\n\r\n<h3><a name="blockquotes" href="#blockquotes">Blockquote Styles</a></h3>\r\n\r\n<blockquote><b>This is a blockquote, you will want to use the following formatting: &lt;blockquote&gt;....&lt;/blockquote&gt;</b>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</blockquote>\r\n\r\n<blockquote class="color1"><b>This is a blockquote, you will want to use the following formatting: &lt;blockquote class=&quot;<em style="font-weight: normal !important;">color1</em>&quot;&gt;<em style="font-weight: normal !important;">...</em>&lt;/blockquote&gt;</b> Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</blockquote>\r\n\r\n<blockquote class="color2"><b>This is a blockquote, you will want to use the following formatting: &lt;blockquote class=&quot;<em style="font-weight: normal !important;">color2</em>&quot;&gt;<em style="font-weight: normal !important;">...</em>&lt;/blockquote&gt;</b>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</blockquote>\r\n\r\n<blockquote class="color3"><b>This is a blockquote, you will want to use the following formatting: &lt;blockquote class=&quot;<em style="font-weight: normal !important;">color3</em>&quot;&gt;<em style="font-weight: normal !important;">...</em>&lt;/blockquote&gt;</b>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</blockquote>\r\n\r\n<blockquote class="quotes"><p><b>This is blockquote, you will want to use the following formatting: &lt;blockquote class=&quot;<em style="font-weight: normal !important;">quotes</em>&quot;&gt;<em style="font-weight: normal !important;">&lt;p&gt;...&lt;/p&gt;</em>&lt;/blockquote&gt;</b>.Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</p></blockquote>\r\n\r\n\r\n<div>\r\n<h3><a name="bulletb" href="#bulletb">List Styles - Bullets</a></h3>\r\n<p>Below is a list with <em style="font-weight: normal !important;">bullets</em>.  To use this style create a list in the following format: <b>&lt;ul class="</b><em style="font-weight: normal !important;">class name</em><b>"&gt;&lt;li&gt;....&lt;/li&gt;&lt;li&gt;....&lt;/li&gt;...&lt;/ul&gt;</b></p>\r\n\r\n<ul class="bullet-1">\r\n  <li>To use this style create a list in the following format: <b>&lt;ul class="</b><em style="font-weight: normal !important;">bullet-1</em><b>"&gt;&lt;li&gt;....&lt;/li&gt;&lt;li&gt;....&lt;/li&gt;...&lt;/ul&gt;</b>.</li>\r\n</ul>\r\n<ul class="bullet-2">\r\n  <li>To use this style create a list in the following format: <b>&lt;ul class="</b><em style="font-weight: normal !important;">bullet-2</em><b>"&gt;&lt;li&gt;....&lt;/li&gt;&lt;li&gt;....&lt;/li&gt;...&lt;/ul&gt;</b>.</li>\r\n</ul>\r\n<ul class="bullet-3">\r\n  <li>To use this style create a list in the following format: <b>&lt;ul class="</b><em style="font-weight: normal !important;">bullet-3</em><b>"&gt;&lt;li&gt;....&lt;/li&gt;&lt;li&gt;....&lt;/li&gt;...&lt;/ul&gt;</b>.</li>\r\n</ul>\r\n<ul class="bullet-4">\r\n  <li>To use this style create a list in the following format: <b>&lt;ul class="</b><em style="font-weight: normal !important;">bullet-4</em><b>"&gt;&lt;li&gt;....&lt;/li&gt;&lt;li&gt;....&lt;/li&gt;...&lt;/ul&gt;</b>.</li>\r\n</ul>\r\n<ul class="bullet-5">\r\n  <li>To use this style create a list in the following format: <b>&lt;ul class="</b><em style="font-weight: normal !important;">bullet-5</em><b>"&gt;&lt;li&gt;....&lt;/li&gt;&lt;li&gt;....&lt;/li&gt;...&lt;/ul&gt;</b>.</li>\r\n</ul>\r\n</div>\r\n\r\n<h3><a name="bulletd" href="#bulletd">Span Styles - Number</a></h3>\r\n<p>Below is a list with <em style="font-weight: normal !important;">number</em>.  To use this style create a list in the following format: <b>&lt;span class=&quot;number-[color2]&quot;&gt;1[any number]&lt;/span&gt;</b></p>\r\n\r\n<p><span class="number">1</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number</em>&quot;&gt;<em style="font-weight: normal !important;">1</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p>\r\n<p><span class="number">2</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number</em>&quot;&gt;<em style="font-weight: normal !important;">2</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p>\r\n<p><span class="number">3</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number</em>&quot;&gt;<em style="font-weight: normal !important;">3</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p><br />\r\n\r\n<p><span class="number-color">1</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number-color</em>&quot;&gt;<em style="font-weight: normal !important;">1</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p>\r\n<p><span class="number-color">2</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number-color</em>&quot;&gt;<em style="font-weight: normal !important;">2</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p>\r\n<p><span class="number-color">3</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number-color</em>&quot;&gt;<em style="font-weight: normal !important;">3</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p><br />\r\n\r\n<p><span class="number-color2">1</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number-color2</em>&quot;&gt;<em style="font-weight: normal !important;">1</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p>\r\n<p><span class="number-color2">2</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number-color2</em>&quot;&gt;<em style="font-weight: normal !important;">2</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p>\r\n<p><span class="number-color2">3</span>To use this style create a list in the following format: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">number-color2</em>&quot;&gt;<em style="font-weight: normal !important;">3</em>&lt;/span&gt;.<em style="font-weight: normal !important;">...some content...</em>.&lt;/p&gt;</b></p>\r\n\r\n<h3><a name="highlights" href="#highlights">Highlight Styles</a></h3>\r\n\r\nThis is a span that allows you to <span class="highlight">highlight words or phases</span>. Use the following format: <b>&lt;span class=&quot;<em style="font-weight: normal !important;">highlight</em>&quot;&gt;...&lt;/span&gt;</b><br /><br />\r\n\r\nThis is a span that allows you to <span class="highlight-color">highlight words or phases</span>. Use the following format: <b>&lt;span class=&quot;<em style="font-weight: normal !important;">highlight-color</em>&quot;&gt;...&lt;/span&gt;</b><br /><br />\r\n\r\nThis is a span that allows you to <span class="highlight-color2">highlight words or phases</span>. Use the following format: <b>&lt;span class=&quot;<em style="font-weight: normal !important;">highlight-color2</em>&quot;&gt;...&lt;/span&gt;</b><br /><br />\r\n\r\nThis is a span that allows you to <span class="highlight-bold">highlight words or phases</span>. Use the following format: <b>&lt;span class=&quot;<em style="font-weight: normal !important;">highlight-bold</em>&quot;&gt;...&lt;/span&gt;</b><br /><br />\r\n\r\n<h3><a name="insets" href="#insets">Inset Styles</a></h3>\r\n\r\n<p>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.<span class="inset-right">You will need to use the following formatting: <font style="font-weight: normal !important;">&lt;span class=&quot;inset-left&quot;&gt;<em style="font-weight: normal !important;">...some content...</em>&lt;/span&gt;</font></span>Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</p><p>Sed euismod magna a nibh. Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>\r\n\r\n<p>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.<span class="inset-left">You will need to use the following formatting: <font style="font-weight: normal !important;">&lt;span class=&quot;inset-left&quot;&gt;<em style="font-weight: normal !important;">...some content...</em>&lt;/span&gt;</font></span>Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.</p><p>Sed euismod magna a nibh. Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p><br />\r\n\r\n<h3><a name="dropcap" href="#dropcap">DropCap Styles</a></h3>\r\n\r\n<p><span class="dropcap">P</span>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh. Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.<br /><br />You will need to use the following formatting: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">dropcap</em>&quot;&gt;<em style="font-weight: normal !important;">P</em>&lt;/span&gt;&lt;/p&gt;</b></p>\r\n\r\n<p><span class="dropcap-color">P</span>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh. Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh.<br /><br />You will need to use the following formatting: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">dropcap-color</em>&quot;&gt;<em style="font-weight: normal !important;">P</em>&lt;/span&gt;&lt;/p&gt;</b></p>\r\n\r\n<p><span class="dropcap-color2">P</span>Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh. Praesent rutrum sapien ac felis. Phasellus elementum dolor quis turpis. Vestibulum nec mi vitae pede tincidunt nonummy. Vestibulum facilisis mollis neque. Sed orci. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed euismod magna a nibh. <br /><br />You will need to use the following formatting: <b>&lt;p&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">dropcap-color2</em>&quot;&gt;<em style="font-weight: normal !important;">P</em>&lt;/span&gt;&lt;/p&gt;</b></p>\r\n\r\n<h3><a name="important" href="#important">Important Emphasis Styles</a></h3>\r\n\r\n<div class="important"><span class="important-title">Sample Title</span>This is a span that lets you place all of the content into a nice well formed section. You will need to use the following formatting: <b>&lt;div class=&quot;<em style="font-weight: normal !important;">important</em>&quot;&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">important-title</em>&quot;&gt;<em style="font-weight: normal !important;">Sample Title</em>&lt;/span&gt;<em style="font-weight: normal !important;">...some content...</em>&lt;/div&gt;</b></div><br />\r\n\r\n<div class="important2"><span class="important-title2">Sample Title</span>This is a span that lets you place all of the content into a nice well formed section. You will need to use the following formatting: <b>&lt;div class=&quot;<em style="font-weight: normal !important;">important2</em>&quot;&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">important-title2</em>&quot;&gt;<em style="font-weight: normal !important;">Sample Title</em>&lt;/span&gt;<em style="font-weight: normal !important;">...some content...</em>&lt;/div&gt;</b></div><br />\r\n\r\n<div class="important3"><span class="important-title3">Sample Title</span>This is a span that lets you place all of the content into a nice well formed section. You will need to use the following formatting: <b>&lt;div class=&quot;<em style="font-weight: normal !important;">important3</em>&quot;&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">important-title3</em>&quot;&gt;<em style="font-weight: normal !important;">Sample Title</em>&lt;/span&gt;<em style="font-weight: normal !important;">...some content...</em>&lt;/div&gt;</b></div><br />\r\n\r\n<div class="important4"><span class="important-title4">Sample Title</span>This is a span that lets you place all of the content into a nice well formed section. You will need to use the following formatting: <b>&lt;div class=&quot;<em style="font-weight: normal !important;">important4</em>&quot;&gt;&lt;span class=&quot;<em style="font-weight: normal !important;">important-title4</em>&quot;&gt;<em style="font-weight: normal !important;">Sample Title</em>&lt;/span&gt;<em style="font-weight: normal !important;">...some content...</em>&lt;/div&gt;</b></div><br />', '', 1, 0, 0, 0, '2007-02-21 11:28:13', 62, '', '2008-04-28 15:38:25', 62, 0, '0000-00-00 00:00:00', '2007-02-21 11:28:01', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 1, 0, 17, '', '', 0, 34357),
(58, 'Portaria e Segurança', '', '<img style="margin-right: 15px" src="images/stories/example3.jpg" alt="image" align="left" /> &Egrave; cada vez maior nossa preocupa&ccedil;&atilde;o com a seguran&ccedil;a, e nos &uacute;ltimos tempos n&oacute;s tamb&eacute;m temos que nos preocupar com quem nos presta esse servi&ccedil;o de &ldquo;seguran&ccedil;a&rdquo; patrimonial. Foi pensando nisso que criamos o conceito de Agentes de Portaria que v&atilde;o al&eacute;m do simples servi&ccedil;os de porteiros de condom&iacute;nios e empresas, s&atilde;o profissionais rigorosamente selecionados e treinados para al&eacute;m das atividades comuns como controladores de acesso, recep&ccedil;&atilde;o de documentos e pessoas, atuam tamb&eacute;m no apoio a seguran&ccedil;a patrimonial por circuito de v&iacute;deo integrado, porem n&atilde;o armada e sem perder a cortesia e a amabilidade dos porteiros de outrora.<br />Sistema de circuito fechado de v&iacute;deo;<br />Melhor rela&ccedil;&atilde;o custo x benef&iacute;cio;<br />Supervis&atilde;o volante constante;<br />Comunica&ccedil;&atilde;o direta com a empresa.<br />', '', 1, 1, 0, 18, '2008-02-29 12:00:35', 62, '', '2008-12-17 13:41:48', 62, 0, '0000-00-00 00:00:00', '2008-02-29 11:59:22', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 8, 0, 4, '', '', 0, 0),
(59, 'Limpeza e Conservação', '', '<img style="margin-right: 15px" src="images/stories/example1.jpg" alt="image" align="left" /> Ind&uacute;strias, Escrit&oacute;rios, Hospitais, Escolas, Shoppings, Condom&iacute;nios. Nossos profissionais est&atilde;o totalmente preparados para a execu&ccedil;&atilde;o de servi&ccedil;os de limpeza e servi&ccedil;os complementares com padr&atilde;o de qualidade nos servi&ccedil;os terceirizados. Todos eles passam por avalia&ccedil;&atilde;o rigorosa e recebem treinamento especifico e comportamental, utilizamos m&aacute;quinas e equipamento de ultima gera&ccedil;&atilde;o, manipula&ccedil;&otilde;es de solu&ccedil;&otilde;es qu&iacute;micas, coleta de lixo, al&eacute;m do uso correto de equipamentos para a pr&oacute;pria prote&ccedil;&atilde;o. <br />Proporciona comodidade, efici&ecirc;ncia e ambientes saud&aacute;veis.', '', 1, 1, 0, 18, '2008-02-29 12:07:15', 62, '', '2008-12-17 13:38:31', 62, 0, '0000-00-00 00:00:00', '2008-02-29 12:06:05', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 7, 0, 3, '', '', 0, 0),
(60, 'Terceirização de Serviços', '', '<img style="margin-right: 15px" src="images/stories/example4.jpg" alt="image" align="left" />O objetivo da geRHa&ccedil;&atilde;o &eacute; possibilitar aos clientes, maior e melhor desempenho em suas atividades globais de administra&ccedil;&atilde;o de recursos e servi&ccedil;os, agregando valores &agrave; suas estruturas de neg&oacute;cios e oferecendo-lhes solu&ccedil;&otilde;es de servi&ccedil;os integrados para suas instala&ccedil;&otilde;es, seus clientes, consumidores e funcion&aacute;rios. Entre alguns servi&ccedil;os, est&atilde;o limpeza e conserva&ccedil;&atilde;o, copa, recep&ccedil;&atilde;o, ascensoristas, zeladores, camareiras, manobristas, auxiliares, telefonistas, manuten&ccedil;&atilde;o el&eacute;trica e hidr&aacute;ulica, manuten&ccedil;&atilde;o de <br />&aacute;reas verdes(jardinagem), apoio &agrave; produ&ccedil;&atilde;o, portaria, auditoria, consultoria e outros. <br />Desta maneira, em sinergia, agrupando todos ou partes dos servi&ccedil;os perif&eacute;ricos em uma &uacute;nica empresa possibilita investimentos tecnol&oacute;gicos e humanos e conseq&uuml;entes redu&ccedil;&otilde;es nos custos globais das empresas.', '', 1, 1, 0, 18, '2008-02-29 12:14:24', 62, '', '2008-12-17 13:40:29', 62, 0, '0000-00-00 00:00:00', '2008-02-29 12:10:57', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 18, 0, 2, '', '', 0, 0),
(14, 'Clear test', '', '<div class="clr"></div>\r\n<div style="float:left; width:200px;">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin ac odio eu tortor sagittis aliquet. Phasellus adipiscing est vitae turpis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus non leo. Ut nisi. In hac habitasse platea dictumst. Fusce adipiscing lectus eu nulla. Phasellus auctor, erat in ultrices aliquam, est diam facilisis dui, ac iaculis lectus nibh non pede. Duis lorem sapien, tempor id, pharetra vel, ultrices sed, lectus. Ut convallis dolor eget nisl. Vivamus hendrerit imperdiet mi. Sed gravida risus. Vestibulum auctor, nisi in porta dapibus, ligula nisi volutpat velit, ut aliquam risus felis in lectus. Nullam imperdiet. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer bibendum. Aliquam ultricies ligula quis ligula. Ut id pede. Curabitur hendrerit.</div>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin ac odio eu tortor sagittis aliquet. Phasellus adipiscing est vitae turpis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus non leo. Ut nisi. In hac habitasse platea dictumst. Fusce adipiscing lectus eu nulla. Phasellus auctor, erat in ultrices aliquam, est diam facilisis dui, ac iaculis lectus nibh non pede. Duis lorem sapien, tempor id, pharetra vel, ultrices sed, lectus. Ut convallis dolor eget nisl. Vivamus hendrerit imperdiet mi. Sed gravida risus. Vestibulum auctor, nisi in porta dapibus, ligula nisi volutpat velit, ut aliquam risus felis in lectus. Nullam imperdiet. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer bibendum. Aliquam ultricies ligula quis ligula. Ut id pede. Curabitur hendrerit.</p>\r\n\r\n<div style="clear:right">\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin ac odio eu tortor sagittis aliquet. Phasellus adipiscing est vitae turpis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus non leo. Ut nisi. In hac habitasse platea dictumst. Fusce adipiscing lectus eu nulla. Phasellus auctor, erat in ultrices aliquam, est diam facilisis dui, ac iaculis lectus nibh non pede. Duis lorem sapien, tempor id, pharetra vel, ultrices sed, lectus. Ut convallis dolor eget nisl. Vivamus hendrerit imperdiet mi. Sed gravida risus. Vestibulum auctor, nisi in porta dapibus, ligula nisi volutpat velit, ut aliquam risus felis in lectus. Nullam imperdiet. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer bibendum. Aliquam ultricies ligula quis ligula. Ut id pede. Curabitur hendrerit.</p>\r\n</div>\r\n\r\n<div style="clear:left">Clear Left - Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin ac odio eu tortor sagittis aliquet. Phasellus adipiscing est vitae turpis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Phasellus non leo. Ut nisi. In hac habitasse platea dictumst. Fusce adipiscing lectus eu nulla. Phasellus auctor, erat in ultrices aliquam, est diam facilisis dui, ac iaculis lectus nibh non pede. Duis lorem sapien, tempor id, pharetra vel, ultrices sed, lectus. Ut convallis dolor eget nisl. Vivamus hendrerit imperdiet mi. Sed gravida risus. Vestibulum auctor, nisi in porta dapibus, ligula nisi volutpat velit, ut aliquam risus felis in lectus. Nullam imperdiet. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Integer bibendum. Aliquam ultricies ligula quis ligula. Ut id pede. Curabitur hendrerit.</div>\r\n', '', 1, 0, 0, 0, '2007-02-23 15:25:14', 62, '', '2007-02-23 15:30:05', 62, 0, '0000-00-00 00:00:00', '2007-02-23 15:23:39', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=', 1, 0, 16, '', '', 0, 28),
(16, 'Lead Story Example', '', 'Ut imperdiet, risus non blandit mollis, nulla pede pretium lacus, et accumsan massa massa auctor metus. <a href="#">Nam ut elit</a>. Pellentesque sit amet tortor. Ut fermentum augue. Etiam a eros a nulla vehicula', '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin dolor. Morbi diam ligula, lacinia nec, sollicitudin quis, faucibus id, tortor. Donec odio. Donec volutpat porta risus. Fusce tristique odio sit amet nunc. Nunc placerat, dui vel semper feugiat, magna ipsum tempus dui, fermentum varius risus sem fringilla est. Proin eget nulla porta arcu semper laoreet. Vestibulum diam. Vestibulum auctor elit non urna. Maecenas lacus leo, gravida eu, porttitor et, placerat ut, nisl. Maecenas nec mi eu ligula dapibus interdum. Ut venenatis.</p>\r\n\r\n<p>Donec at purus ut nisl rhoncus faucibus. Vivamus ullamcorper cursus leo. Morbi erat. Aenean id quam. Proin arcu. Pellentesque et mi. Proin vitae purus a justo pellentesque consectetuer. Quisque rhoncus lorem. Sed vel mauris. Phasellus quis justo. Aliquam erat volutpat. Nullam nec lorem. </p>', 1, 1, 0, 13, '2007-03-28 16:33:13', 62, '', '2007-03-30 17:32:09', 62, 0, '0000-00-00 00:00:00', '2007-03-28 16:32:34', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 4, 0, 1, '', '', 0, 169),
(21, 'Catalyst Preset Styles', '', '<b>Catalyst</b> is a stylish professional design with lots of versatility. You can choose one of the 6 included preset styles by specifying it in the template''s "index.php" file on the following line. \r\n\r\n <pre>                 $default_theme = &quot;style1&quot;; [theme1... theme6]</pre>\r\n\r\n<p>There are also 10 different header/footer styles to choose from with <b>Catalyst</b>.  Click on the small patterned icons below to change the header/footer styles.</p><br/>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header1"><img src="images/stories/styles/head1.jpg" alt="Header 1" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:65px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header2"><img src="images/stories/styles/head2.jpg" alt="Header 2" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header3"><img src="images/stories/styles/head3.jpg" alt="Header 3" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header4"><img src="images/stories/styles/head4.jpg" alt="Header 4" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header5"><img src="images/stories/styles/head5.jpg" alt="Header 5" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<br /><br />\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header6"><img src="images/stories/styles/head6.jpg" alt="Header 6" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;margin-left:65px" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header7"><img src="images/stories/styles/head7.jpg" alt="Header 7" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header8"><img src="images/stories/styles/head8.jpg" alt="Header 8" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header9"><img src="images/stories/styles/head9.jpg" alt="Header 9" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42?theme=custom&amp;hstyle=header10"><img src="images/stories/styles/head10.jpg" alt="Header 10" align="middle" class="demo-border" style="margin-right:5px;margin-bottom:5px;margin-left:5px;" /></a>\r\n\r\n<p>The 6 styles are featured below, click on the thumbnails to load the color schemes and see them in action.</p>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42&amp;theme=theme1"><img src="images/stories/styles/ss_style1.jpg" alt="Style1" align="middle" class="demo-border" style="margin-right:25px;margin-bottom:25px;margin-left:60px;" /></a>\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42&amp;theme=theme2"><img src="images/stories/styles/ss_style2.jpg" alt="Style2" align="middle" class="demo-border" style="margin-right:1px;margin-bottom:25px;" /></a>\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42&amp;theme=theme3"><img src="images/stories/styles/ss_style3.jpg" alt="Style3" align="middle" class="demo-border" style="margin-right:25px;margin-bottom:25px;margin-left:60px;" /></a>\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42&amp;theme=theme4"><img src="images/stories/styles/ss_style4.jpg" alt="Style4" align="middle" class="demo-border" style="margin-right:1px;margin-bottom:25px;" /></a>\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42&amp;theme=theme5"><img src="images/stories/styles/ss_style5.jpg" alt="Style5" align="middle" class="demo-border" style="margin-right:25px;margin-bottom:25px;margin-left:60px;" /></a>\r\n\r\n<a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42&amp;theme=theme6"><img src="images/stories/styles/ss_style6.jpg" alt="Style6" align="middle" class="demo-border" style="margin-right:1px;margin-bottom: 25px;" /></a>', '', 1, 0, 0, 0, '2007-03-31 16:17:39', 62, '', '2008-04-29 12:14:44', 66, 0, '0000-00-00 00:00:00', '2007-03-31 16:07:27', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 12, 0, 14, '', '', 0, 178803),
(18, 'Catalyst Features', '', '<p><b>Catalyst</b> continues the chain of impressive, revolutionary, yet functional RocketTheme templates. It combines incredible styling with refined and powerful code to help your website achieve its maximum potential.</p>\r\n\r\n<ul class="bullet-1">\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42">Preset Theme Options</a></b><br />\r\n<b>Catalyst</b> includes 6 different preset theme configurations for you to use on your site. Catalyst includes 6 color styles and 10 header / footer textures for you to choose from allowing you to mix and match a variety of styles.\r\n</li>\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=20&amp;Itemid=40#rokcontentrotator">RokContentRotator</a></b><br/>\r\nThis month, the excellent mootools based module called RokContentRotator returns. This module is another fine way to introduce snippets of your top stories in a seamless fashion. You can view this module in action on the frontpage of this demo.</li>\r\n\r\n<li><b><a href="#">3-Column Layout availability</a></b><br />\r\n<b>Catalyst</b> includes the option to allow for a 1, 2, or 3 column layout.  Simply publish modules to the left and right positions and a you will get a 3 column look.    \r\n</li>\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=24&amp;Itemid=43#">IE6 Warning Message</a></b><br />\r\n<img src="images/stories/ie6.jpg" width="195" height="145" alt="image" align="right" class="demo-border" style="margin-right:10px;margin-left:10px;" />\r\nFor the <b>Catalyst</b> template we included a script that will display a message to IE6 users advising them to upgrade to a more secure browser version.  <br/><br/> This is not enabled by default, but can easily be turned on or off with a toggle in the template index.php. When enabled, users visiting your site with IE6 only, will be able to view the message.  By enabling this, you can help fight the good fight that rids the internet of the evil incarnation that is IE6!    \r\n</li>\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=20&amp;Itemid=40#integrated-rokslide">Integrated RokSlide Tabbed Modules</a></b><br />\r\nYou will observe that the modular positions, User8 through User 12 are contained within what appears to be RokSlide. In <b>Catalyst</b>, the RokSlide effect/functionality has been integrated directly into the template, providing a sleek stylish approach for extra modules, allowing you to created tabbed modules and tabbed module groups.\r\n</li>\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=13&amp;Itemid=28">Stylish Typography</a></b><br />\r\n<b>Catalyst</b> includes a vast selection of professionally styled typography to bring that extra element to your content. Choose from several typography options including various bullet and number styles as well as much much more.\r\n</li>\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=25&amp;Itemid=44">25 Module Positions</a></b><br />\r\nWith a Module Position count of 25, as well as support for many more using the new tabbed module functionality, you will be able create a wide variety of layout options and methods for presenting your site''s content. These include several positions concealed in javascript dropdown panels.\r\n</li>\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=23&amp;Itemid=46">Menu Extravaganza</a></b><br />\r\nThe new <b>RokMooMenu</b> is now accompanied by its predecessor, <b>Suckerfish</b> in a toggle option so you can easier interchange for maximum compatibility. The popular and versatile <b>SplitMenu</b> is also available and can be seen in action on this demo by default.\r\n</li>\r\n\r\n<li><b><a href="index.php?option=com_content&amp;task=view&amp;id=44&amp;Itemid=60">Rocketlauncher Available</a></b><br />\r\nThe Catalyst RocketLauncher package consists of a full 1.0.15 Joomla install, complete with all of the demo images, content, modules, and extensions. By running the installer, your Joomla site will be set up with all everything needed to create an exact implementation of the demo site automatically.\r\n</li>\r\n</ul>\r\n\r\n<div class="moduletable-hilite1">\r\n<h3>Joomla 1.5 Native</h3>\r\nA Joomla 1.5 native version of <b>Catalyst</b> is available. The front-end of your site will be the same as <b>Catalyst</b> 1.0.x but Joomla 1.5 allows for the option of back-end parameters. Therefore, all the index.php configurable options are easily accessible in the Joomla 1.5 administrator. Simply go to <b><u>Admin -> Extensions -> rt_catalyst_j15</u></b> -> Edit. All the options will appear in the right column such as the transition type for RokMooMenu.\r\n</div>', '', 1, 0, 0, 0, '2007-03-29 16:27:18', 62, '', '2008-04-30 11:07:10', 62, 0, '0000-00-00 00:00:00', '2007-03-29 16:26:55', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 4, 0, 13, '', '', 0, 36201);
INSERT INTO `jos_content` (`id`, `title`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`) VALUES
(55, 'A Empresa', '', '<!--[if gte mso 9]><xml>  <w:WordDocument>   <w:View>Normal</w:View>   <w:Zoom>0</w:Zoom>   <w:TrackMoves/>   <w:TrackFormatting/>   <w:HyphenationZone>21</w:HyphenationZone>   <w:PunctuationKerning/>   <w:ValidateAgainstSchemas/>   <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>   <w:IgnoreMixedContent>false</w:IgnoreMixedContent>   <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>   <w:DoNotPromoteQF/>   <w:LidThemeOther>PT-BR</w:LidThemeOther>   <w:LidThemeAsian>X-NONE</w:LidThemeAsian>   <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>   <w:Compatibility>    <w:BreakWrappedTables/>    <w:SnapToGridInCell/>    <w:WrapTextWithPunct/>    <w:UseAsianBreakRules/>    <w:DontGrowAutofit/>    <w:SplitPgBreakAndParaMark/>    <w:DontVertAlignCellWithSp/>    <w:DontBreakConstrainedForcedTables/>    <w:DontVertAlignInTxbx/>    <w:Word11KerningPairs/>    <w:CachedColBalance/>   </w:Compatibility>   <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>   <m:mathPr>    <m:mathFont m:val="Cambria Math"/>    <m:brkBin m:val="before"/>    <m:brkBinSub m:val="--"/>    <m:smallFrac m:val="off"/>    <m:dispDef/>    <m:lMargin m:val="0"/>    <m:rMargin m:val="0"/>    <m:defJc m:val="centerGroup"/>    <m:wrapIndent m:val="1440"/>    <m:intLim m:val="subSup"/>    <m:naryLim m:val="undOvr"/>   </m:mathPr></w:WordDocument> </xml><![endif]--><!--[if gte mso 9]><xml>  <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"   DefSemiHidden="true" DefQFormat="false" DefPriority="99"   LatentStyleCount="267">   <w:LsdException Locked="false" Priority="0" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Normal"/>   <w:LsdException Locked="false" Priority="9" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>   <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>   <w:LsdException Locked="false" Priority="39" Name="toc 1"/>   <w:LsdException Locked="false" Priority="39" Name="toc 2"/>   <w:LsdException Locked="false" Priority="39" Name="toc 3"/>   <w:LsdException Locked="false" Priority="39" Name="toc 4"/>   <w:LsdException Locked="false" Priority="39" Name="toc 5"/>   <w:LsdException Locked="false" Priority="39" Name="toc 6"/>   <w:LsdException Locked="false" Priority="39" Name="toc 7"/>   <w:LsdException Locked="false" Priority="39" Name="toc 8"/>   <w:LsdException Locked="false" Priority="39" Name="toc 9"/>   <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>   <w:LsdException Locked="false" Priority="10" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Title"/>   <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>   <w:LsdException Locked="false" Priority="11" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>   <w:LsdException Locked="false" Priority="22" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Strong"/>   <w:LsdException Locked="false" Priority="20" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>   <w:LsdException Locked="false" Priority="59" SemiHidden="false"    UnhideWhenUsed="false" Name="Table Grid"/>   <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>   <w:LsdException Locked="false" Priority="1" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>   <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading"/>   <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List"/>   <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid"/>   <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1"/>   <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2"/>   <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1"/>   <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2"/>   <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1"/>   <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2"/>   <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3"/>   <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List"/>   <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading"/>   <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List"/>   <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid"/>   <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 1"/>   <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 1"/>   <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 1"/>   <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>   <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>   <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>   <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>   <w:LsdException Locked="false" Priority="34" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>   <w:LsdException Locked="false" Priority="29" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Quote"/>   <w:LsdException Locked="false" Priority="30" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>   <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>   <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>   <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>   <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>   <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 1"/>   <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>   <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 1"/>   <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>   <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 2"/>   <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 2"/>   <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 2"/>   <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>   <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>   <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>   <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>   <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>   <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>   <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>   <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 2"/>   <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>   <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 2"/>   <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>   <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 3"/>   <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 3"/>   <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 3"/>   <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>   <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>   <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>   <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>   <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>   <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>   <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>   <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 3"/>   <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>   <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 3"/>   <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>   <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 4"/>   <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 4"/>   <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 4"/>   <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>   <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>   <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>   <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>   <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>   <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>   <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>   <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 4"/>   <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>   <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 4"/>   <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>   <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 5"/>   <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 5"/>   <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 5"/>   <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>   <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>   <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>   <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>   <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>   <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>   <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>   <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 5"/>   <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>   <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 5"/>   <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>   <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 6"/>   <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 6"/>   <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 6"/>   <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>   <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>   <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>   <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>   <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>   <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>   <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>   <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 6"/>   <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>   <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 6"/>   <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>   <w:LsdException Locked="false" Priority="19" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>   <w:LsdException Locked="false" Priority="21" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>   <w:LsdException Locked="false" Priority="31" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>   <w:LsdException Locked="false" Priority="32" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>   <w:LsdException Locked="false" Priority="33" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>   <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>   <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>  </w:LatentStyles> </xml><![endif]--> <!--  /* Font Definitions */  @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:1; 	mso-generic-font-family:roman; 	mso-font-format:other; 	mso-font-pitch:variable; 	mso-font-signature:0 0 0 0 0 0;}  /* Style Definitions */  p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:"Times New Roman","serif"; 	mso-fareast-font-family:"Times New Roman";} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-size:10.0pt; 	mso-ansi-font-size:10.0pt; 	mso-bidi-font-size:10.0pt;} @page Section1 	{size:612.0pt 792.0pt; 	margin:70.85pt 3.0cm 70.85pt 3.0cm; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.Section1 	{page:Section1;} --> <!--[if gte mso 10]> <style>  /* Style Definitions */  table.MsoNormalTable 	{mso-style-name:"Tabela normal"; 	mso-tstyle-rowband-size:0; 	mso-tstyle-colband-size:0; 	mso-style-noshow:yes; 	mso-style-priority:99; 	mso-style-qformat:yes; 	mso-style-parent:""; 	mso-padding-alt:0cm 5.4pt 0cm 5.4pt; 	mso-para-margin:0cm; 	mso-para-margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:11.0pt; 	font-family:"Calibri","sans-serif"; 	mso-ascii-font-family:Calibri; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"Times New Roman"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Calibri; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} </style> <![endif]-->  <p style="text-align: justify; line-height: 150%" class="MsoNormal"><span style="font-size: 10pt; line-height: 150%; font-family: &quot;Arial&quot;,&quot;sans-serif&quot;">A <strong><span style="color: blue">ge</span><span style="color: red">RH</span><span style="color: blue">a&ccedil;&atilde;o</span></strong><span style="color: blue"> </span>Gest&atilde;o de Recursos Humanos e Servi&ccedil;os terceirizados &eacute; resultado de mais de 25 anos de trabalho de seus fundadores como executivo de diversas empresas focadas na gest&atilde;o de recursos humanos, estrat&eacute;gias corporativas na presta&ccedil;&atilde;o de servi&ccedil;os terceirizados. O nascimento da <strong><span style="color: blue">ge</span><span style="color: red">RH</span><span style="color: blue">a&ccedil;&atilde;o</span></strong><span>&nbsp; </span>deve-se a grande necessidade do mercado em contratar empresas e profissionais capacitados e respons&aacute;veis para agregarem valores e prestarem servi&ccedil;os confi&aacute;veis e de qualidade. </span></p>  <a href="index.php?option=com_content&amp;task=view&amp;id=18&amp;Itemid=35"></a>', '', 1, 0, 0, 0, '2007-11-30 16:41:46', 62, '', '2008-12-17 13:30:48', 62, 0, '0000-00-00 00:00:00', '2007-11-30 16:41:37', '0000-00-00 00:00:00', 'monitor.png|right||0||bottom|right|', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=1\nprint=1\nemail=1', 2, 0, 1, '', '', 0, 8103),
(29, 'Catalyst Demo Content', '', '<p>The demo for <b>Catalyst</b> utilises a large stock of custom code and styling to make the most of every content item and show what catalyst can do. This basically \r\nmeans that the HTML used in the custom modules and content have customisations\r\nwhich employ the characteristics of the template to make the &quot;perfect&quot; content.</p>\r\n\r\n<p>This demo content section will take you through some of the content areas and general techniques used to further your understanding and help you realise the true potential of <b>Catalyst</b>. If you would like to quickly deploy a replica of our Catalyst demo for a new Joomla site, be sure to check out our <a href="index.php?option=com_content&amp;task=view&amp;id=44&amp;Itemid=60">RocketLauncher package</a>.</p>\r\n\r\n<h3><a name="static-content" href="#static-content">Adding Static Content to the Front-Page</a></h3>\r\n\r\nIf you wish to place a Static Content Item on the frontpage, you will have to do the following:-\r\n\r\n<ol>\r\n<li>Create your Static Content Item</li>\r\n<li>Go to Admin > menu > mainmenu</li>\r\n<li>Click the New button</li>\r\n<li>Select Link - Static Content</li>\r\n<li>Fill out the menu parameters</li>\r\n<li>Save</li>\r\n</ol>\r\n\r\n<div>\r\n<h3><a name="tabbed" href="#tabbed">Tabbed Modules</a></h3>\r\nCatalyst features an exciting new RokSlide powered tabbed module system. It is designed to allow you to publish your modules into the designated locations, creating tabs for each module position which you can customise. Currently, Catalyst uses the User8-12 module positions for your tabs plus other module positions. By default, Joomla does not include these module positions, so these must be created in the Joomla admin.<br /><br />\r\n\r\nThey can easily be created in your Joomla administrator by going to <strong>Site > Template Manager > Module Positions</strong> In the first available "empty" module position fields, type in the name "user10" in one, and "user11" in another etc... Next, click Save in the top right corner. Now, you will be able to utilise the "User10" to "User12" module positions when assigning your module''s positions in the Module Manager. \r\n</div><br />\r\n\r\n<div>\r\n<h3><a name="readmore" href="#readmore">Read More Buttons</a></h3>\r\nRead more buttons are automatically generated for content items that use the intro and main text option. However, you can easily insert them manually as witnessed on this demo. Use the following code:\r\n<pre>\r\n&lt;a href=&quot;your_link&quot; class="readon"&gt;Read More...&lt;/a&gt;\r\n</pre>\r\n</div>', '', 1, 0, 0, 0, '2007-05-28 15:42:06', 62, '', '2008-04-29 22:08:33', 62, 0, '0000-00-00 00:00:00', '2007-05-28 15:41:57', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 18, 0, 6, '', '', 0, 11233),
(20, 'Catalyst Custom Modules', '', '<p>Learn all about the exciting new RocketTheme exclusive modules that are included in the <b>Catalyst</b> template release. Here you can find instructions for installation and configuration for each of the custom modules.</p>\r\n\r\n<span class="note">\r\n<b>Video Tutorial Currently Available!</b><br/>\r\n<a href="http://tutorials.rockettheme.com/video/installing_modules/install_module_v1.5.html" target="_new">Launch the Joomla Template Module Installation Video Tutorial now!</a> <i>(Requires Flash)</i></span>\r\n\r\n<div style="width: 47%; float: left; margin-right: 20px; margin-bottom: 0;" class="module-hilite1">\r\n<h3>Step One: Installing the Module</h3>\r\n<ul class="bullet-5">\r\n<li>Download the <b>catalyst_extensions.zip</b> archive from the <strong>catalyst</strong>  download section of the RocketTheme Joomla! Club.  Unzip the archive, inside you will find individual zip files for each of the modules.</li> \r\n<li>Login in to your administrator console on your Joomla! website and navigate to the <b>Installers</b> menu item, and select <b>Modules</b> from the dropdown menu.\r\n</li>\r\n<li>In the <b>Upload Package File</b> section, click the <b>Choose File</b> button and select one of the <b>mod_modulename.zip</b> that was inside of the zip file you downloaded earlier.  Next click the <b>Upload File &amp; Install</b> button to install the module.</li></ul>\r\n</div>\r\n\r\n\r\n<div style="width: 45%; float: left; margin-bottom: 0;" class="module-hilite2">\r\n<h3>Step Two: Publishing the Module</h3>\r\n<ul class="bullet-5">\r\n<li>Now the module is installed it must be published in the appropriate module position and configured to suit our needs.  From the top menu, select <b>Modules</b> then <b>Site Modules</b>.</li>\r\n<li>This will take you to the <b>Site Module Manager</b> which allows you to configure the placement and configuration of all modules in your Joomla! website.</li>\r\n<li>Locate the module from the list. Remember there could be a few pages. Once you have found it, you can publish in 2 ways: The first is selecting the cross next to the tile or clicking the title then set <b>Publish</b> to <b>Yes</b> followed by a Save.</li></ul>\r\n</div>\r\n<div class="clr"></div>\r\n\r\n<h3><a name="signallogin" href="#signallogin">Signal Login Module</a></h3>\r\nThis month, we have implemented a login module created by RT and Mod Squad member Chris S. We thank Chris and appreciate the effort he put into this module.  The Signal Login module is installed using the procedure explained above.  Once installed, we recommend publishing it to the top template position. The default options are fine as the module is styled from within the template.<br/>\r\n\r\n\r\n<h3><a name="rokcontentrotator" href="#rokcontentrotator">RokContentRotator</a></h3>\r\nRokContentRotator is a mootools based module that is used to display your articles in a sleek fading fashion. To set up RokContentRotator, follow the install instructions above.<br/><br/>\r\nWe will take a quick look at the parameters you will find when you install this module.\r\n<ol>\r\n<li>Title: Hide</li>\r\n<li>Position: We recommend the inset position.</li>\r\n<li>Access level: Public</li>\r\n<li>Published: Yes</li>\r\n<li>Module Class Suffix and Cache: Most will leave empty by default.</li>\r\n<li>Module Mode: You have a choice of what type of content to choose from.</li>\r\n<li>Frontpage items: Show or Hide</li>\r\n<li>Count: Enter how many items you would like to show in the RokContentRotator.</li>\r\n<li>Category: Choose which Category to pull your stories from.</li>\r\n<li>Section: Choose which Section to pull your stories from.</li>\r\n<li>Click Title: You have the option to have the effect start when you mouseover the title or when you click on the title.</li>\r\n<li>Include Mootools library: Set to no, unless using this module with a template that does not include mootools javascript.</li>\r\n<li>Show Read More: Show Read More text allowing your users to be taken to the full article.</li>\r\n<li>Read More Label: Enter any text such as Read On, or Learn More.</li>\r\n<li>Transition Duration: This is the time it takes to fade to the next article on mouseover or when the title is clicked.</li>\r\n<li>Preview Length: This is the time your article will display for.</li>\r\n</ol>\r\n\r\n<h3><a name="integrated-rokslide" href="#integrated-rokslide">Integrated RokSlide Tabbed Modules</a></h3>\r\nFor <b>Catalyst</b>, we have integrated RokSlide into the core of the template so you have the RokSlide functionality in terms of tabs with stylish transitional effects with the modular layout of the template. <br /><br />\r\nRokSlide by default has 5 tabs available. Each tab has a module position ranging from User7 to User11, respective to the individual tab. Therefore, you have a single modular position per tab but you have the option of applying multiple modules to a single position. For example, you can assign 3 modules to the User11 position and they will appear in a horizontal layout in Tab 1.<br />\r\n\r\n<h3><a href="javascript:void(0);">Configuration</a></h3>\r\nConfiguration of the integrated RokSlide itself is primarily controlled in the index.php with the other template <br/>configurations. Below is a snippet related to the integrated rokslide:-<br />\r\n<pre>\r\n// module slider configuration\r\n\r\n$modules_list  = array(array("title"=>"Group 1 Stuff", "module"=>"user8"),\r\n\r\n		 array("title"=>"Group 2 Panel", "module"=>"user9"),\r\n\r\n		 array("title"=>"Group 3 Collection", "module"=>"user10"),\r\n\r\n\r\n		 array("title"=>"Group 4 Assortment", "module"=>"user11"),\r\n\r\n		 array("title"=>"Group 5 Items", "module"=>"user12"));\r\n\r\n$max_mods_per_row     = 3;     // maximum number of modules per row \r\n                                 (adjust the height if this wraps)\r\n</pre><br />\r\nWith the setting <b>$module_list</b>, you can control the aspects of the integrated rokslide feature to a great extent. You can control the tab title and also the module position which appears in each of the tabs.<br/>\r\n\r\nIn the code snippet, we have 5 lines, each line controls a single tab. Each line is segregated into 2 distinct parts. These are <b>"title"=>"<a name="tab-title">Tab Title</a>",</b> which controls the Tab Title. The second part is <b>"module"=>"<a href="#select-module" name="select-module">Module Position</a>"),</b><br /><br />\r\n\r\n<h3><a href="#slide-height" name="slider-height">Module Slider Height</a></h3>\r\nYou can change the height for the <b>Rokslide tabber</b> in the <b>template_css.css</b> file.\r\n\r\n<pre>\r\n#moduleslider-size {\r\n	height: 240px;\r\n	margin-bottom: 15px;\r\n	margin-top: 15px;\r\n	overflow: hidden;\r\n}\r\n</pre>\r\n\r\n<h3><a href="#module-count" name="module-count">Module Count</a></h3>\r\nThe module configuration variable below controls how many consecutive modules you can have for a position. \r\n<pre>$max_mods_per_row = 3; // max number of modules per row\r\n(if wraps, adjust height)\r\n</pre>\r\n\r\n<h3><a href="#positions" name="positions">Adding new positions</a></h3>\r\nWith the integrated RokSlide, you will find that you may need to add extra module positions. These positions are <b>user10, user 11, user12, advert4 and search</b>. These are not available by default in Joomla so you will need to create these yourself. To do this, follow the instructions below:- <br />\r\n<ol>\r\n<li>Login into the Joomla administrator</li>\r\n<li>Navigate to <b><u>Site -&gt; Template Manager -&gt; Module Positions</u></b></li>\r\n<li>In the column called <b>Positions</b>, find a blank field</li>\r\n<li>Once found, enter all positions one by one, e.g. <u>User10</u></li>\r\n<li>Select Save</li>\r\n</ol>\r\n', '', 1, 0, 0, 0, '2007-03-31 14:55:58', 62, '', '2008-04-29 23:45:02', 62, 0, '0000-00-00 00:00:00', '2007-03-31 14:51:19', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 17, 0, 12, '', '', 0, 27999),
(28, 'Using Catalyst Typography', '', '<p>A guide to implementing the included <b>Catalyst</b> typography styles and elements into your site as well as instructions for inserting typography using the HTML editor option of your WYSIWYG editor.</p>\r\n\r\n<span class="note">\r\n<b>Video Tutorial Currently Available!</b><br/>\r\n<a href="http://tutorials.rockettheme.com/video/using_typography/using_typography.v1.2.html" target="_new">Launch the Joomla Using Typography Video Tutorial now!</a> <i>(Requires Flash)</i></span>\r\n\r\n<p>Every RocketTheme template has its own set of content styles, these styles are known as Typography. Typography can come in a number of varying and diverse formats, ranging from simple text modifications to image insertions to improve the look, layout of your website and give it life. This tutorial will take you through the necessary steps to adding typography to your Joomla! content.</p>\r\n\r\n<h2>Inserting Typography with the HTML Editor</h2>\r\n\r\n<p>To insert the Typography into your content, you must use the HTML feature of all Joomla! content editors. The following tutorial will outline the steps in which to does this with the default Joomla! content edit, TinyMCE. Please be aware, even though there are other content editors available, all are basically the same and the steps with be just as effective if you are using another Content editor.</p>\r\n\r\n<span class="note">Please note that the following tutorial will show you how to insert HTML (typography) into Static Content, the same procedure is apparent for Content Items and Custom modules. You may skip steps 4 and 5 if you are using <b>No WYSIWYG</b> editor.</span>\r\n\r\n<div class="module-style2" style="float:left;width:48%;margin-right:15px;margin-bottom:15px;"><div><div><div>\r\n<h3><a name="step1" href="#step1">Step 1 - Login</a></h3>\r\nLogin to the Joomla! Administration Control Panel. Go to <a href="javascript:void(0);">www.yoursite.com/administrator</a>. Enter the Administrator''s Username and password.\r\n</div></div></div></div>\r\n\r\n<div class="module-style2" style="float:left;width:48%;margin-bottom:15px;"><div><div><div>\r\n<h3><a name="step2" href="#step2">Step 2 - Navigation</a></h3>\r\nNavigate to the Static Content Manager. Hover over the Content link on the top taskbar, scroll down to Static Content Manager.\r\n</div></div></div></div>\r\n\r\n<div class="clr"></div>\r\n\r\n<div class="module-style2" style="float:left;width:48%;margin-right:15px;margin-bottom:15px;"><div><div><div>\r\n<h3><a name="step3" href="#step3">Step 3 - Static Content Manager</a></h3>\r\nWhen you have selected the Static Content Manager link, you will be sent to the Static Content Manager control panel. Select either Edit (after selecting a particular content item) or New, depending on whether you want to add typography to an existing or new item.\r\n</div></div></div></div>\r\n\r\n<div class="module-style2" style="float:left;width:48%;margin-bottom:15px;"><div><div><div>\r\n<h3><a name="step4" href="#step4">Step 4 - HTML Icon</a></h3>\r\nTo add typography to your content, you must enter the HTML mode of your Content editor. In the TinyMCE editor, this an icon called &quot;HTML&quot;, in some other content editors, it is a tab. Press the icon to enter HTML mode. This step is not necessary if you are using <b>No WYSIWYG</b> editor.\r\n</div></div></div></div>\r\n\r\n<div class="clr"></div>\r\n\r\n<div class="module-style2" style="float:left;width:48%;margin-right:15px;margin-bottom:15px;"><div><div><div>\r\n<h3><a name="step5" href="#step5">Step 5 - HTML Mode</a></h3>\r\nA popup shall appear with your content in HTML format, only if you are using TinyMCE, with other editors, a new tab may become selected. You shall do all your editing here for typography.\r\n</div></div></div></div>\r\n\r\n<div class="module-style2" style="float:left;width:48%;margin-bottom:15px;"><div><div><div>\r\n<h3><a name="step6" href="#step6">Step 6 - Inserting HTML</a></h3>\r\nYou then proceed to add your HTML coding into the tab/textbox that appears in front of you. This can be any HTML such as span class typography or styled lists.\r\n</div></div></div></div>\r\n\r\n<div class="clr"></div>\r\n\r\n<center>\r\n<span class="info">You will not see the effects in the content editor, all style affects are only visible on the Frontend of your Joomla! website.\r\n</span>\r\n</center>\r\n', '', 1, 0, 0, 0, '2007-05-28 14:02:51', 62, '', '2008-04-25 12:36:49', 63, 0, '0000-00-00 00:00:00', '2007-05-28 14:01:55', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 3, 0, 5, '', '', 0, 4866),
(19, 'Installing Catalyst', '', '<p>The following is a guide that covers how to set up your new <strong>Catalyst</strong>  template on your Joomla site.  Here you can find an overview of the files included in the <b>Catalyst</b> release and instructions on installing and activating the template in your Joomla install.</p>\r\n\r\n<span class="note">\r\n<b>Video Tutorial Currently Available!</b><br/>\r\n<a href="http://tutorials.rockettheme.com/video/installing_templates/install_template_v1.01.html" target="_new">Launch the Joomla Template Installation Video Tutorial now!</a> <i>(Requires Flash)</i>\r\n</span>\r\n\r\n<h3>Downloading the Catalyst Files</h3>\r\n\r\nThe first step is to download all of the files that will be needed to install your template, custom modules, as well make modifications. Here is a quick explanation of each of the available files in the <strong>Catalyst</strong> release.\r\n\r\n<ul class="bullet-1">\r\n<li><b>Catalyst Template</b> <i>(rt_catalyst.tgz)</i> This file is the template package you will use to install your template into Joomla.</li>\r\n\r\n<li><b>Catalyst Custom Extensions</b> <i>(catalyst_extensions.zip)</i> This package contains each of the individual custom extensions included in the catalyst template release.</li>\r\n\r\n<li><b>Catalyst Source PNG''s</b> <i>(catalyst_sources.zip)</i> This zip contains the source png''s for making modifications and customizations to the images in the template as well as the logo font. There are additional sources for the transparent background, buttons, typography and headers. You will need an image editing software (preferably Adobe/Macromedia Fireworks) to utilize the Source PNG files. </li>\r\n\r\n</ul>\r\n\r\n<div style="float: left; width: 300px; margin-right: 10px;"><div class="module-hilite3"><div><div><div>\r\n<h3 style="font-size: 120%; margin-top: 0px; margin-bottom: 0px;">Step 1 - Using the Joomla installer</h3>\r\n\r\nGo to your Admin control panel for your site. In the Menu at the top, go to <b><u>Installers</u></b> > <b><u>Templates - Site</u></b>. Next browse for your <b>rt_catalyst.tgz</b> file you downloaded and then click <b>Upload File &amp; Install</b>. Now <strong>catalyst</strong>  is installed and in your template list. You should observe an introductory page in which you select <b>Continue</b>.\r\n<br />\r\n</div></div></div></div></div>\r\n\r\n\r\n<div style="float: left; width: 300px;"><div class="module-hilite4"><div><div><div>\r\n<h3 style="font-size: 120%; margin-top: 0px; margin-bottom: 0px;">Step 2 - Making Catalyst Default</h3>\r\n\r\nNext, from your admin control panel, go to <b><u>Site</u></b> > <b><u>Template Manager</u></b> > <b><u>Site Templates</u></b>. This will pull up the list of your installed templates. Find catalyst on the list and select the radio button to the left and then click <b>Default</b> up in the top right corner. Catalyst will now be the default template on your Joomla! website.\r\n</div></div></div></div></div>', '', 1, 0, 0, 0, '2007-03-31 11:04:09', 62, '', '2008-04-27 05:46:40', 64, 0, '0000-00-00 00:00:00', '2007-03-31 10:59:41', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 2, 0, 11, '', '', 0, 11021),
(23, 'Catalyst Menu Options', '', '<p><b>Catalyst</b> features the exciting and exclusive RokMooMenu system for the ultimate in menu functionality and style, as well as the popular and reliable RTSplitMenu. An option for Suckerfish is also included. Here you can read an overview of the <b>Catalyst</b> menu configuration options.</p>\r\n\r\n<span class="note">\r\n<b>Video Tutorial Currently Available!</b><br/>\r\n<a href="http://tutorials.rockettheme.com/video/menu_configuration/menu_config.v1.2.html">Launch the Joomla Menu Setup Video Tutorial now!</a> <i>(Requires Flash)</i></span>\r\n\r\n<h2>Selecting your Menu Style</h2>\r\n\r\n<p><b>Catalyst</b> allows you to choose from 3 menu options:</p>\r\n\r\n<ul class="bullet-1">\r\n	<li style="margin-bottom:0px;"><b><a href="index.php?option=com_content&amp;task=view&amp;id=23&amp;Itemid=41&amp;mtype=moomenu">RokMooMenu</a></b> - The exciting new menu system based on the ever popular Suckerfish menu style, featuring mootools powered transitions and effects.</li>\r\n	<li style="margin-bottom:0px;"><b><a href="index.php?option=com_content&amp;task=view&amp;id=23&amp;Itemid=41&amp;mtype=suckerfish">Suckerfish</a></b> - The versatile dropdown menu is now an option in combination with RokMooMenu to minimize library conflicts.</li>\r\n	<li style="margin-bottom:0px;"><b><a href="index.php?option=com_content&amp;task=view&amp;id=23&amp;Itemid=41&amp;mtype=splitmenu">SplitMenu</a></b> - Then venerable SplitMenu is a solid choice for navigation.  It works by rendering the top-level menu options horizontally, and the submenu/child items are rendered in a module on the side.</li>\r\n</ul>\r\n\r\n<p>You can designate which menu type you want to use on your site with a single control in the template''s <b>index.php</b> file which is editable via the Joomla Administrator as well as in an external text editor.</p>\r\n\r\n<pre>$menu_type = "moomenu";	// moomenu | suckerfish | splitmenu | module</pre><br />\r\n\r\n<div style="width:400px;padding-right:20px;float:left;">\r\n<h3><a name="moomenu1" href="#moomenu1">The Powerful &amp; Popular RokMooMenu</a></h3>\r\nMaking its return this month is the immensely popular RokMooMenu. This menu has been developed from scratch using the latest and greatest MooTools JavaScript framework. The RokMooMenu is a highly advanced and fully customizable menu system. Some of the great features include:\r\n<ul>\r\n<li style="margin-bottom:3px;">Hover support for IE6 using the sfHover javascript class just like in Suckerfish.</li>\r\n<li style="margin-bottom:3px;">Fully degradable to standard SuckerFish menu if javascript is not supported.</li>\r\n<li style="margin-bottom:3px;">Configurable mouse-out delay to allow for accidental mousing out of the menu.</li>\r\n<li style="margin-bottom:3px;">Completely customizable animation effects using MooTools transitions. Can be configured in X and/or Y directions.</li>\r\n<li style="margin-bottom:3px;">Support for fade-in transparency</li>\r\n<li style="margin-bottom:3px;">Experimental support for IE6 z-index bug using the iFrame hack.</li>\r\n</ul>\r\nAn example configuration as used in the demo:\r\n<pre style="width:370px;">\r\n&lt;script type=&quot;text/javascript&quot;&gt;\r\nwindow.addEvent(''domready'', function() {\r\nnew Rokmoomenu($E(''ul.menutop''), {\r\nbgiframe: false,\r\ndelay: 500,\r\nanimate: {\r\nprops: [''opacity'', ''width'', ''height''],\r\nopts: {\r\nduration:400,\r\nfps: 100,\r\ntransition: Fx.Transitions.Expo.easeOut\r\n}\r\n}\r\n});\r\n});\r\n&lt;/script&gt;</pre>\r\n</div>\r\n\r\n<div style="float:left;width:200px;">\r\n<h3 style="text-align:center;">Menu Settings</h3>\r\n<ul>\r\n<li><b>Bgiframe</b> - Can be <b>true</b> or <b>false</b>. Only turn on if you are having problems with IE and z-index. This feature is experimental.</li>\r\n\r\n<li><b>Delay</b> - Defaults to 500ms. This is the how <b>long</b> you can mouse off the menu before it vanishes.</li>\r\n\r\n<li><b>Props</b> - These are the <b>properties</b> that will be applied to the menu. Can be any combination of opacity, width, height. The two properties, width and height are affected by the transition defined in opts.</li>\r\n\r\n<li><b>Duration</b> - the <b>time</b> in ms the animation will run for.</li>\r\n\r\n<li><b>Fps</b> - <b>speed</b> of the animation - leave at 100 for best results.</li>\r\n\r\n<li><b>Transitions</b> - any of the available <a href="http://mootools.net"><b>MooTools</b></a> transitions. See below for more details.<br />For example, &quot;<b>Bounce.easeIn</b> or <b>Bounce.easeOut</b> or <b>Bounce.easeInOut</b>&quot; produce a bouncing effect.</li>\r\n</ul>\r\n</div>\r\n\r\n<div class="clr"></div>\r\n\r\n<span class="info">For more details with diagrams, visit the <a href="http://docs.mootools.net/Effects/Fx-Transitions.js">Mootools documentation site</a></span>\r\n\r\n<h3><a name="guide" href="#guide">Creating dropdown menus for RokMooMenu and Suckerfish</a></h3>\r\nTo have specific menu items appear as sub items, you must assign them to the specific navigation item in which they will originate from. The following tutorial will show you how simple it is to create your ideal menu system.<br /><br />\r\n\r\n<div>\r\n<div style="padding:10px;float:left;width:270px;margin-right:10px;">\r\n<h3 style="font-size:120%;margin-top:5px;margin-bottom:0px;"><a name="step1" href="#step1">Step 1 - Navigation</a></h3>\r\nOnce you have logged into the Joomla! Administration Area, you must navigate to the Menu area. Hover over the Menu item in the taskbar to show the dropdown menu. For this example, we are going into the mainmenu but the technique is exactly the same for all menus in the Administrative area.\r\n</div>\r\n\r\n<div style="padding:10px;float:left;width:270px;">\r\n<h3 style="font-size:120%;margin-top:5px;margin-bottom:0px;"><a href="#step2" name="step2">Step 2 - The Menu Manager</a></h3>\r\nYou will then be transported to the Menu Manager for mainmenu. At this point, you can do 2 things. The first is to edit an existing menu item, which we will be doing in this tutorial or create a New menu item to be subordinate to an existing item. Click on the item you want to be part of the dropdown menu.\r\n</div>\r\n\r\n<div style="padding:10px;float:left;width:550px;margin-top: 10px;">\r\n<h3 style="font-size:120%;margin-top:5px;margin-bottom:0px;"><a href="#step3" name="step3">Step 3 - Assigning a Parent Item</a></h3>\r\nYou can now assign the content item to the mainmenu item in which you want to be in the dropdown menu. Identify the "Parent Item" section of the manager. You will select the item in which you wish to be the parent and the source of the dropdown menu. In this example, we have selected "Home".\r\n<br/><br/>\r\n</div>\r\n\r\n</div>', '', 1, 0, 0, 0, '2007-03-31 17:39:05', 62, '', '2008-04-27 05:41:02', 63, 0, '0000-00-00 00:00:00', '2007-03-31 17:38:54', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 5, 0, 10, '', '', 0, 31779),
(56, 'Sistemas Corporativos', '', '<img style="margin-right: 15px" src="images/stories/example6.jpg" alt="image" align="left" />A geRHa&ccedil;&atilde;o disponibiliza aos seus clientes, projetos corporativos na &aacute;rea de servi&ccedil;os, oferecendo a CONTRATANTE, seguro e fian&ccedil;a, proporcionando uma total seguran&ccedil;a aos seus clientes. <br />Para melhor controle dos servi&ccedil;os a geRHa&ccedil;&atilde;o colocar&aacute; em cada localidade uma base operacional composto de uma encarregada, um supervisor e todo suporte operacional e administrativo, desenvolvendo assim, os servi&ccedil;os dentro dos projetos tra&ccedil;ados na implanta&ccedil;&atilde;o dos servi&ccedil;os.', '', 1, 1, 0, 18, '2008-01-31 18:00:00', 62, '', '2008-12-17 13:44:06', 62, 0, '0000-00-00 00:00:00', '2008-01-31 18:00:00', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 9, 0, 5, '', '', 0, 0),
(57, 'Higiene Hospitalar', '', '<img style="margin-right: 15px" src="images/stories/example5.jpg" alt="image" align="left" /> A limpeza em hospitais, centro m&eacute;dico, laborat&oacute;rios e afins v&atilde;o muito al&eacute;m da necessidade de apar&ecirc;ncia de limpeza e a geRHa&ccedil;&atilde;o &eacute; uma das poucas empresas que disp&otilde;em de profissionais e kno - how que est&atilde;o aptos a desempenhar uma atividade altamente t&eacute;cnica e exigente como a &aacute;rea de sa&uacute;de. Norteados por um sistema de padr&atilde;o internacional de servi&ccedil;os que re&uacute;ne maquin&aacute;rio, t&eacute;cnicas e treinamento humano. <br />Para integrar todos os seguimentos do mercado a&nbsp; geRHa&ccedil;&atilde;o disp&otilde;em de profissionais de reconhecida experi&ecirc;ncia, seriedade e capacidade, que projetam de forma t&eacute;cnica e funcional os servi&ccedil;os a serem terceirizados, dando uma total seguran&ccedil;a a empresas CONTRATANTE.', '', 1, 1, 0, 18, '2008-02-14 18:00:00', 62, '', '2008-12-17 13:43:35', 62, 0, '0000-00-00 00:00:00', '2008-02-26 18:00:00', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 12, 0, 1, '', '', 0, 0);
INSERT INTO `jos_content` (`id`, `title`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`) VALUES
(22, 'Tutorials', '', '<div style="float:left;width:290px;margin-right:25px"><a href="index.php?option=com_content&amp;task=view&amp;id=19&amp;Itemid=39"><img src="images/tutorial/tutorial_icon1.png" align="left" border="0" alt="Install Catalyst" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">Installing Catalyst</div>\r\n\r\n<p>An overview of template files and instructions for installing and activating the template. <a href="index.php?option=com_content&amp;task=view&amp;id=19&amp;Itemid=39">Read More...</a></p></div>\r\n\r\n<div style="float:right;width:310px"><a href="index.php?option=com_content&amp;task=view&amp;id=20&amp;Itemid=40"><img src="images/tutorial/tutorial_icon2.png" align="left" border="0" alt="Custom Modules" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">Custom Modules</div>\r\n\r\n<p>Learn all about the included <strong>Catalyst</strong> custom modules and how to configure them for your site. <a href="index.php?option=com_content&amp;task=view&amp;id=20&amp;Itemid=40">Read More...</a></p></div><div class="clr"></div><div class="clr"></div><br />\r\n\r\n<div style="float:left;width:290px;margin-right:25px"><a href="index.php?option=com_content&amp;task=view&amp;id=23&amp;Itemid=41"><img src="images/tutorial/tutorial_icon3.png" align="left" border="0" alt="Menu Options" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">Menu Options</div>\r\n\r\n<p>An overview of the <strong>Catalyst</strong> menu options including the top menu icon configuration. <a href="index.php?option=com_content&amp;task=view&amp;id=23&amp;Itemid=41">Read More...</a></p></div>\r\n\r\n<div style="float:right;width:310px"><a href="index.php?option=com_content&amp;task=view&amp;id=24&amp;Itemid=43"><img src="images/tutorial/tutorial_icon4.png" align="left" border="0" alt="Customization" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">Customisation</div>\r\n\r\n<p>A guide to the <strong>Catalyst</strong> customisation options that can be configured within the template''s <b>index.php</b>. <a href="index.php?option=com_content&amp;task=view&amp;id=24&amp;Itemid=43">Read More...</a></p></div><div class="clr"></div><br />\r\n\r\n<div style="float:left;width:290px;margin-right:25px"><a href="index.php?option=com_content&amp;task=view&amp;id=28&amp;Itemid=48"><img src="images/tutorial/tutorial_icon5.png" align="left" border="0" alt="Typography" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">Using Typography</div>\r\n\r\n<p>A guide to using the included the <strong>Catalyst</strong> typography styles in your site. <a href="index.php?option=com_content&amp;task=view&amp;id=28&amp;Itemid=48">Read More...</a></p></div>\r\n\r\n<div style="float:right;width:310px"><a href="index.php?option=com_content&amp;task=view&amp;id=29&amp;Itemid=56"><img src="images/tutorial/tutorial_icon6.png" align="left" border="0" alt="Demo Content" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">Demo Content</div>\r\n\r\n<p>A look at some of the modules and techniques used in the demo content on the frontpage of the <strong>Catalyst</strong> demo. <a href="index.php?option=com_content&amp;task=view&amp;id=29&amp;Itemid=56">Read More...</a></p></div><div class="clr"></div><br />\r\n\r\n<div style="float:left;width:290px;margin-right:25px"><a href="index.php?option=com_content&amp;task=view&amp;id=31&amp;Itemid=58"><img src="images/tutorial/tutorial_icon7.png" align="left" border="0" alt="Logo Editing" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">Logo Editing</div>\r\n\r\n<p>An overview for customizing your logo and replacing the logo text with your organization or company name and logo. <a href="index.php?option=com_content&amp;task=view&amp;id=31&amp;Itemid=58">Read More...</a></p></div>\r\n\r\n<div style="float:right;width:310px"><a href="http://tutorials.rockettheme.com/joomla-templates/catalyst-tutorials/pngfix.html"><img src="images/tutorial/tutorial_icon8.png" align="left" border="0" alt="PNGfix" class="nounder" style="margin-right:10px;margin-top:10px" /></a><div style="font-size:130%;font-weight:bold;margin-bottom:15px;margin-top:15px">PNGfix</div>\r\n\r\n<p>An overview of how to customize your <strong>Catalyst</strong> in respects to the Internet Explorer PNGfix. <a href="http://tutorials.rockettheme.com/joomla-templates/catalyst-tutorials/pngfix.html">Read More...</a></p></div>\r\n\r\n<div class="clr"></div><br />\r\n\r\n<div class="contentheading">More Catalyst Template Tutorials</div>\r\n\r\n<p>Continue learning how to configure and customize the Catalyst template with the following guides and tutorials available in the RocketTheme  members forum board.</p>\r\n\r\n<div style="width:300px;float:left;margin-right:25px">\r\n\r\n<ul>\r\n	<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33114.0/"><b>Disable Template Scripts</b></a></li>\r\n	<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33115.0/"><b>Logo Changes</b></a></li>\r\n	<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33113.0/"><b>Insert a Flash logo</b></a></li>\r\n	<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33118.0/"><b>Moving RokSlide</b></a></li>\r\n</ul>\r\n\r\n</div>\r\n\r\n<div style="width:250px;float:left">\r\n\r\n	<ul>\r\n		<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33117.0/"><b>Switching Showcase/Advert areas</b></a></li>\r\n		<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33116.0/"><b>Showcase Changes</b></a></li>\r\n		<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33111.0/"><b>Changing RocketTheme logo</b></a></li>\r\n		<li><a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,33112.0/"><b>Removing/Editing Links</b></a></li>\r\n	</ul>\r\n\r\n</div>', '', 1, 0, 0, 0, '2007-03-31 16:19:43', 62, '', '2008-04-29 14:33:09', 66, 0, '0000-00-00 00:00:00', '2007-03-31 16:12:44', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 5, 0, 9, '', '', 0, 15560),
(25, 'Module Positions', '', '<p><strong>Catalyst</strong> has been constructed with an assortment of 25 module positions allowing for one of the most versatile designs ever. These module positions are fully collapsible meaning that if there are no modules published in  particular area, that module area will not be shown. This provides you with the maximum amount of flexibility possible.</p>\r\n\r\n<p>Catalyst includes built in style and implementation for the extra module positions &quot;user10&quot;, &quot;user11&quot;, &quot;user12&quot;, &quot;advert4&quot; and &quot;search&quot;. These module positions are not defined in a default Joomla install.</p>\r\n\r\n<p>You can add these additional module positions by going to &quot;Site > Template Manager > Module Positions&quot; in your Joomla administrator. Add each of the additional module positions by entering the name in the &quot;module position&quot; field. Then click Save. Now your extra module positions will be ready for you to place module content into, catalyst will take care of the rest.</p><br />\r\n\r\n<div align="center"><img src="images/module-positions.jpg" alt="Module Positions" style="border:4px solid #f1f1f1" /></div>', '', 1, 0, 0, 0, '2007-03-31 18:18:35', 62, '', '2008-04-29 18:31:29', 62, 0, '0000-00-00 00:00:00', '2007-03-31 18:18:29', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 5, 0, 8, '', '', 0, 35453),
(31, 'Customising Your Catalyst Logo', '', '<p>The following is a step by step guide to customising your Catalyst logo image by replacing the logo text  with your own organization or company''s name and logo.</p>\r\n\r\n<span class="info">In order to utilise the Fireworks Source PNG included with the catalyst template release, you will need Adobe Fireworks. The 30-day free trial of this software as well as more information is available <a href="http://www.adobe.com/products/fireworks/" target="_new"><strong>here at the Adobe website</strong></a>.</span>\r\n\r\n<h2>Customising Your Logo</h2>\r\n\r\n<p>One of the first steps of customising your new <strong>Catalyst</strong> template will undoubtedly be altering the logo text to reflect the name of your company / organization. RocketTheme makes this process a simple one by including both the Source PNG for the template, as well as font(s) used.</p>\r\n\r\n<p>The most effective way to customise the logo is to use the included Source PNG file. When opened in Adobe Fireworks, this file contains all of the Layers in the design allowing you to tweak and change any of the image elements of the template design. The following steps will help you quickly get your new logo ready to go:</p>\r\n\r\n<div>\r\n<h3><a name="step1" href="#step1">Step 1</a></h3>\r\n\r\nFirst, open the header Source PNG file in Adobe Fireworks (source_main.png). On the right side, you will notice a taskbar named <strong>Layers</strong>. Inside this column, a list of elements within the source will appear, divided into folders. The first is Web Layers which controls the green slices on the page that are used to export the images. Click the eye which is immediately left to the folder name <strong>Web Layers</strong> to make it invisible. This allows use to edit the logo.<br /><br />\r\n<img class="demo-border" src="images/tutorial/logo/logo1.jpg" alt="Logo"/>\r\n</div>\r\n\r\n<div>\r\n<h3><a name="step2" href="#step2">Step 2</a></h3>\r\nNext, double click on the logo which should be position in top-centre. This will activate the text tool so you can edit the element. Highlight the entire text box with your cursor and type your text instead(such as your company name.)<br /><br />\r\n<img alt="Logo" class="demo-border" src="images/tutorial/logo/logo2.jpg" style="margin-bottom:15px;" /><br />\r\nNext, double click on the slogan which is beneath the logo. This will activate the text tool so you can edit the element. Highlight the entire text box with your cursor and type your text instead(such as your company slogan.)<br /><br />\r\n<img alt="Logo" class="demo-border" src="images/tutorial/logo/logo3.jpg" style="margin-bottom:15px;" /><br />\r\n<img alt="Logo" class="demo-border" src="images/tutorial/logo/logo4.jpg" /><br />\r\n</div>\r\n\r\n<div>\r\n<h3><a name="step3" href="#step3">Step 3</a></h3>\r\nOn the right column where the Web layers area is, we need to reactivate it. As you did in the initial step, select the eye icon to make it visible and subsequently making the slices visible on the canvas. Select the logo slice, either on the canvas itself or in the Web Layers folder. If you find the slice is too small, hover your cursor the blue points around the slice and drag it to a new size<br /><br />\r\n<img alt="Logo" class="demo-border" src="images/tutorial/logo/logo5.jpg" style="margin-bottom:15px;" /><br />\r\n</div>\r\n<div>\r\n<h3><a name="step4" href="#step4">Step 4</a></h3>\r\nTo export your logo, right click on the green slice that is situated above your new logo. A popup menu should appear with numerous options. The value we want to deal with is &quot;Export Selected Slice...&quot;. As the name suggests, this option will export/save this slice only out of the entire source window.\r\n<br /><br />\r\n<img alt="Logo" class="demo-border" src="images/tutorial/logo/logo6.jpg" />\r\n</div>\r\n\r\n\r\n<div>\r\n<h3><a name="step5" href="#step5">Step 5</a></h3>\r\n<p>If you are new to Fireworks, you may be wondering why it appears that there is only one style variation in the source. This is not the case as we take advantage of the Frame features of Fireworks. You need to simply switch frames to see all the other style variation sources.<br /><br />\r\nThere are a few ways to change frames and we will show 2 methods that you can use.</p>\r\n\r\n\r\n<p>In the right column where you find the Layers toolbar including the Web Layers area, you should see another tab/toolbar named Frames. Just left click on the title <b>Frames</b> to enter the frames area. Then you can click on either of the frames which are named to show which style variant is on that particular frame.</p>\r\n<img alt="Logo" class="demo-border" src="images/tutorial/logo/logo8.jpg" style="margin-bottom:15px;" /><br />\r\n\r\n<p>The second method is the most easiest and simplistic. At the bottom of the Fireworks canvas is a row of buttons, arrows just as previous and next. Select the arrows to switch between frames.</p>\r\n<img alt="Logo" class="demo-border" src="images/tutorial/logo/logo9.jpg" /><br />\r\n</div>\r\n\r\n<h3><a name="step6" href="#step6">Step 6</a></h3>\r\n<p>Once you have successfully edited then exported your new logo, you will need to upload it to your server. This process is best done via a FTP client such as <a href="http://filezilla.sourceforge.net/">Filezilla</a></p>\r\n<ol>\r\n<li>Open your FTP client on your local computer.</li>\r\n<li>Login to your web server where <strong>Catalyst</strong> is installed.</li>\r\n<li>Navigate to the /templates/rt_catalyst/images/style# directory.</li>\r\n<li>Upload <strong>logo.png</strong> to this directory (You may need to browse on the local panel in the FTP client to find where you have exported your logo).</li>\r\n<li>Clear your browser cache before viewing such as using the keyboard commands on Windows, <strong>Ctrl+F5</strong>.</li>\r\n</ol>\r\n\r\n<span class="note">\r\nEnsure that you are uploading the correct logo to avoid confusion if it does not change.\r\nAlso take into account hosting permissions. Sometimes, hosts which are not designed for \r\nJoomla may have permissions not suited for the setup, thus, the upload will not be complete.\r\nIn this case, contact your hosting provider.\r\n</span>', '', 1, 0, 0, 0, '2007-05-29 15:46:24', 62, '', '2008-04-27 06:56:23', 64, 0, '0000-00-00 00:00:00', '2007-05-29 15:45:56', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 7, 0, 4, '', '', 0, 9411),
(30, 'Module Variations', '', '<p><b>Catalyst</b> provides the ability to use 4 subtle module variations to increase the appeal of your module content and highlight key areas.  Through the use of these module suffixes, you can make any module stand out on your site. To use these, just add the hilite names in the <b>module class suffix</b> parameter of the module in the <b>Module Manager</b> located in your Joomla administrator.</p>\r\n\r\n<img alt="Module Suffix Setting" src="images/stories/mod_suffix.jpg" />', '', 1, 0, 0, 0, '2007-05-29 11:33:43', 62, '', '2008-04-27 08:15:14', 64, 0, '0000-00-00 00:00:00', '2007-05-29 11:33:14', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=1\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 7, 0, 3, '', '', 0, 17639),
(44, 'RocketLauncher', '', '<div class="contentheading">RocketLauncher - Rapid Template Deployment</div>\r\n\r\n<p>RocketTheme templates features some amazing advanced features and layout possibilities that open up a wide world of options for your sites. Unfortunately, the default Joomla content in a new Joomla install is very limited and does little to take advantage of the abilities of a modern Joomla template.</p>\r\n<p>Each of our demo sites has been highly customized to show off the abilities of each template and give that "professional touch" to the look and feel of the content. This professional look could not be achieved without hands on manual customization, that is, until now.</p>\r\n\r\n<p><b>Catalyst</b> now features the exciting new RocketLauncher custom Joomla install option. With the RocketLauncher <b>Catalyst</b> Joomla installer, you can instantly create a Joomla site complete with custom content that is a perfect replica of our <b>Catalyst</b> demo site that has been professionally tuned to look its best, all with just a few clicks.</p>\r\n\r\n<p>The <b>catalyst</b> RocketLauncher package consists of a full 1.0.15 Joomla install, complete with all of the demo images, content, modules, and extensions. By running the installer, your Joomla site will be set up with all everything needed to create an exact implementation of the demo site automatically.</p>\r\n\r\n<span class="alert">RocketLauncher includes a FULL Joomla install, in addition to the template and demo contents. The Joomla installation process is necessary in creating the demo content, therefore RocketLauncher will only work properly as a new Joomla installation. It can not be used on an existing Joomla installation.</span><br />\r\n\r\n<h3>RocketLauncher Installation Video Tutorial</h3><p>Learn the steps to uploading the RocketLauncher package files to your server and installing the RocketLauncher template packages by following along with the steps in this detailed video tutorial. It''s now easier than ever before to deploy a replica of the RocketTheme template demo sites. <a href="http://demo.rockettheme.com/jul07/media/video_tutorial/rt_launcher_full.v1.2.html" target="_new">Launch Video...</a></p>\r\n\r\n<h3>Uploading RocketLauncher to your Root</h3><p>An in depth guide that details the steps necessary to properly upload the files from the RocketLauncher template package directly to the root of your site. This will ensure your RocketLauncher installation installs to the root of your site, and not in a subfolder.  <a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,20046.0/" target="_new">Learn more...</a></p>', '', 1, 0, 0, 0, '2007-05-20 12:56:19', 62, '', '2008-04-27 07:54:17', 64, 0, '0000-00-00 00:00:00', '2007-05-20 12:56:10', '0000-00-00 00:00:00', '', '', 'menu_image=-1\nitem_title=0\npageclass_sfx=\nback_button=0\nrating=0\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0', 2, 0, 2, '', '', 0, 16437),
(49, 'Lead Story Example', 'Lead Story Example', 'DENVER, Colorado (AP) -- United Airlines, hit hard by weekend storms in the Midwest, canceled dozens more flights Thursday as the second storm since Christmas threatened to pile 20 inches of new snow on Colorado.', 'Traffic along southbound Interstate 25 slows during heavy snow and high winds at Thornton, Colorado, near Denver, on Thursday.\r\n\r\nUp to 8 inches of new snow were expected in Denver, which set a record for its snowiest Christmas with the nearly 8 inches that fell Tuesday.\r\n\r\nUnited canceled 168 flights nationwide Thursday mostly because of the weather in Denver, its second-largest hub, to help prevent planes from being stranded there. That''s about 5 percent of the airline''s daily schedule.', 1, 5, 0, 17, '2007-08-30 12:42:29', 63, '', '2007-12-27 20:03:50', 62, 0, '0000-00-00 00:00:00', '2007-08-30 12:41:30', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 3, 0, 4, '', '', 0, 59),
(50, 'Another Article', 'Another Article', 'One of the most famously flawed stamps in U.S. history sold for $825,000 to a New York man who bought it slightly cheaper than the record price another "Inverted Jenny" copy fetched at auction last month.\r\n\r\n\r\n', 'The 1918 "Inverted Jenny" stamp is a misprint featuring an upside-down biplane.\r\n\r\nThe rare 1918 24-cent stamp, depicting an upside-down Curtis JN-4 biplane known as "Jenny," was sold privately this week to a Wall Street executive who did not want to be identified.\r\n\r\nHeritage Auction Galleries president Greg Rohan, who brokered the sale, said the buyer is the same collector who lost an auction last month in which another "Inverted Jenny" sold for $977,500.', 1, 5, 0, 17, '2007-08-30 12:43:11', 63, '', '2007-12-27 20:03:09', 62, 0, '0000-00-00 00:00:00', '2007-08-30 12:42:37', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 3, 0, 3, '', '', 0, 65),
(51, 'Yet Another Article', 'Yet Another Article', 'A 300-pound Siberian tiger apparently jumped that wall Tuesday, zoo director Manuel Mollinedo said, attacking and killing a 17-year-old boy and injuring two other young men, brothers who were visiting the zoo with the teenager.', '"I think the tiger could have grabbed onto something, maybe a ledge," Mollinedo said. "She had to jump. How she was able to jump so high is amazing to me, but she''s an exotic animal."\r\n\r\nMollinedo also said the dry moat between the wall and the tiger exhibit is 33 feet, not 20 feet as originally reported. The wall, he said, is 12 and a half feet, not 18 feet. The exhibit was built in 1940, he said.', 1, 5, 0, 17, '2007-08-30 12:43:11', 63, '', '2007-12-27 20:04:29', 62, 0, '0000-00-00 00:00:00', '2007-08-30 12:42:37', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 6, 0, 2, '', '', 0, 62),
(52, 'Great Story Example', 'Great Story Example', 'The photographer who took images of former Pakistani Prime Minister Benazir Bhutto moments before her assassination Thursday told CNN he was "surprised" to see her rise through the sunroof of her vehicle to wave to supporters after delivering her speech.\r\n', '"I ran up, got as close as I got, made a few pictures of her waving to the crowd," Getty Images senior staff photographer John Moore told CNN''s online streaming news service, CNN.com Live, in a phone interview Thursday from Islamabad, Pakistan.\r\n\r\n"And then suddenly, there were a few gunshots that rang out, and she went down, she went down through the sunroof," he said. "And just at that moment I raised my camera up and the blast happened. ... And then, of course, there was chaos." Watch Moore describe Bhutto''s final moments', 1, 5, 0, 17, '2007-08-30 12:42:29', 63, '', '2007-12-27 20:04:25', 62, 0, '0000-00-00 00:00:00', '2007-08-30 12:41:30', '0000-00-00 00:00:00', '', '', 'pageclass_sfx=\nback_button=\nitem_title=1\nlink_titles=\nintrotext=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nkeyref=\ndocbook_type=', 3, 0, 1, '', '', 0, 54);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_content_frontpage`
--

CREATE TABLE IF NOT EXISTS `jos_content_frontpage` (
  `content_id` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  PRIMARY KEY  (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_content_frontpage`
--

INSERT INTO `jos_content_frontpage` (`content_id`, `ordering`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_content_rating`
--

CREATE TABLE IF NOT EXISTS `jos_content_rating` (
  `content_id` int(11) NOT NULL default '0',
  `rating_sum` int(11) unsigned NOT NULL default '0',
  `rating_count` int(11) unsigned NOT NULL default '0',
  `lastip` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_content_rating`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_core_acl_aro`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro` (
  `aro_id` int(11) NOT NULL auto_increment,
  `section_value` varchar(240) NOT NULL default '0',
  `value` varchar(240) NOT NULL default '',
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`aro_id`),
  UNIQUE KEY `jos_gacl_section_value_value_aro` (`section_value`(100),`value`(100)),
  KEY `jos_gacl_hidden_aro` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `jos_core_acl_aro`
--

INSERT INTO `jos_core_acl_aro` (`aro_id`, `section_value`, `value`, `order_value`, `name`, `hidden`) VALUES
(10, 'users', '62', 0, 'Administrator', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_core_acl_aro_groups`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro_groups` (
  `group_id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `lft` int(11) NOT NULL default '0',
  `rgt` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`),
  KEY `parent_id_aro_groups` (`parent_id`),
  KEY `jos_gacl_parent_id_aro_groups` (`parent_id`),
  KEY `jos_gacl_lft_rgt_aro_groups` (`lft`,`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Extraindo dados da tabela `jos_core_acl_aro_groups`
--

INSERT INTO `jos_core_acl_aro_groups` (`group_id`, `parent_id`, `name`, `lft`, `rgt`) VALUES
(17, 0, 'ROOT', 1, 22),
(28, 17, 'USERS', 2, 21),
(29, 28, 'Public Frontend', 3, 12),
(18, 29, 'Registered', 4, 11),
(19, 18, 'Author', 5, 10),
(20, 19, 'Editor', 6, 9),
(21, 20, 'Publisher', 7, 8),
(30, 28, 'Public Backend', 13, 20),
(23, 30, 'Manager', 14, 19),
(24, 23, 'Administrator', 15, 18),
(25, 24, 'Super Administrator', 16, 17);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_core_acl_aro_sections`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_aro_sections` (
  `section_id` int(11) NOT NULL auto_increment,
  `value` varchar(230) NOT NULL default '',
  `order_value` int(11) NOT NULL default '0',
  `name` varchar(230) NOT NULL default '',
  `hidden` int(11) NOT NULL default '0',
  PRIMARY KEY  (`section_id`),
  UNIQUE KEY `value_aro_sections` (`value`),
  UNIQUE KEY `jos_gacl_value_aro_sections` (`value`),
  KEY `hidden_aro_sections` (`hidden`),
  KEY `jos_gacl_hidden_aro_sections` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `jos_core_acl_aro_sections`
--

INSERT INTO `jos_core_acl_aro_sections` (`section_id`, `value`, `order_value`, `name`, `hidden`) VALUES
(10, 'users', 1, 'Users', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_core_acl_groups_aro_map`
--

CREATE TABLE IF NOT EXISTS `jos_core_acl_groups_aro_map` (
  `group_id` int(11) NOT NULL default '0',
  `section_value` varchar(240) NOT NULL default '',
  `aro_id` int(11) NOT NULL default '0',
  UNIQUE KEY `group_id_aro_id_groups_aro_map` (`group_id`,`section_value`,`aro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_core_acl_groups_aro_map`
--

INSERT INTO `jos_core_acl_groups_aro_map` (`group_id`, `section_value`, `aro_id`) VALUES
(25, '', 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_core_log_items`
--

CREATE TABLE IF NOT EXISTS `jos_core_log_items` (
  `time_stamp` date NOT NULL default '0000-00-00',
  `item_table` varchar(50) NOT NULL default '',
  `item_id` int(11) unsigned NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_core_log_items`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_core_log_searches`
--

CREATE TABLE IF NOT EXISTS `jos_core_log_searches` (
  `search_term` varchar(128) NOT NULL default '',
  `hits` int(11) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_core_log_searches`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_groups`
--

CREATE TABLE IF NOT EXISTS `jos_groups` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_groups`
--

INSERT INTO `jos_groups` (`id`, `name`) VALUES
(0, 'Public'),
(1, 'Registered'),
(2, 'Special');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_jce_langs`
--

CREATE TABLE IF NOT EXISTS `jos_jce_langs` (
  `id` int(11) NOT NULL auto_increment,
  `Name` varchar(100) NOT NULL default '',
  `lang` varchar(100) NOT NULL default '',
  `published` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `jos_jce_langs`
--

INSERT INTO `jos_jce_langs` (`id`, `Name`, `lang`, `published`) VALUES
(1, 'English', 'en', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_jce_plugins`
--

CREATE TABLE IF NOT EXISTS `jos_jce_plugins` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `plugin` varchar(100) NOT NULL default '',
  `type` varchar(100) NOT NULL default 'plugin',
  `icon` varchar(255) NOT NULL default '',
  `layout_icon` varchar(255) NOT NULL default '',
  `access` tinyint(3) unsigned NOT NULL default '18',
  `row` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(3) NOT NULL default '0',
  `editable` tinyint(3) NOT NULL default '0',
  `elements` varchar(255) NOT NULL default '',
  `iscore` tinyint(3) NOT NULL default '0',
  `client_id` tinyint(3) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `plugin` (`plugin`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Extraindo dados da tabela `jos_jce_plugins`
--

INSERT INTO `jos_jce_plugins` (`id`, `name`, `plugin`, `type`, `icon`, `layout_icon`, `access`, `row`, `ordering`, `published`, `editable`, `elements`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
(1, 'Context Menu', 'contextmenu', 'plugin', '', '', 18, 0, 0, 0, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(2, 'Directionality', 'directionality', 'plugin', 'ltr,rtl', 'directionality', 18, 3, 8, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(3, 'Emotions', 'emotions', 'plugin', 'emotions', 'emotions', 18, 4, 12, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(4, 'Fullscreen', 'fullscreen', 'plugin', 'fullscreen', 'fullscreen', 18, 4, 6, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(5, 'Paste', 'paste', 'plugin', 'pasteword,pastetext', 'paste', 18, 1, 16, 1, 1, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(6, 'Preview', 'preview', 'plugin', 'preview', 'preview', 18, 4, 1, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(7, 'Tables', 'table', 'plugin', 'tablecontrols', 'buttons', 18, 2, 8, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(8, 'Print', 'print', 'plugin', 'print', 'print', 18, 4, 3, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(9, 'Search Replace', 'searchreplace', 'plugin', 'search,replace', 'searchreplace', 18, 1, 17, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(10, 'Styles', 'style', 'plugin', 'styleprops', 'styleprops', 18, 4, 7, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(11, 'Non-Breaking', 'nonbreaking', 'plugin', 'nonbreaking', 'nonbreaking', 18, 4, 8, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(12, 'Visual Characters', 'visualchars', 'plugin', 'visualchars', 'visualchars', 18, 4, 9, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(13, 'XHTML Xtras', 'xhtmlxtras', 'plugin', 'cite,abbr,acronym,del,ins,attribs', 'xhtmlxtras', 18, 4, 10, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(14, 'Image Manager', 'imgmanager', 'plugin', '', 'imgmanager', 18, 4, 13, 1, 1, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(15, 'Advanced Link', 'advlink', 'plugin', '', 'advlink', 18, 4, 14, 1, 1, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(16, 'Spell Checker', 'spellchecker', 'plugin', 'spellchecker', 'spellchecker', 18, 4, 15, 1, 1, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(17, 'Layers', 'layer', 'plugin', 'insertlayer,moveforward,movebackward,absolute', 'layer', 18, 4, 11, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(18, 'Font ForeColour', 'forecolor', 'command', 'forecolor', 'forecolor', 18, 3, 4, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(19, 'Bold', 'bold', 'command', 'bold', 'bold', 18, 1, 5, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(20, 'Italic', 'italic', 'command', 'italic', 'italic', 18, 1, 6, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(21, 'Underline', 'underline', 'command', 'underline', 'underline', 18, 1, 7, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(22, 'Font BackColour', 'backcolor', 'command', 'backcolor', 'backcolor', 18, 3, 5, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(23, 'Unlink', 'unlink', 'command', 'unlink', 'unlink', 18, 2, 11, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(24, 'Font Select', 'fontselect', 'command', 'fontselect', 'fontselect', 18, 3, 2, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(25, 'Font Size Select', 'fontsizeselect', 'command', 'fontsizeselect', 'fontsizeselect', 18, 3, 3, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(26, 'Style Select', 'styleselect', 'command', 'styleselect', 'styleselect', 18, 3, 1, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(27, 'New Document', 'newdocument', 'command', 'newdocument', 'newdocument', 18, 1, 4, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(28, 'Help', 'help', 'command', 'help', 'help', 18, 1, 3, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(29, 'StrikeThrough', 'strikethrough', 'command', 'strikethrough', 'strikethrough', 18, 1, 12, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(30, 'Indent', 'indent', 'command', 'indent', 'indent', 18, 1, 11, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(31, 'Outdent', 'outdent', 'command', 'outdent', 'outdent', 18, 1, 10, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(32, 'Undo', 'undo', 'command', 'undo', 'undo', 18, 1, 1, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(33, 'Redo', 'redo', 'command', 'redo', 'redo', 18, 1, 2, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(34, 'Horizontal Rule', 'hr', 'command', 'hr', 'hr', 18, 2, 1, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(35, 'HTML', 'html', 'command', 'code', 'code', 18, 1, 13, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(36, 'Numbered List', 'numlist', 'command', 'numlist', 'numlist', 18, 1, 9, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(37, 'Bullet List', 'bullist', 'command', 'bullist', 'bullist', 18, 1, 8, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(38, 'Clipboard Actions', 'clipboard', 'command', 'cut,copy,paste', 'clipboard', 18, 1, 16, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(39, 'Subscript', 'sub', 'command', 'sub', 'sub', 18, 2, 2, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(40, 'Superscript', 'sup', 'command', 'sup', 'sup', 18, 2, 3, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(41, 'Visual Aid', 'visualaid', 'command', 'visualaid', 'visualaid', 18, 3, 7, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(42, 'Character Map', 'charmap', 'command', 'charmap', 'charmap', 18, 3, 6, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(43, 'Justify Full', 'full', 'command', 'justifyfull', 'justifyfull', 18, 2, 7, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(44, 'Justify Center', 'center', 'command', 'justifycenter', 'justifycenter', 18, 2, 5, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(45, 'Justify Left', 'left', 'command', 'justifyleft', 'justifyleft', 18, 2, 6, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(46, 'Justify Right', 'right', 'command', 'justifyright', 'justifyright', 18, 2, 4, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(47, 'Remove Format', 'removeformat', 'command', 'removeformat', 'removeformat', 18, 1, 15, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(48, 'Anchor', 'anchor', 'command', 'anchor', 'anchor', 18, 2, 9, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(49, 'Format Select', 'formatselect', 'command', 'formatselect', 'formatselect', 18, 3, 9, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(50, 'Image', 'image', 'command', 'image', 'image', 18, 4, 1, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', ''),
(51, 'Link', 'link', 'command', 'link', 'link', 18, 4, 1, 1, 0, '', 1, 0, 0, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_mambots`
--

CREATE TABLE IF NOT EXISTS `jos_mambots` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `element` varchar(100) NOT NULL default '',
  `folder` varchar(100) NOT NULL default '',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(3) NOT NULL default '0',
  `iscore` tinyint(3) NOT NULL default '0',
  `client_id` tinyint(3) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_folder` (`published`,`client_id`,`access`,`folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Extraindo dados da tabela `jos_mambots`
--

INSERT INTO `jos_mambots` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
(1, 'MOS Image', 'mosimage', 'content', 0, -10000, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(2, 'MOS Pagination', 'mospaging', 'content', 0, 10000, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(3, 'Legacy Mambot Includer', 'legacybots', 'content', 0, 2, 0, 1, 0, 0, '0000-00-00 00:00:00', ''),
(4, 'SEF', 'mossef', 'content', 0, 4, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(5, 'MOS Rating', 'mosvote', 'content', 0, 5, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(6, 'Search Content', 'content.searchbot', 'search', 0, 1, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(7, 'Search Weblinks', 'weblinks.searchbot', 'search', 0, 2, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(8, 'Code support', 'moscode', 'content', 0, 3, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(9, 'No WYSIWYG Editor', 'none', 'editors', 0, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(10, 'TinyMCE WYSIWYG Editor', 'tinymce', 'editors', 0, 0, 1, 1, 0, 0, '0000-00-00 00:00:00', 'theme=advanced'),
(11, 'MOS Image Editor Button', 'mosimage.btn', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(12, 'MOS Pagebreak Editor Button', 'mospage.btn', 'editors-xtd', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(13, 'Search Contacts', 'contacts.searchbot', 'search', 0, 3, 1, 1, 0, 0, '0000-00-00 00:00:00', ''),
(14, 'Search Categories', 'categories.searchbot', 'search', 0, 4, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(15, 'Search Sections', 'sections.searchbot', 'search', 0, 5, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(16, 'Email Cloaking', 'mosemailcloak', 'content', 0, 6, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(17, 'GeSHi', 'geshi', 'content', 0, 7, 0, 0, 0, 0, '0000-00-00 00:00:00', ''),
(18, 'Search Newsfeeds', 'newsfeeds.searchbot', 'search', 0, 6, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(19, 'Load Module Positions', 'mosloadposition', 'content', 0, 8, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(24, 'RunPHP', 'RunPHP', 'content', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', ''),
(27, 'JCE Editor Mambot', 'jce', 'editors', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_menu`
--

CREATE TABLE IF NOT EXISTS `jos_menu` (
  `id` int(11) NOT NULL auto_increment,
  `menutype` varchar(25) default NULL,
  `name` varchar(100) default NULL,
  `link` text,
  `type` varchar(50) NOT NULL default '',
  `published` tinyint(1) NOT NULL default '0',
  `parent` int(11) unsigned NOT NULL default '0',
  `componentid` int(11) unsigned NOT NULL default '0',
  `sublevel` int(11) default '0',
  `ordering` int(11) default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pollid` int(11) NOT NULL default '0',
  `browserNav` tinyint(4) default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `utaccess` tinyint(3) unsigned NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `componentid` (`componentid`,`menutype`,`published`,`access`),
  KEY `menutype` (`menutype`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Extraindo dados da tabela `jos_menu`
--

INSERT INTO `jos_menu` (`id`, `menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`) VALUES
(1, 'mainmenu', 'Home', 'index.php?option=com_frontpage', 'components', 0, 0, 10, 0, 14, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nheader=Welcome to the Frontpage\npage_title=0\nback_button=0\nleading=1\nintro=0\ncolumns=0\nlink=0\norderby_pri=\norderby_sec=front\npagination=0\npagination_results=0\nimage=1\nsection=0\nsection_link=0\ncategory=0\ncategory_link=0\nitem_title=1\nlink_titles=\nreadmore=\nrating=\nauthor=0\ncreatedate=0\nmodifydate=0\npdf=0\nprint=0\nemail=0\nunpublished=0'),
(2, 'mainmenu', 'News', 'index.php?option=com_content&task=section&id=1', 'content_section', 0, 26, 1, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npage_title=1\npageclass_sfx=\nback_button=\ndescription_sec=1\ndescription_sec_image=1\norderby=\nother_cat_section=1\nempty_cat_section=0\ndescription_cat=1\ndescription_cat_image=1\nother_cat=1\nempty_cat=0\ncat_items=1\ncat_description=1\ndate_format=\ndate=\nauthor=\nhits=\nheadings=1\nnavigation=1\norder_select=1\ndisplay=1\ndisplay_num=50\nfilter=1\nfilter_type=title\nunpublished=1'),
(3, 'mainmenu', 'Contact Us', 'index.php?option=com_contact', 'components', 0, 26, 7, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=1\nheader=\ncatid=0\nother_cat_section=1\nother_cat=1\ncat_description=1\ncat_items=1\ndescription=1\ndescription_text=\nimage=-1\nimage_align=right\nheadings=1\nposition=1\nemail=0\ntelephone=1\nfax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsessionCheck=1'),
(23, 'mainmenu', 'Links', 'index.php?option=com_weblinks', 'components', 0, 26, 4, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=1\nheader=\nheadings=1\nhits=\nitem_description=1\nother_cat_section=1\nother_cat=1\ndescription=1\ndescription_text=\nimage=-1\nimage_align=right\nweblink_icons='),
(5, 'mainmenu', 'Search', 'index.php?option=com_search', 'components', 0, 26, 16, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=1\nheader='),
(6, 'mainmenu', 'Joomla! License', 'index.php?option=com_content&task=view&id=5', 'content_typed', 0, 2, 5, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(7, 'mainmenu', 'News Feeds', 'index.php?option=com_newsfeeds', 'components', 0, 2, 12, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=1\nheader=\nother_cat_section=1\nother_cat=1\ncat_description=1\ncat_items=1\ndescription=1\ndescription_text=\nimage=-1\nimage_align=right\nheadings=1\nname=1\narticles=1\nlink=0\nfeed_image=1\nfeed_descr=1\nitem_descr=1\nword_count=0'),
(8, 'mainmenu', 'Trabalhe Conosco', 'index.php?option=com_wrapper', 'wrapper', 1, 0, 0, 0, 13, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=0\nheader=\nscrolling=auto\nwidth=100%\nheight=600\nheight_auto=0\nadd=1\nurl=http://www.google.com'),
(9, 'mainmenu', 'Blog', 'index.php?option=com_content&task=blogsection&id=5', 'content_blog_section', 0, 2, 5, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\nheader=A Blog Layout of Content\npage_title=1\nleading=0\nintro=6\ncolumns=2\nlink=4\norderby_pri=order\norderby_sec=\npagination=2\npagination_results=1\nimage=0\ndescription=0\ndescription_image=0\ncategory=0\ncategory_link=0\nitem_title=1\nlink_titles=\nreadmore=\nrating=\nauthor=1\ncreatedate=1\nmodifydate=\npdf=1\nprint=1\nemail=1\nunpublished=0\nsectionid=5'),
(10, 'othermenu', 'Joomla!', 'http://www.joomla.org', 'url', -2, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1'),
(11, 'othermenu', 'RocketTheme', 'http://www.rockettheme.com', 'url', -2, 0, 0, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1'),
(12, 'othermenu', 'RocketTutorials', 'http://tutorials.rockettheme.com', 'url', -2, 0, 0, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1'),
(24, 'othermenu', 'RocketWerx', 'http://www.rocketwerx.com', 'url', -2, 0, 0, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1'),
(21, 'usermenu', 'Your Details', 'index.php?option=com_user&task=UserDetails', 'url', 1, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 1, 3, ''),
(13, 'usermenu', 'Submit News', 'index.php?option=com_content&task=new&sectionid=1&Itemid=0', 'url', 1, 0, 0, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 1, 2, ''),
(14, 'usermenu', 'Submit WebLink', 'index.php?option=com_weblinks&task=new', 'url', 1, 0, 0, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 1, 2, ''),
(15, 'usermenu', 'Check-In My Items', 'index.php?option=com_user&task=CheckIn', 'url', 1, 0, 0, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 1, 2, ''),
(16, 'usermenu', 'Logout', 'index.php?option=com_login', 'components', 1, 0, 15, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 1, 3, ''),
(17, 'topmenu', 'Home', 'index.php', 'url', 0, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=icon-1.png'),
(18, 'topmenu', 'Contact Us', 'index.php?option=com_contact&Itemid=3', 'url', 0, 0, 0, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=icon-2.png'),
(19, 'topmenu', 'About', 'index.php?option=com_content&task=section&id=1&Itemid=2', 'url', 0, 0, 0, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=icon-3.png'),
(20, 'topmenu', 'Links', 'index.php?option=com_weblinks&Itemid=23', 'url', 0, 0, 0, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1'),
(25, 'mainmenu', 'FAQs', 'index.php?option=com_content&task=category&sectionid=3&id=7', 'content_category', 0, 26, 7, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\npage_title=1\npageclass_sfx=\nback_button=\ndescription_cat=1\ndescription_cat_image=1\norderby=\ndate_format=\ndate=\nauthor=\nhits=\nheadings=1\nnavigation=1\norder_select=1\ndisplay=1\ndisplay_num=50\nfilter=1\nfilter_type=title\nother_cat=1\nempty_cat=0\ncat_items=1\ncat_description=1\nunpublished=1'),
(26, 'mainmenu', 'Contato', 'index.php?option=com_content&task=view&id=12', 'content_typed', 1, 0, 12, 0, 12, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(27, 'topmenu', 'Contact', 'index.php?option=com_contact', 'components', 0, 0, 7, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=icon-2.png\npageclass_sfx=\nback_button=\npage_title=1\nheader=\ncatid=0\nother_cat_section=1\nother_cat=1\ncat_description=1\ncat_items=1\ndescription=1\ndescription_text=\nimage=-1\nimage_align=right\nheadings=1\nposition=1\nemail=0\ntelephone=1\nfax=1\nbannedEmail=\nbannedSubject=\nbannedText=\nsessionCheck=1'),
(28, 'mainmenu', 'Serviços', 'index.php?option=com_content&task=view&id=13', 'content_typed', 1, 0, 13, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(35, 'mainmenu', 'A geRHação', 'index.php?option=com_content&task=view&id=18', 'content_typed', 1, 0, 18, 0, 3, 62, '2016-09-12 10:02:22', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(37, 'mainmenu', 'All New RokBox ', 'index.php?option=com_content&task=view&id=17', 'content_typed', -2, 35, 17, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(38, 'mainmenu', 'Clientes', 'index.php?option=com_content&task=view&id=22', 'content_typed', 1, 0, 22, 0, 6, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(39, 'mainmenu', 'Installing Catalyst', 'index.php?option=com_content&task=view&id=19', 'content_typed', 0, 38, 19, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(40, 'mainmenu', 'Custom Modules', 'index.php?option=com_content&task=view&id=20', 'content_typed', 0, 38, 20, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(41, 'mainmenu', 'Menu Options', 'index.php?option=com_content&task=view&id=23', 'content_typed', 0, 38, 23, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(42, 'mainmenu', 'Visão', 'index.php?option=com_content&task=view&id=21', 'content_typed', 1, 35, 21, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(43, 'mainmenu', 'Basic Customisation', 'index.php?option=com_content&task=view&id=24', 'content_typed', 0, 38, 24, 0, 4, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(44, 'mainmenu', 'Valores', 'index.php?option=com_content&task=view&id=25', 'content_typed', 1, 35, 25, 0, 6, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(46, 'mainmenu', 'Missão', 'index.php?option=com_content&task=view&id=23', 'content_typed', 1, 35, 23, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(48, 'mainmenu', 'Using Typography', 'index.php?option=com_content&task=view&id=28', 'content_typed', 0, 38, 28, 0, 6, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(49, 'mainmenu', 'Dummy Menu Item', '#', 'url', -2, 46, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(50, 'mainmenu', 'Dummy Menu Item', '#', 'url', -2, 46, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(51, 'mainmenu', 'Dummy Menu Item', '#', 'url', -2, 46, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(52, 'mainmenu', 'Dummy Menu Item', '#', 'url', -2, 46, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(53, 'mainmenu', 'Dummy Menu Item', '#', 'url', -2, 50, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(54, 'mainmenu', 'Dummy Menu Item', '#', 'url', -2, 50, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(55, 'mainmenu', 'Dummy Menu Item', '#', 'url', -2, 50, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(56, 'mainmenu', 'Demo Content', 'index.php?option=com_content&task=view&id=29', 'content_typed', 0, 38, 29, 0, 7, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(57, 'mainmenu', 'Module Variations', 'index.php?option=com_content&task=view&id=30', 'content_typed', 0, 35, 30, 0, 7, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(58, 'mainmenu', 'Logo Editing', 'index.php?option=com_content&task=view&id=31', 'content_typed', 0, 38, 31, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(60, 'mainmenu', 'A empresa', 'index.php?option=com_content&task=view&id=44', 'content_typed', 1, 35, 44, 0, 3, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(61, 'othermenu', 'RocketLabs', 'http://labs.rockettheme.com', 'url', -2, 0, 0, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(62, 'mainmenu', 'RT', '#', 'url', -2, 0, 0, 0, 7, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(63, 'mainmenu', 'RT', 'index.php?option=com_content&task=view&id=19', 'content_typed', -2, 0, 19, 0, 5, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(64, 'mainmenu', 'Modifying Colors', 'index.php?option=com_content&task=view&id=54', 'content_typed', -2, 35, 54, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(65, 'mainmenu', 'Preset Styles', 'index.php?option=com_content&task=view&id=21', 'content_typed', -2, 0, 21, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(66, 'mainmenu', 'Notícias', 'index.php?option=com_jce', 'components', 1, 0, 19, 0, 8, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, ''),
(67, 'mainmenu', 'Forums', 'index.php?option=com_fireboard', 'components', 0, 0, 33, 0, 9, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, ''),
(68, 'mainmenu', 'Groups', 'index.php?option=com_groupjive', 'components', 0, 0, 27, 0, 11, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, ''),
(69, 'mainmenu', 'Users List', 'index.php?option=com_comprofiler&task=usersList', 'url', -2, 66, 0, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(70, 'mainmenu', 'Community', 'index.php?option=com_comprofiler&task=usersList', 'url', 0, 0, 0, 0, 10, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(71, 'mainmenu', 'Home', 'index.php?option=com_content&task=view&id=55', 'content_typed', 1, 0, 55, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(72, 'mainmenu', 'Third Level Test', 'index.php?option=com_content&task=blogcategory&id=17', 'content_blog_category', 0, 9, 17, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\npageclass_sfx=\nback_button=\nheader=\npage_title=1\nleading=1\nintro=4\ncolumns=2\nlink=4\norderby_pri=\norderby_sec=\npagination=2\npagination_results=1\nimage=1\ndescription=0\ndescription_image=0\ncategory=0\ncategory_link=0\nitem_title=1\nlink_titles=\nreadmore=\nrating=\nauthor=\ncreatedate=\nmodifydate=\npdf=\nprint=\nemail=\nunpublished=0\ncategoryid=17'),
(73, 'mainmenu', 'Powerful Menus', 'index.php?option=com_content&task=view&id=23', 'content_typed', -2, 71, 23, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(74, 'mainmenu', '10 Preset Styles', 'index.php?option=com_content&task=view&id=21', 'content_typed', -2, 71, 21, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(75, 'mainmenu', 'Tabbed Module System', 'index.php?option=com_content&task=view&id=20', 'content_typed', -2, 71, 20, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(76, 'mainmenu', 'Tutorials and Guides', 'index.php?option=com_content&task=view&id=22', 'content_typed', -2, 71, 22, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(77, 'mainmenu', 'Stylish Typography Options', 'index.php?option=com_content&task=view&id=13', 'content_typed', -2, 71, 13, 0, 2, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(78, 'mainmenu', 'RocketTheme', 'http://www.rockettheme.com', 'url', -2, 71, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1'),
(79, 'mainmenu', 'SIFR Custom Headers', 'index.php?option=com_content&task=view&id=24', 'content_typed', -2, 71, 24, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(80, 'mainmenu', 'Stylish Typography', 'index.php?option=com_content&task=view&id=13', 'content_typed', -2, 71, 13, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(81, 'othermenu', 'Home', 'index.php?option=com_content&task=view&id=55', 'content_typed', 1, 0, 55, 0, 6, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(82, 'othermenu', 'A geRHação', 'index.php?option=com_content&task=view&id=18', 'content_typed', 1, 0, 18, 0, 7, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(83, 'othermenu', 'Serviços', 'index.php?option=com_content&task=view&id=13', 'content_typed', 1, 0, 13, 0, 8, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(84, 'othermenu', 'Clientes', 'index.php?option=com_content&task=view&id=22', 'content_typed', 1, 0, 22, 0, 9, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 'menu_image=-1\nunique_itemid=0'),
(85, 'othermenu', 'Notícias', 'index.php?option=com_jce', 'components', 1, 0, 19, 0, 10, 0, '0000-00-00 00:00:00', 0, 0, 0, 0, ''),
(86, 'othermenu', 'Trabalhe Conosco', 'index.php?option=com_wrapper', 'wrapper', 1, 0, 0, 0, 11, 0, '0000-00-00 00:00:00', 0, 0, 0, 3, 'menu_image=-1\npageclass_sfx=\nback_button=\npage_title=0\nheader=\nscrolling=auto\nwidth=100%\nheight=600\nheight_auto=0\nadd=1\nurl=http://www.google.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_messages`
--

CREATE TABLE IF NOT EXISTS `jos_messages` (
  `message_id` int(10) unsigned NOT NULL auto_increment,
  `user_id_from` int(10) unsigned NOT NULL default '0',
  `user_id_to` int(10) unsigned NOT NULL default '0',
  `folder_id` int(10) unsigned NOT NULL default '0',
  `date_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `state` int(11) NOT NULL default '0',
  `priority` int(1) unsigned NOT NULL default '0',
  `subject` varchar(230) NOT NULL default '',
  `message` text NOT NULL,
  PRIMARY KEY  (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Extraindo dados da tabela `jos_messages`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_messages_cfg`
--

CREATE TABLE IF NOT EXISTS `jos_messages_cfg` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `cfg_name` varchar(100) NOT NULL default '',
  `cfg_value` varchar(255) NOT NULL default '',
  UNIQUE KEY `idx_user_var_name` (`user_id`,`cfg_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_messages_cfg`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_modules`
--

CREATE TABLE IF NOT EXISTS `jos_modules` (
  `id` int(11) NOT NULL auto_increment,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `position` varchar(10) default NULL,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `module` varchar(50) default NULL,
  `numnews` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `showtitle` tinyint(3) unsigned NOT NULL default '1',
  `params` text NOT NULL,
  `iscore` tinyint(4) NOT NULL default '0',
  `client_id` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=309 ;

--
-- Extraindo dados da tabela `jos_modules`
--

INSERT INTO `jos_modules` (`id`, `title`, `content`, `ordering`, `position`, `checked_out`, `checked_out_time`, `published`, `module`, `numnews`, `access`, `showtitle`, `params`, `iscore`, `client_id`) VALUES
(1, 'Today''s Poll', '', 1, 'right', 0, '0000-00-00 00:00:00', 0, 'mod_poll', 0, 0, 1, 'cache=0\nmoduleclass_sfx=', 0, 0),
(2, 'User Menu', '', 1, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_mainmenu', 0, 1, 1, 'menutype=usermenu', 1, 0),
(3, 'Main Menu', '', 4, 'left', 0, '0000-00-00 00:00:00', 1, 'mod_mainmenu', 0, 0, 1, 'class_sfx=\nmoduleclass_sfx=\nmenutype=mainmenu\nmenu_style=vert_indent\nfull_active_id=0\ncache=1\nmenu_images=1\nmenu_images_align=0\nexpand_menu=0\nactivate_parent=0\nindent_image=0\nindent_image1=\nindent_image2=\nindent_image3=\nindent_image4=\nindent_image5=\nindent_image6=\nspacer=\nend_spacer=', 1, 0),
(4, 'Login Form', '', 1, 'advert3', 0, '0000-00-00 00:00:00', 0, 'mod_login', 0, 0, 1, 'moduleclass_sfx=-featured\npretext=\nposttext=\nlogin=\nlogout=\nlogin_message=0\nlogout_message=0\ngreeting=1\nname=0', 1, 0),
(5, 'Syndicate', '', 1, 'user3', 0, '0000-00-00 00:00:00', 0, 'mod_rssfeed', 0, 0, 1, 'text=\ncache=0\nmoduleclass_sfx=\nrss091=1\nrss10=1\nrss20=1\natom=1\nopml=1\nrss091_image=\nrss10_image=\nrss20_image=\natom_image=\nopml_image=', 1, 0),
(6, 'Latest News', '', 1, 'user6', 0, '0000-00-00 00:00:00', 0, 'mod_latestnews', 0, 0, 1, 'moduleclass_sfx=\ncache=1\ntype=2\nshow_front=0\ncount=8\ncatid=\nsecid=', 1, 0),
(8, 'Who''s Online', '', 4, 'user7', 0, '0000-00-00 00:00:00', 1, 'mod_whosonline', 0, 0, 0, 'showmode=2\nmoduleclass_sfx=', 0, 0),
(9, 'Popular', '', 1, 'user7', 0, '0000-00-00 00:00:00', 0, 'mod_mostread', 0, 0, 1, 'moduleclass_sfx=\ncache=0\ntype=2\nshow_front=1\ncount=8\ncatid=\nsecid=', 0, 0),
(10, 'Template Chooser', '', 8, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_templatechooser', 0, 0, 1, 'show_preview=1', 0, 0),
(11, 'Archive', '', 9, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_archive', 0, 0, 1, 'count=10\ncache=0\nmoduleclass_sfx=', 1, 0),
(12, 'Sections', '', 10, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_sections', 0, 0, 1, 'count=5\nmoduleclass_sfx=', 1, 0),
(13, 'Newsflash', '', 2, 'top', 0, '0000-00-00 00:00:00', 0, 'mod_newsflash', 0, 0, 0, 'catid=17\nstyle=random\nimage=0\nlink_titles=0\nreadmore=0\nitem_title=0\nitems=\ncache=0\nmoduleclass_sfx=', 0, 0),
(14, 'Related Items', '', 5, 'user7', 0, '0000-00-00 00:00:00', 0, 'mod_related_items', 0, 0, 1, 'cache=1\nmoduleclass_sfx=', 0, 0),
(15, 'Search', '', 1, 'search', 0, '0000-00-00 00:00:00', 1, 'mod_search', 0, 0, 0, 'moduleclass_sfx=\ncache=1\nset_itemid=\nwidth=20\ntext=\nbutton=\nbutton_pos=right\nbutton_text=', 0, 0),
(16, 'Random Image', '', 3, 'right', 0, '0000-00-00 00:00:00', 0, 'mod_random_image', 0, 0, 1, '', 0, 0),
(17, 'Top Menu', '', 1, 'bottom', 0, '0000-00-00 00:00:00', 0, 'mod_mainmenu', 0, 0, 0, 'class_sfx=\nmoduleclass_sfx=\nmenutype=topmenu\nmenu_style=horiz_flat\nfull_active_id=0\ncache=0\nmenu_images=0\nmenu_images_align=0\nexpand_menu=0\nactivate_parent=0\nindent_image=0\nindent_image1=\nindent_image2=\nindent_image3=\nindent_image4=\nindent_image5=\nindent_image6=\nspacer=|\nend_spacer=', 1, 0),
(18, 'Banners', '', 1, 'banner', 0, '0000-00-00 00:00:00', 0, 'mod_banners', 0, 0, 0, 'banner_cids=\nmoduleclass_sfx=', 1, 0),
(19, 'Components', '', 2, 'cpanel', 0, '0000-00-00 00:00:00', 0, 'mod_components', 0, 99, 1, '', 1, 1),
(20, 'Popular', '', 3, 'cpanel', 0, '0000-00-00 00:00:00', 0, 'mod_popular', 0, 99, 1, '', 0, 1),
(21, 'Latest Items', '', 4, 'cpanel', 0, '0000-00-00 00:00:00', 0, 'mod_latest', 0, 99, 1, '', 0, 1),
(22, 'Menu Stats', '', 5, 'cpanel', 0, '0000-00-00 00:00:00', 0, 'mod_stats', 0, 99, 1, '', 0, 1),
(23, 'Unread Messages', '', 1, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_unread', 0, 99, 1, '', 1, 1),
(24, 'Online Users', '', 3, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_online', 0, 99, 1, '', 1, 1),
(25, 'Full Menu', '', 1, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_fullmenu', 0, 99, 1, '', 1, 1),
(26, 'Pathway', '', 1, 'pathway', 0, '0000-00-00 00:00:00', 1, 'mod_pathway', 0, 99, 1, '', 1, 1),
(27, 'Toolbar', '', 1, 'toolbar', 0, '0000-00-00 00:00:00', 1, 'mod_toolbar', 0, 99, 1, '', 1, 1),
(28, 'System Message', '', 1, 'inset', 0, '0000-00-00 00:00:00', 1, 'mod_mosmsg', 0, 99, 1, '', 1, 1),
(29, 'Quick Icons', '', 1, 'icon', 0, '0000-00-00 00:00:00', 1, 'mod_quickicon', 0, 99, 1, '', 1, 1),
(30, 'Other Menu', '', 3, 'bottom', 0, '0000-00-00 00:00:00', 1, 'mod_mainmenu', 0, 0, 0, 'class_sfx=\nmoduleclass_sfx=\nmenutype=othermenu\nmenu_style=horiz_flat\nfull_active_id=0\ncache=0\nmenu_images=0\nmenu_images_align=0\nexpand_menu=0\nactivate_parent=0\nindent_image=0\nindent_image1=\nindent_image2=\nindent_image3=\nindent_image4=\nindent_image5=\nindent_image6=\nspacer=\nend_spacer=', 0, 0),
(31, 'Wrapper', '', 11, 'left', 0, '0000-00-00 00:00:00', 0, 'mod_wrapper', 0, 0, 1, 'moduleclass_sfx=\nurl=\nscrolling=auto\nwidth=100%\nheight=200\nheight_auto=1\nadd=1', 0, 0),
(32, 'Logged', '', 0, 'cpanel', 0, '0000-00-00 00:00:00', 1, 'mod_logged', 0, 99, 1, '', 0, 1),
(66, 'Preview', '', 2, 'header', 0, '0000-00-00 00:00:00', 1, 'mod_preview', 0, 99, 1, '', 0, 1),
(301, 'User 10 Module', '<h2>Integrated RokSlide Tabbed Modules</h2>\r\nYou will observe that the modular positions, User8 through User 12 are contained within what appears to be RokSlide. In Catalyst, the RokSlide effect/functionality has been integrated directly into the template, providing a sleek stylish approach for extra modules, allowing you to created tabbed modules and tabbed module groups.', 1, 'user10', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=1\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(134, 'RocketLauncher', '<div class="module-style1" style="float: left; width: 45%; margin-right: 10px;"><div><div><div>\r\n	<h3 style="visibility: visible;">Upload the files</h3>\r\n		Download the separate <b>catalyst</b> RocketLauncher package from the <b>catalyst</b> template downloads section and upload the Joomla files within to your server as you would a standard Joomla installation package. You upload the files with a FTP client such as <a href="http://filezilla.sourceforge.net/" title="filezilla">Filezilla</a>. <a href="http://www.rockettheme.com/option,com_smf/Itemid,190/topic,20046.0/">Want to learn more...?</a>\r\n\r\n</div></div></div></div>\r\n\r\n<div class="module-style1" style="float: left; width: 45%;"><div><div><div>\r\n<h3 style="visibility: visible;"><span>Run</span> the Installer</h3>\r\nPoint your browser to the location of the <b>RocketLauncher</b> Joomla\r\ninstallation package on your server (whichever domain/folder you\r\nuploaded to). You will then see a <b>RocketLauncher</b> splash screen. Click\r\nBegin Install and follow the on screen Joomla installation instructions.\r\n\r\n</div></div></div></div>\r\n', 2, 'user3', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=-\ncache=1\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(214, 'Users Review', '<h2>See what ours users have to say</h2>\r\nWe believe we offer the best quality, professional grade templates available for Joomla. Club members in the RTTC also get exclusive access to a forum that provides a great resource when looking for help with installing or customizing your RocketTheme Joomla templates. ', 1, 'user9', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=1\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(244, 'WYSIWYG Editor Tip', 'When placing custom html code into a custom module or content item, it is best to turn your WYSIWYG editor off.   \r\nDoing so will ensure the code you place will not get stripped away when the item is saved. <p>Turn your editor off in your admin your panel by going to Site > Global Configuration and setting Default WYSIWYG Editor to No WYSIWYG Editor.</p>\r\n', 2, 'left', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=-note\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(201, 'RocketTutorials', 'Dive deeper into the world of Joomla with informative and detailed written tutorials on a variety of topics including general Joomla functions, RocketTheme template modifications, and much more on our all new <a href="http://tutorials.rockettheme.com">RocketTutorials</a> site.', 3, 'left', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=-hilite2\ncache=1\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(227, 'Module User1', '<div class="module-hilite1"><div><div><div>\r\n\r\n<h3>User1 -hilite1</h3>\r\n\r\nThis is the <b>User1</b> module position, which is using the <b>-hilite1</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>\r\n\r\n<div class="clr"></div>\r\n\r\n<div class="module-hilite3"><div><div><div>\r\n\r\n<h3>User1 -hilite3</h3>\r\n\r\nThis is the <b>User1</b> module position, which is using the <b>-hilite3</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>', 1, 'user1', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=-\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(228, 'Module User2', '<div class="module-hilite2"><div><div><div>\r\n\r\n<h3>User2 -hilite2</h3>\r\n\r\nThis is the <b>User2</b> module position, which is using the <b>-hilite2</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>\r\n\r\n<div class="module-hilite4"><div><div><div>\r\n\r\n<h3>User2 -hilite4</h3>\r\n\r\nThis is the <b>User2</b> module position, which is using the <b>-hilite4</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>', 1, 'user2', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=-\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(230, 'Module User4', 'This is the <b>User4</b> module position, which does not have module hilites.<br/><br/>You can publish multiple modules to this module position and can have 4 modules wide in this bottom bar.		', 1, 'user4', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(231, 'Module User5', 'This is the <b>User5</b> module position, which does not have module hilites.<br/><br/>You can publish multiple modules to this module position and can have 4 modules wide in this bottom bar.		', 1, 'user5', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(286, 'GERHAÇÃO', 'PRESTA&Ccedil;&Atilde;O DE SERVI&Ccedil;OS TERCEIRIZADOS <br />\r\nNAS &Agrave;REAS&nbsp; DE LIMPEZA,CONSERVA&Ccedil;&Atilde;O,<br />\r\nPORTARIA, RECEP&Ccedil;&Atilde;O&nbsp; E <br />\r\nM&Atilde;O DE OBRA LOCADA \r\n', 6, 'left', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=-hilite3\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(232, 'Module User6', 'This is the <b>User6</b> module position, which does not have module hilites.<br/><br/>You can publish multiple modules to this module position and can have 4 modules wide in this bottom bar.		', 3, 'user6', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(271, 'Module Advert1', '<div class="featured-1 png">\r\n</div>\r\n <span class="featured-header">Servi&ccedil;os</span><span class="featured-desc">Veja os servi&ccedil;os que n&oacute;s prestamos!<br />\r\n</span>\r\n', 1, 'advert1', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(235, 'Module Right', '<div class="module-hilite1"><div><div><div>\r\n\r\n<h3>Right -hilite1</h3>\r\n\r\nThis is the <b>Right</b> module position, which is using the <b>-hilite1</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>\r\n\r\n<div class="module-hilite2"><div><div><div>\r\n\r\n<h3>Right -hilite2</h3>\r\n\r\nThis is the <b>Right</b> module position, which is using the <b>-hilite2</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>\r\n\r\n<div class="module-hilite3"><div><div><div>\r\n\r\n<h3>Right -hilite3</h3>\r\n\r\nThis is the <b>Right</b> module position, which is using the <b>-hilite3</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>\r\n\r\n\r\n<div class="module-hilite4"><div><div><div>\r\n\r\n<h3>Right -hilite4</h3>\r\n\r\nThis is the <b>Right</b> module position, which is using the <b>-hilite4</b> module class suffix.\r\n\r\n\r\n</div></div></div></div>', 2, 'right', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=-\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(302, 'Module User7', 'This is the <b>User7</b> module position, which does not have module hilites.<br/><br/>You can publish multiple modules to this module position and can have 4 modules wide in this bottom bar.		', 2, 'user7', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(281, 'Module Left', 'This is the <b>Left</b> module position, which has the ability to have all 4 hilites, those being <b>-hilite1 : -hilite2 : -hilite3 &amp; -hilite4</b>.\r\n', 7, 'left', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(304, 'Advert1 Position', 'This is the <b>Advert1</b> module position, which does not have module hilites.\r\n', 2, 'advert1', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(305, 'Advert2 Position', 'This is the <b>Advert2</b> module position, which does not have module hilites.\r\n', 1, 'advert2', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(274, '-hilite2 Module', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 3, 'user2', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=-hilite2\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(239, 'Rich Colorful Styles', '<h2>Rich, Colorful Styles</h2>\r\nCatalyst features 6 independent body color styles and 10 header / footer texture styles allowing you to mix and match for the perfect style. Catalyst also includes 6 preset configurations for you to start with. <a href="index.php?option=com_content&amp;task=view&amp;id=21&amp;Itemid=42" class="readon">Learn more...</a>\r\n', 1, 'user8', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=1\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(279, 'Mais Informações', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas tortor. Nunc pulvinar, massa vitae luctus posuere, quam urna bibendum augue, quis venenatis mi est eu libero.\r\n', 2, 'user4', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(263, 'Notícias', 'Not&iacute;cias do Setor RH. Apresentamos as principais not&iacute;cias do setor de Gest&atilde;o em RH. <br />\r\n', 3, 'user1', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(264, 'Mais Informações', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas tortor. Nunc pulvinar, massa vitae luctus posuere, quam urna bibendum augue, quis venenatis mi est eu libero.\r\n', 2, 'user6', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(270, 'Footer', 'Copyright &copy;2008 RocketTheme', 2, 'footer', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(280, 'Mais Informações', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas tortor. Nunc pulvinar, massa vitae luctus posuere, quam urna bibendum augue, quis venenatis mi est eu libero.\r\n', 2, 'user5', 62, '2009-01-12 15:17:57', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(282, 'Module Advert2', '<div class="featured-2 png">\r\n</div>\r\n<span class="featured-header">Quem Somos</span><span class="featured-desc">Apresenta&ccedil;ao da empresa, seus valores, vis&atilde;o e miss&atilde;o. <br />\r\n</span>\r\n', 2, 'advert2', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(284, 'Clientes', '<p>\r\n<img src="images/stories/provinil.png" style="width: 159px; height: 40px" alt="provinil.png" title="provinil.png" width="159" height="40" />\r\n</p>\r\n<p>\r\n<a href="index.php?fontfamily=palatino"><img src="images/stories/tecno.png" alt="tecno.png" title="tecno.png" width="155" height="57" /></a>\r\n</p>\r\n<p>\r\n<img src="images/stories/bunge.png" alt="bunge.png" title="bunge.png" width="151" height="41" />\r\n</p>\r\n<p>\r\n<a href="index.php?fontfamily=palatino"><img src="images/stories/sociesc.png" style="width: 121px; height: 111px" alt="sociesc.png" title="sociesc.png" width="121" height="111" /></a>\r\n</p>\r\n<p>\r\n&nbsp;\r\n</p>\r\n<p>\r\n<a href="index.php?fontfamily=palatino"><img src="images/stories/bematech.png" alt="bematech.png" title="bematech.png" width="136" height="37" /></a>\r\n</p>\r\n', 5, 'left', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=-note\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(290, 'Nossos Serviços', '', 1, 'inset', 0, '0000-00-00 00:00:00', 1, 'mod_rokcontentrotator', 0, 0, 0, 'moduleclass_sfx=\nshow_title=1\ncache=0\ntype=1\nshow_front=1\ncount=5\ncatid=18\nsecid=0\nclick_title=0\njslib=0\nshow_readmore=0\nreadmore=Leia Mais\nduration=800\npreview_count=400', 0, 0),
(291, 'Login Form', '', 1, 'top', 0, '0000-00-00 00:00:00', 1, 'mod_signallogin', 0, 0, 0, 'moduleclass_sfx=\nmodule_theme=none\nhorizontal=1\npretext=\nposttext=\nlogin=\nlogout=index.php\nshow_lostpass=1\nshow_newaccount=0\nname_lenght=10\npass_lenght=10\nlogin_message=0\nlogout_message=0\nremember_enabled=0\ngreeting=1\nname=0', 0, 0),
(292, 'Module Advert3', '<div class="featured-3 png">\r\n</div>\r\n<span class="featured-header">Equipamentos</span><span class="featured-desc">Veja os equipamentos que usamos em nossos servi&ccedil;os.<br />\r\n</span>\r\n', 2, 'advert3', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(294, 'Module Advert4', '<div class="featured-4 png">\r\n</div>\r\n<span class="featured-header">Entre em Contato</span><span class="featured-desc">Contate-nos para or&ccedil;amentos. </span>\r\n', 1, 'advert4', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(295, 'Header Promo', '<img src="templates/rt_catalyst/images/promo.png" alt="promo" class="png" id="promo" />', 1, 'header', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 0, 'moduleclass_sfx=\ncache=1\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(296, 'Mais Informações', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas tortor. Nunc pulvinar, massa vitae luctus posuere, quam urna bibendum augue, quis venenatis mi est eu libero.\r\n', 3, 'user7', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(297, 'Products', '<ul class="bullet-2">\r\n<li>Book About our Products</li>\r\n<li>DVD About our Products</li>\r\n<li>Product Guide DVD</li>\r\n<li>DVD Product Guide Book</li>\r\n</ul>', 3, 'user3', 0, '0000-00-00 00:00:00', 0, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(298, '-hilite1 Module', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 2, 'user1', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=-hilite1\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(299, 'Trabalhe Conosco', 'Est&aacute; procurando por trabalho. Preencha nosso formul&aacute;rio de aplica&ccedil;&atilde;o e comece logo a trabalhar.\r\n', 2, 'user2', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(300, '-hilite3 Module', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 4, 'user3', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=-hilite3\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(306, 'Advert3 Position', 'This is the <b>Advert3</b> module position, which does not have module hilites.\r\n', 3, 'advert3', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(307, 'Advert4 Position', 'This is the <b>Advert4</b> module position, which does not have module hilites.\r\n', 2, 'advert4', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=1\nrssdesc=1\nrssimage=1\nrssitems=3\nrssitemdesc=1\nword_count=0\nrsscache=3600', 0, 0),
(308, 'Top Modules Animation', '<script type="text/javascript">\r\nwindow.addEvent(''domready'', function() {\r\n	\r\n	var fx_duration = 50;\r\n	var fx_margin = 3; // px\r\n	\r\n	var blocks = $$(''#featuredmodules .block'');\r\n	if (blocks.length) {\r\n		blocks.setStyle(''cursor'', ''pointer'').each(function(block) {\r\n			var fx = new Fx.Style(block, ''margin-top'',{\r\n				duration: fx_duration,\r\n				transition: Fx.Transitions.Quad.easeInOut,\r\n				wait: false\r\n			}).set(0);\r\n			block.addEvents({\r\n				''mouseenter'': function() { \r\n					fx.start(fx_margin).chain(function() {\r\n						this.start(-fx_margin);\r\n					}).chain(function() {\r\n						this.start(0);\r\n					});\r\n				}\r\n			});\r\n		});\r\n	};\r\n});\r\n</script>', 1, 'debug', 0, '0000-00-00 00:00:00', 1, '', 0, 0, 1, 'moduleclass_sfx=\ncache=0\nfirebots=1\nrssurl=\nrsstitle=0\nrssdesc=0\nrssimage=0\nrssitems=3\nrssitemdesc=0\nword_count=0\nrsscache=3600', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_modules_menu`
--

CREATE TABLE IF NOT EXISTS `jos_modules_menu` (
  `moduleid` int(11) NOT NULL default '0',
  `menuid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`moduleid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_modules_menu`
--

INSERT INTO `jos_modules_menu` (`moduleid`, `menuid`) VALUES
(1, 71),
(2, 0),
(3, 2),
(3, 3),
(3, 5),
(3, 6),
(3, 7),
(3, 9),
(3, 23),
(3, 25),
(3, 26),
(3, 38),
(3, 39),
(3, 40),
(3, 41),
(3, 43),
(3, 48),
(3, 56),
(3, 58),
(3, 72),
(4, 71),
(5, 2),
(5, 5),
(5, 6),
(5, 7),
(5, 23),
(5, 25),
(6, 2),
(6, 3),
(6, 5),
(6, 6),
(6, 7),
(6, 9),
(6, 23),
(6, 25),
(6, 26),
(8, 71),
(9, 2),
(9, 3),
(9, 5),
(9, 6),
(9, 7),
(9, 9),
(9, 23),
(9, 25),
(9, 26),
(10, 1),
(11, 71),
(12, 71),
(13, 0),
(14, 2),
(14, 3),
(14, 5),
(14, 6),
(14, 7),
(14, 9),
(14, 23),
(14, 25),
(14, 26),
(15, 57),
(17, 0),
(18, 0),
(30, 0),
(31, 71),
(66, 0),
(134, 60),
(201, 35),
(201, 37),
(201, 42),
(201, 44),
(201, 46),
(201, 60),
(214, 35),
(227, 57),
(228, 57),
(230, 57),
(231, 57),
(232, 57),
(235, 57),
(239, 35),
(244, 35),
(244, 37),
(244, 42),
(244, 44),
(244, 46),
(244, 60),
(263, 71),
(264, 71),
(270, 0),
(271, 2),
(271, 3),
(271, 5),
(271, 6),
(271, 7),
(271, 9),
(271, 23),
(271, 25),
(271, 26),
(271, 28),
(271, 35),
(271, 38),
(271, 39),
(271, 40),
(271, 41),
(271, 42),
(271, 43),
(271, 44),
(271, 46),
(271, 48),
(271, 49),
(271, 50),
(271, 51),
(271, 52),
(271, 53),
(271, 54),
(271, 55),
(271, 56),
(271, 58),
(271, 60),
(271, 71),
(271, 72),
(274, 2),
(274, 3),
(274, 5),
(274, 6),
(274, 7),
(274, 9),
(274, 23),
(274, 25),
(274, 26),
(274, 72),
(279, 71),
(280, 71),
(281, 57),
(282, 2),
(282, 3),
(282, 5),
(282, 6),
(282, 7),
(282, 9),
(282, 23),
(282, 25),
(282, 26),
(282, 28),
(282, 35),
(282, 38),
(282, 39),
(282, 40),
(282, 41),
(282, 42),
(282, 43),
(282, 44),
(282, 46),
(282, 48),
(282, 49),
(282, 50),
(282, 51),
(282, 52),
(282, 53),
(282, 54),
(282, 55),
(282, 56),
(282, 58),
(282, 60),
(282, 71),
(282, 72),
(284, 71),
(286, 71),
(290, 71),
(291, 0),
(292, 2),
(292, 3),
(292, 5),
(292, 6),
(292, 7),
(292, 9),
(292, 23),
(292, 25),
(292, 26),
(292, 28),
(292, 35),
(292, 38),
(292, 39),
(292, 40),
(292, 41),
(292, 42),
(292, 43),
(292, 44),
(292, 46),
(292, 48),
(292, 49),
(292, 50),
(292, 51),
(292, 52),
(292, 53),
(292, 54),
(292, 55),
(292, 56),
(292, 58),
(292, 60),
(292, 71),
(292, 72),
(294, 2),
(294, 3),
(294, 5),
(294, 6),
(294, 7),
(294, 9),
(294, 23),
(294, 25),
(294, 26),
(294, 28),
(294, 35),
(294, 38),
(294, 39),
(294, 40),
(294, 41),
(294, 42),
(294, 43),
(294, 44),
(294, 46),
(294, 48),
(294, 49),
(294, 50),
(294, 51),
(294, 52),
(294, 53),
(294, 54),
(294, 55),
(294, 56),
(294, 58),
(294, 60),
(294, 71),
(294, 72),
(295, 2),
(295, 3),
(295, 5),
(295, 6),
(295, 7),
(295, 9),
(295, 23),
(295, 25),
(295, 26),
(295, 28),
(295, 35),
(295, 37),
(295, 38),
(295, 39),
(295, 40),
(295, 41),
(295, 42),
(295, 43),
(295, 44),
(295, 46),
(295, 48),
(295, 49),
(295, 50),
(295, 51),
(295, 52),
(295, 53),
(295, 54),
(295, 55),
(295, 56),
(295, 57),
(295, 58),
(295, 60),
(295, 71),
(295, 72),
(296, 71),
(297, 71),
(298, 2),
(298, 3),
(298, 5),
(298, 6),
(298, 7),
(298, 9),
(298, 23),
(298, 25),
(298, 26),
(298, 72),
(299, 71),
(300, 2),
(300, 3),
(300, 5),
(300, 6),
(300, 7),
(300, 9),
(300, 23),
(300, 25),
(300, 26),
(300, 72),
(301, 35),
(302, 57),
(304, 57),
(305, 57),
(306, 57),
(307, 57),
(308, 2),
(308, 3),
(308, 5),
(308, 6),
(308, 7),
(308, 9),
(308, 23),
(308, 25),
(308, 26),
(308, 28),
(308, 35),
(308, 37),
(308, 38),
(308, 39),
(308, 40),
(308, 41),
(308, 42),
(308, 43),
(308, 44),
(308, 46),
(308, 48),
(308, 49),
(308, 50),
(308, 51),
(308, 52),
(308, 53),
(308, 54),
(308, 55),
(308, 56),
(308, 57),
(308, 58),
(308, 60),
(308, 71),
(308, 72);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_newsfeeds`
--

CREATE TABLE IF NOT EXISTS `jos_newsfeeds` (
  `catid` int(11) NOT NULL default '0',
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `link` text NOT NULL,
  `filename` varchar(200) default NULL,
  `published` tinyint(1) NOT NULL default '0',
  `numarticles` int(11) unsigned NOT NULL default '1',
  `cache_time` int(11) unsigned NOT NULL default '3600',
  `checked_out` tinyint(3) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `published` (`published`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `jos_newsfeeds`
--

INSERT INTO `jos_newsfeeds` (`catid`, `id`, `name`, `link`, `filename`, `published`, `numarticles`, `cache_time`, `checked_out`, `checked_out_time`, `ordering`) VALUES
(4, 1, 'Joomla! - Official News', 'http://www.joomla.org/index.php?option=com_rss_xtd&feed=RSS2.0&type=com_frontpage&Itemid=1', '', 1, 5, 3600, 0, '0000-00-00 00:00:00', 8),
(4, 2, 'Joomla! - Community News', 'http://www.joomla.org/index.php?option=com_rss_xtd&feed=RSS2.0&type=com_content&task=blogcategory&id=0&Itemid=33', '', 1, 5, 3600, 0, '0000-00-00 00:00:00', 9),
(10, 4, 'Linux Today', 'http://linuxtoday.com/backend/my-netscape.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 1),
(5, 5, 'Business News', 'http://headlines.internet.com/internetnews/bus-news/news.rss', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 2),
(11, 6, 'Web Developer News', 'http://headlines.internet.com/internetnews/wd-news/news.rss', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 3),
(10, 7, 'Linux Central:New Products', 'http://linuxcentral.com/backend/lcnew.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 4),
(10, 8, 'Linux Central:Best Selling', 'http://linuxcentral.com/backend/lcbestns.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 5),
(10, 9, 'Linux Central:Daily Specials', 'http://linuxcentral.com/backend/lcspecialns.rdf', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 6),
(9, 10, 'Internet:Finance News', 'http://headlines.internet.com/internetnews/fina-news/news.rss', '', 1, 3, 3600, 0, '0000-00-00 00:00:00', 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_polls`
--

CREATE TABLE IF NOT EXISTS `jos_polls` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `voters` int(9) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `access` int(11) NOT NULL default '0',
  `lag` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `jos_polls`
--

INSERT INTO `jos_polls` (`id`, `title`, `voters`, `checked_out`, `checked_out_time`, `published`, `access`, `lag`) VALUES
(14, 'How many ''cores'' does your computer have?', 7, 0, '0000-00-00 00:00:00', 1, 0, 86400);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_poll_data`
--

CREATE TABLE IF NOT EXISTS `jos_poll_data` (
  `id` int(11) NOT NULL auto_increment,
  `pollid` int(4) NOT NULL default '0',
  `text` text NOT NULL,
  `hits` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`pollid`,`text`(1))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Extraindo dados da tabela `jos_poll_data`
--

INSERT INTO `jos_poll_data` (`id`, `pollid`, `text`, `hits`) VALUES
(1, 14, 'Single core', 2),
(2, 14, 'Dual core', 2),
(3, 14, 'Dual-dual core (4 cores)', 2),
(4, 14, 'Dual-quad core (8 cores)', 1),
(5, 14, 'What the heck is a core?', 0),
(6, 14, '', 0),
(7, 14, '', 0),
(8, 14, '', 0),
(9, 14, '', 0),
(10, 14, '', 0),
(11, 14, '', 0),
(12, 14, '', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_poll_date`
--

CREATE TABLE IF NOT EXISTS `jos_poll_date` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `vote_id` int(11) NOT NULL default '0',
  `poll_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `jos_poll_date`
--

INSERT INTO `jos_poll_date` (`id`, `date`, `vote_id`, `poll_id`) VALUES
(1, '2008-02-29 22:19:00', 4, 14);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_poll_menu`
--

CREATE TABLE IF NOT EXISTS `jos_poll_menu` (
  `pollid` int(11) NOT NULL default '0',
  `menuid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pollid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_poll_menu`
--

INSERT INTO `jos_poll_menu` (`pollid`, `menuid`) VALUES
(14, 71);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_sections`
--

CREATE TABLE IF NOT EXISTS `jos_sections` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `image` varchar(100) NOT NULL default '',
  `scope` varchar(50) NOT NULL default '',
  `image_position` varchar(10) NOT NULL default '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_scope` (`scope`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `jos_sections`
--

INSERT INTO `jos_sections` (`id`, `title`, `name`, `image`, `scope`, `image_position`, `description`, `published`, `checked_out`, `checked_out_time`, `ordering`, `access`, `count`, `params`) VALUES
(1, 'News', 'The News', 'articles.jpg', 'content', 'right', 'Select a news topic from the list below, then select a news article to read.', 1, 0, '0000-00-00 00:00:00', 3, 0, 3, ''),
(2, 'Newsflashes', 'Newsflashes', '', 'content', 'left', '', 1, 0, '0000-00-00 00:00:00', 4, 0, 3, ''),
(3, 'FAQs', 'Frequently Asked Questions', 'pastarchives.jpg', 'content', 'left', 'From the list below choose one of our FAQs topics, then select an FAQ to read. If you have a question which is not in this section, please contact us.', 1, 0, '0000-00-00 00:00:00', 5, 0, 1, ''),
(4, 'Latest', 'Latest', '', 'content', 'left', '', 1, 0, '0000-00-00 00:00:00', 2, 0, 1, 'imagefolders=*1*'),
(5, 'Blog Demo', 'Blog Demo', '', 'content', 'left', '', 1, 0, '0000-00-00 00:00:00', 1, 0, 1, 'imagefolders=*1*');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_session`
--

CREATE TABLE IF NOT EXISTS `jos_session` (
  `username` varchar(50) default '',
  `time` varchar(14) default '',
  `session_id` varchar(200) NOT NULL default '0',
  `guest` tinyint(4) default '1',
  `userid` int(11) default '0',
  `usertype` varchar(50) default '',
  `gid` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`session_id`),
  KEY `whosonline` (`guest`,`usertype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_session`
--

INSERT INTO `jos_session` (`username`, `time`, `session_id`, `guest`, `userid`, `usertype`, `gid`) VALUES
('', '1473685724', '1cd67b5c00653897febe5c6530815dce', 1, 0, '', 0),
('admin', '1473685715', '078a0252d2cd3ddcd6b66e3c34a45c34', 1, 62, 'Super Administrator', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_stats_agents`
--

CREATE TABLE IF NOT EXISTS `jos_stats_agents` (
  `agent` varchar(255) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `hits` int(11) unsigned NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_stats_agents`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_templates_menu`
--

CREATE TABLE IF NOT EXISTS `jos_templates_menu` (
  `template` varchar(50) NOT NULL default '',
  `menuid` int(11) NOT NULL default '0',
  `client_id` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`template`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_templates_menu`
--

INSERT INTO `jos_templates_menu` (`template`, `menuid`, `client_id`) VALUES
('joomla_admin', 0, 1),
('gerhacao', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_template_positions`
--

CREATE TABLE IF NOT EXISTS `jos_template_positions` (
  `id` int(11) NOT NULL auto_increment,
  `position` varchar(10) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Extraindo dados da tabela `jos_template_positions`
--

INSERT INTO `jos_template_positions` (`id`, `position`, `description`) VALUES
(1, 'left', ''),
(2, 'right', ''),
(3, 'top', ''),
(4, 'bottom', ''),
(5, 'inset', ''),
(6, 'banner', ''),
(7, 'header', ''),
(8, 'footer', ''),
(9, 'newsflash', ''),
(10, 'legals', ''),
(11, 'pathway', ''),
(12, 'toolbar', ''),
(13, 'cpanel', ''),
(14, 'user1', ''),
(15, 'user2', ''),
(16, 'user3', ''),
(17, 'user4', ''),
(18, 'user5', ''),
(19, 'user6', ''),
(20, 'user7', ''),
(21, 'user8', ''),
(22, 'user9', ''),
(23, 'advert1', ''),
(24, 'advert2', ''),
(25, 'advert3', ''),
(26, 'icon', ''),
(27, 'debug', ''),
(28, 'user10', ''),
(29, 'user11', ''),
(30, 'search', ''),
(31, 'user12', ''),
(32, 'user13', ''),
(33, 'user14', ''),
(34, 'user15', ''),
(35, 'user16', ''),
(36, 'user17', ''),
(37, 'rotator', ''),
(38, 'advert4', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_users`
--

CREATE TABLE IF NOT EXISTS `jos_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `username` varchar(25) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `usertype` varchar(25) NOT NULL default '',
  `block` tinyint(4) NOT NULL default '0',
  `sendEmail` tinyint(4) default '0',
  `gid` tinyint(3) unsigned NOT NULL default '1',
  `registerDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL default '',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `usertype` (`usertype`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Extraindo dados da tabela `jos_users`
--

INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `gid`, `registerDate`, `lastvisitDate`, `activation`, `params`) VALUES
(62, 'Administrator', 'admin', 'leo.lima.web@gmail.com', 'cdecfe4937db6680dd5fb6c0dad73315:xvHo5XfUkKGW0SSr', 'Super Administrator', 0, 1, 25, '2008-12-17 11:52:07', '0000-00-00 00:00:00', '', 'expired=\nexpired_time=');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_usertypes`
--

CREATE TABLE IF NOT EXISTS `jos_usertypes` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `mask` varchar(11) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `jos_usertypes`
--

INSERT INTO `jos_usertypes` (`id`, `name`, `mask`) VALUES
(0, 'superadministrator', ''),
(1, 'administrator', ''),
(2, 'editor', ''),
(3, 'user', ''),
(4, 'author', ''),
(5, 'publisher', ''),
(6, 'manager', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `jos_weblinks`
--

CREATE TABLE IF NOT EXISTS `jos_weblinks` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `sid` int(11) NOT NULL default '0',
  `title` varchar(250) NOT NULL default '',
  `url` varchar(250) NOT NULL default '',
  `description` varchar(250) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `archived` tinyint(1) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '1',
  `params` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`,`published`,`archived`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `jos_weblinks`
--

INSERT INTO `jos_weblinks` (`id`, `catid`, `sid`, `title`, `url`, `description`, `date`, `hits`, `published`, `checked_out`, `checked_out_time`, `ordering`, `archived`, `approved`, `params`) VALUES
(1, 2, 0, 'Joomla!', 'http://www.joomla.org', 'Home of Joomla!', '2005-02-14 15:19:02', 2, 1, 0, '0000-00-00 00:00:00', 1, 0, 1, 'target=0'),
(2, 2, 0, 'php.net', 'http://www.php.net', 'The language that Joomla! is developed in', '2004-07-07 11:33:24', 0, 1, 0, '0000-00-00 00:00:00', 3, 0, 1, ''),
(3, 2, 0, 'MySQL', 'http://www.mysql.com', 'The database that Joomla! uses', '2004-07-07 10:18:31', 0, 1, 0, '0000-00-00 00:00:00', 5, 0, 1, ''),
(4, 2, 0, 'OpenSourceMatters', 'http://www.opensourcematters.org', 'Home of OSM', '2005-02-14 15:19:02', 2, 1, 0, '0000-00-00 00:00:00', 1, 0, 1, 'target=0'),
(5, 2, 0, 'Joomla! - Forums', 'http://forum.joomla.org', 'Joomla! Forums', '2005-02-14 15:19:02', 2, 1, 0, '0000-00-00 00:00:00', 1, 0, 1, 'target=0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
