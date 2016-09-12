--
-- Table structure for table `#__jce_plugins`
--

DROP TABLE IF EXISTS `#__jce_plugins`;
CREATE TABLE `#__jce_plugins` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL default 'plugin',
  `icon` varchar(255) NOT NULL default '',
  `layout` varchar(255) NOT NULL,
  `row` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `published` tinyint(3) NOT NULL default '0',
  `editable` tinyint(3) NOT NULL default '0',
  `elements` varchar(255) NOT NULL default '',
  `params` text NOT NULL,
  `iscore` tinyint(3) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `plugin` (`name`)
);

--
-- Dumping data for table `#__jce_plugins`
--


INSERT INTO `#__jce_plugins` (`id`, `title`, `name`, `type`, `icon`, `layout`, `row`, `ordering`, `published`, `editable`, `elements`, `params`, `iscore`, `checked_out`, `checked_out_time`) VALUES
(1, 'Context Menu', 'contextmenu', 'plugin', '', '', 0, 19, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(2, 'Directionality', 'directionality', 'plugin', 'ltr,rtl', 'directionality', 3, 26, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(3, 'Emotions', 'emotions', 'plugin', 'emotions', 'emotions', 3, 24, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(4, 'Fullscreen', 'fullscreen', 'plugin', 'fullscreen', 'fullscreen', 3, 27, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(5, 'Paste', 'paste', 'plugin', 'pasteword,pastetext', 'paste', 2, 15, 1, 1, '', '', 1, 0, '0000-00-00 00:00:00'),
(6, 'Preview', 'preview', 'plugin', 'preview', 'preview', 3, 29, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(7, 'Tables', 'table', 'plugin', 'tablecontrols', 'buttons', 3, 11, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(8, 'Print', 'print', 'plugin', 'print', 'print', 3, 25, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(9, 'Search Replace', 'searchreplace', 'plugin', 'search,replace', 'searchreplace', 2, 18, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(10, 'Styles', 'style', 'plugin', 'styleprops', 'style', 4, 16, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(11, 'Non-Breaking', 'nonbreaking', 'plugin', 'nonbreaking', 'nonbreaking', 4, 21, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(12, 'Visual Characters', 'visualchars', 'plugin', 'visualchars', 'visualchars', 4, 20, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(13, 'XHTML Xtras', 'xhtmlxtras', 'plugin', 'cite,abbr,acronym,del,ins,attribs', 'xhtmlxtras', 4, 17, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(14, 'Image Manager', 'imgmanager', 'plugin', 'imgmanager', 'imgmanager', 4, 30, 1, 1, '', '', 1, 0, '0000-00-00 00:00:00'),
(15, 'Advanced Link', 'advlink', 'plugin', 'advlink', 'advlink', 4, 31, 1, 1, '', '', 1, 0, '0000-00-00 00:00:00'),
(16, 'Spell Checker', 'spellchecker', 'plugin', 'spellchecker', 'spellchecker', 4, 22, 1, 1, '', '', 1, 0, '0000-00-00 00:00:00'),
(17, 'Layers', 'layer', 'plugin', 'insertlayer,moveforward,movebackward,absolute', 'layer', 4, 10, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(18, 'Font ForeColour', 'forecolor', 'command', 'forecolor', 'forecolor', 2, 17, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(19, 'Bold', 'bold', 'command', 'bold', 'bold', 1, 2, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(20, 'Italic', 'italic', 'command', 'italic', 'italic', 1, 3, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(21, 'Underline', 'underline', 'command', 'underline', 'underline', 1, 4, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(22, 'Font BackColour', 'backcolor', 'command', 'backcolor', 'backcolor', 2, 18, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(23, 'Unlink', 'unlink', 'command', 'unlink', 'unlink', 2, 11, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(24, 'Font Select', 'fontselect', 'command', 'fontselect', 'fontselect', 1, 12, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(25, 'Font Size Select', 'fontsizeselect', 'command', 'fontsizeselect', 'fontsizeselect', 1, 13, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(26, 'Style Select', 'styleselect', 'command', 'styleselect', 'styleselect', 1, 10, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(27, 'New Document', 'newdocument', 'command', 'newdocument', 'newdocument', 1, 1, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(28, 'Help', 'help', 'plugin', 'help', 'help', 1, 6, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(29, 'StrikeThrough', 'strikethrough', 'command', 'strikethrough', 'strikethrough', 1, 5, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(30, 'Indent', 'indent', 'command', 'indent', 'indent', 2, 7, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(31, 'Outdent', 'outdent', 'command', 'outdent', 'outdent', 2, 6, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(32, 'Undo', 'undo', 'command', 'undo', 'undo', 2, 8, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(33, 'Redo', 'redo', 'command', 'redo', 'redo', 2, 9, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(34, 'Horizontal Rule', 'hr', 'command', 'hr', 'hr', 3, 2, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(35, 'HTML', 'html', 'command', 'code', 'code', 2, 16, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(36, 'Numbered List', 'numlist', 'command', 'numlist', 'numlist', 2, 5, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(37, 'Bullet List', 'bullist', 'command', 'bullist', 'bullist', 2, 4, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(38, 'Clipboard Actions', 'clipboard', 'command', 'cut,copy,paste', 'clipboard', 2, 1, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(39, 'Subscript', 'sub', 'command', 'sub', 'sub', 3, 5, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(40, 'Superscript', 'sup', 'command', 'sup', 'sup', 3, 6, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(41, 'Visual Aid', 'visualaid', 'command', 'visualaid', 'visualaid', 3, 4, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(42, 'Character Map', 'charmap', 'command', 'charmap', 'charmap', 3, 7, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(43, 'Justify Full', 'full', 'command', 'justifyfull', 'justifyfull', 1, 8, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(44, 'Justify Center', 'center', 'command', 'justifycenter', 'justifycenter', 1, 7, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(45, 'Justify Left', 'left', 'command', 'justifyleft', 'justifyleft', 1, 6, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(46, 'Justify Right', 'right', 'command', 'justifyright', 'justifyright', 1, 9, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(47, 'Remove Format', 'removeformat', 'command', 'removeformat', 'removeformat', 3, 3, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(48, 'Anchor', 'anchor', 'command', 'anchor', 'anchor', 2, 12, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(49, 'Format Select', 'formatselect', 'command', 'formatselect', 'formatselect', 1, 11, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(50, 'Image', 'image', 'command', 'image', 'image', 2, 13, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(51, 'Link', 'link', 'command', 'link', 'link', 2, 10, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(52, 'File Browser', 'browser', 'plugin', '', '', 0, 28, 1, 1, '', '', 1, 0, '0000-00-00 00:00:00'),
(53, 'Inline Popups', 'inlinepopups', 'plugin', '', '', 0, 12, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(54, 'Read More', 'readmore', 'plugin', 'readmore', 'readmore', 4, 23, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(55, 'Media Support', 'media', 'plugin', '', '', 0, 9, 1, 1, '', '', 1, 0, '0000-00-00 00:00:00'),
(56, 'Code Cleanup', 'cleanup', 'command', 'cleanup', 'cleanup', 2, 14, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(57, 'Safari Browser Support', 'safari', 'plugin', '', '', 0, 13, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00'),
(59, 'Advanced Code Editor', 'advcode', 'plugin', 'advcode', 'advcode', 4, 8, 1, 0, '', '', 1, 0, '0000-00-00 00:00:00');

--
-- Table structure for table `#__jce_extensions`
--

DROP TABLE IF EXISTS `#__jce_extensions`;
CREATE TABLE `#__jce_extensions` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `published` tinyint(3) NOT NULL,
  PRIMARY KEY  (`id`)
);

--
-- Dumping data for table `#__jce_extensions`
--

INSERT INTO `#__jce_extensions` (`id`, `pid`, `name`, `extension`, `folder`, `published`) VALUES
(1, 15, 'Joomla Links for Advanced Link', 'joomlalinks', 'links', 1);

--
-- Table structure for table `#__jce_groups`
--
DROP TABLE IF EXISTS `#__jce_groups`;
CREATE TABLE `#__jce_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `types` varchar(255) NOT NULL,
  `components` text NOT NULL,
  `rows` text NOT NULL,
  `plugins` varchar(255) NOT NULL,
  `published` tinyint(3) NOT NULL,
  `ordering` int(11) NOT NULL,
  `checked_out` tinyint(3) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
);

--
-- Dumping data for table `#__jce_groups`
--

INSERT INTO `#__jce_groups` (`id`, `name`, `description`, `users`, `types`, `components`, `rows`, `plugins`, `published`, `ordering`, `checked_out`, `checked_out_time`, `params`) VALUES
(1, 'Default', 'Default group for all users with edit access', '', '19,20,21,23,24,25', '', '28,27,32,33,19,20,21,29,45,44,43,46,26,49,36,37,30,31,39,40;56,47,38,5,9,48,42,24,25,22,18,2;7,17,13,10,3;23,15,14,59,16,4,6,8,12,54,34,41,11', '1,52,53,55,57,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,28,54,59', 1, 1, 62, '2008-08-01 18:52:15', '');
