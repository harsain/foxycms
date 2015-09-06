-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2015 at 08:10 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `foxy`
--

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` int(1) NOT NULL,
  `html_content` text NOT NULL,
  `php_file` text NOT NULL,
  `place` int(2) NOT NULL,
  `order` int(11) NOT NULL,
  `show_title` int(1) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `title`, `type`, `html_content`, `php_file`, `place`, `order`, `show_title`, `status`) VALUES
(1, 'Date & Time', 2, '', 'date.php', 3, 1, 1, 1),
(4, 'test in center', 2, 'hello', 'links_menu.php', 4, 2, 1, 1),
(8, 'Right', 1, '<p>\r\n	Very nice block</p>\r\n', '', 3, 3, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cp_notes`
--

CREATE TABLE IF NOT EXISTS `cp_notes` (
  `id` int(11) NOT NULL,
  `text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `pos_x` int(11) NOT NULL,
  `pos_y` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `jq_id` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cp_notes`
--

INSERT INTO `cp_notes` (`id`, `text`, `pos_x`, `pos_y`, `width`, `height`, `time`, `jq_id`) VALUES
(63, 'Test note..\n\ndone', 954, 49, 300, 218, 1440528750, 1);

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL,
  `lang_code` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `lang_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `dir` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `charset` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `lang_code`, `lang_name`, `dir`, `charset`) VALUES
(7, 'en', 'English', 'ltr', 'utf-8');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `menu_id` int(11) NOT NULL,
  `target` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `title`, `url`, `menu_id`, `target`, `order`, `status`) VALUES
(1, 'Home', 'index.php', 2, '_self', 2, 1),
(2, 'Test', '#', 3, '_self', 1, 1),
(3, 'Stylk.net', 'http://www.stylk.net/', 3, '_blank', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL,
  `var` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `directory` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `version` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `programmer_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `programmer_email` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `var`, `directory`, `status`, `name`, `version`, `programmer_name`, `programmer_email`) VALUES
(53, 'news', 'news', 1, 'News', '0.1', 'foxycms.org', 'info@foxycms.org'),
(52, 'links', 'links', 1, 'Links', '0.1', 'foxycms.org', 'info@foxycms.org'),
(54, 'sounds', '', 1, 'Sounds', '0.1', 'foxycms.org', 'info@foxycms.org');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `sub_title` text NOT NULL,
  `image` mediumtext NOT NULL,
  `author_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `summary` mediumtext NOT NULL,
  `text` longtext NOT NULL,
  `time` int(11) NOT NULL,
  `source` tinytext NOT NULL,
  `comments` int(1) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `sub_title`, `image`, `author_id`, `cat_id`, `summary`, `text`, `time`, `source`, `comments`, `status`) VALUES
(1, 'Barcelona vs. Realmadrid', 'test', '', 0, 1, 'Barcelona vs. Realmadrid', '<p>\r\n	Barcelona vs. Realmadrid</p>\r\n<p>\r\n	Barcelona vs. RealmadridBarcelona vs. RealmadridBarcelona vs. Realmadrid</p>\r\n<p>\r\n	Barcelona vs. RealmadridBarcelona vs. Realmadrid Barcelona vs. Realmadrid Barcelona vs. Realmadrid</p>\r\n', 0, 'kooora', 1, 1),
(2, 'test title', 'test subtitle', 'test', 0, 3, 'test', '<p style="text-align: center;">\r\n	<span style="font-family:verdana,geneva,sans-serif;"><span style="font-size: 48px;">test</span></span></p>\r\n', 4894089, '', 1, 1),
(3, 'Test Title', 'Test SubTitle', '45', 1, 1, 'TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary TEst Summary ', 'TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT TEst teXT v', 1046044, '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `news_cats`
--

CREATE TABLE IF NOT EXISTS `news_cats` (
  `id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `description` text NOT NULL,
  `parent_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_cats`
--

INSERT INTO `news_cats` (`id`, `title`, `description`, `parent_id`, `order`, `status`) VALUES
(1, 'Sports', 'For sports news ...', 0, 2, 1),
(2, 'Spanish league', 'test', 1, 5, 1),
(3, 'another cat', 'aaaaaa', 1, 1, 1),
(4, 'test cat', 'test', 0, 1, 1),
(5, 'sub sub cat', 'test', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `phrases`
--

CREATE TABLE IF NOT EXISTS `phrases` (
  `id` int(11) NOT NULL,
  `var` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `lang_id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `owner` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=448 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `phrases`
--

INSERT INTO `phrases` (`id`, `var`, `lang_id`, `text`, `owner`) VALUES
(81, 'template', 7, 'Template', 'Foxy'),
(80, 'disabled', 7, 'Disabled', 'Foxy'),
(1, 'welcome_text', 7, 'Welcome to our great website :D', 'Foxy'),
(304, 'separate_values_comma', 7, 'Separate values by a comma (value1,value2)', 'Foxy'),
(302, 'select_menu', 7, 'Select Menu', 'Foxy'),
(43, 'language', 7, 'Language', 'Foxy'),
(44, 'style', 7, 'Style', 'Foxy'),
(49, 'options', 7, 'Options', 'Foxy'),
(50, 'edit', 7, 'Edit', 'Foxy'),
(52, 'code', 7, 'Code', 'Foxy'),
(54, 'message', 7, 'Message', 'Foxy'),
(55, 'export', 7, 'Export', 'Foxy'),
(58, 'add', 7, 'Add', 'Foxy'),
(61, 'variable', 7, 'Variable', 'Foxy'),
(62, 'text', 7, 'Text', 'Foxy'),
(72, 'direction', 7, 'Direction', 'Foxy'),
(298, 'manage_users_fields', 7, 'Manage Fields', 'Foxy'),
(299, 'add_field', 7, 'Add Field', 'Foxy'),
(76, 'import_style', 7, 'Import Style', 'Foxy'),
(77, 'show_templates', 7, 'Show Templates', 'Foxy'),
(78, 'add_template', 7, 'Add Template', 'Foxy'),
(79, 'enabled', 7, 'Enabled', 'Foxy'),
(82, 'source', 7, 'Source', 'Foxy'),
(83, 'edit_template', 7, 'Edit Template', 'Foxy'),
(75, 'status', 7, 'Status', 'Foxy'),
(71, 'welcome_text', 7, 'Welcome to our great website :D0', 'Foxy'),
(69, 'edit_phrase', 7, 'Edit Phrase', 'Foxy'),
(68, 'language', 7, 'اللغة', 'Foxy'),
(74, 'add_style', 7, 'Add Style', 'Foxy'),
(63, 'module_plugin', 7, 'Module / Plugin', 'Foxy'),
(59, 'add_phrase', 7, 'Add Phrase', 'Foxy'),
(57, 'import_language', 7, 'Import Language', 'Foxy'),
(56, 'add_language', 7, 'Add Language', 'Foxy'),
(53, 'edit_language', 7, 'Edit Language', 'Foxy'),
(51, 'delete', 7, 'Delete', 'Foxy'),
(48, 'name', 7, 'Name', 'Foxy'),
(46, 'save', 7, 'Save', 'Foxy'),
(45, 'control_panel', 7, 'Control Panel', 'Foxy'),
(42, 'site_title', 7, 'Site Title', 'Foxy'),
(41, 'settings', 7, 'Settings', 'Foxy'),
(39, 'charset', 7, 'Charset', 'Foxy'),
(300, 'type', 7, 'Type', 'Foxy'),
(84, 'export_language', 7, 'Export Language', 'Foxy'),
(85, 'direction', 7, 'Direction', 'Foxy'),
(86, 'download_xml', 7, 'Download XML', 'Foxy'),
(87, 'import', 7, 'Import', 'Foxy'),
(88, 'file', 7, 'File', 'Foxy'),
(303, 'value', 7, 'Value', 'Foxy'),
(290, 'directory', 7, 'Directory', 'Foxy'),
(337, 'css', 7, 'CSS', 'Foxy'),
(334, 'languages', 7, 'Languages', 'Foxy'),
(336, 'edit_css', 7, 'Edit CSS', 'Foxy'),
(335, 'home', 7, 'Home', 'Foxy'),
(204, 'show_phrases', 7, 'Show Phrases', 'Foxy'),
(240, 'styles', 7, 'Styles', 'Foxy'),
(301, 'text', 7, 'Text', 'Foxy'),
(291, 'create', 7, 'Create', 'Foxy'),
(289, 'create_module', 7, 'Create Module', 'Foxy'),
(296, 'add_user', 7, 'Add User', 'Foxy'),
(295, 'manage_users', 7, 'Manage Users', 'Foxy'),
(294, 'users', 7, 'Users', 'Foxy'),
(250, 'modules', 7, 'Modules', 'Foxy'),
(252, 'import_module', 7, 'Import Module', 'Foxy'),
(253, 'manage_modules', 7, 'Manage Modules', 'Foxy'),
(254, 'show_details', 7, 'Show Details', 'Foxy'),
(255, 'back', 7, 'Go Back', 'Foxy'),
(256, 'version', 7, 'Version', 'Foxy'),
(257, 'variable', 7, 'Variable', 'Foxy'),
(258, 'programmer_name', 7, 'Programmer Name', 'Foxy'),
(259, 'programmer_email', 7, 'Programmer Email', 'Foxy'),
(260, 'download', 7, 'Download', 'Foxy'),
(261, 'export_ready', 7, 'Exported Successfully, <br /> ready to download', 'Foxy'),
(262, 'logout', 7, 'Log Out', 'Foxy'),
(263, 'confirmation_dialog', 7, 'Confirmation Dialog', 'Foxy'),
(264, 'delete_confirm', 7, 'Are you sure ? , you cannot undo', 'Foxy'),
(305, 'phrase_name', 7, 'Phrase Name', 'Foxy'),
(306, 'control_ajax', 7, 'Control Panel Ajax', 'Foxy'),
(307, 'on', 7, 'On', 'Foxy'),
(308, 'off', 7, 'Off', 'Foxy'),
(309, 'control_max_results', 7, 'Control Panel Max Results Per Page', 'Foxy'),
(310, 'go', 7, 'Go', 'Foxy'),
(311, 'go_to', 7, 'Go To', 'Foxy'),
(312, 'username', 7, 'Username', 'Foxy'),
(313, 'password', 7, 'Password', 'Foxy'),
(314, 'email', 7, 'Email', 'Foxy'),
(315, 'user_group', 7, 'User Group', 'Foxy'),
(316, 'manage', 7, 'Manage', 'Foxy'),
(317, 'latest_users', 7, 'Latest Users', 'Foxy'),
(318, 'ban_user', 7, 'Ban', 'Foxy'),
(319, 'users_groups', 7, 'Users Groups', 'Foxy'),
(320, 'add_group', 7, 'Add Group', 'Foxy'),
(321, 'priviliges', 7, 'Privileges', 'Foxy'),
(322, 'foxy', 7, 'Foxy', 'Foxy'),
(323, 'administrator', 7, 'Administrator', 'Foxy'),
(324, 'moderator', 7, 'Moderator', 'Foxy'),
(325, 'normal_user', 7, 'Normal User', 'Foxy'),
(326, 'this_field_required', 7, 'This field is required.', 'Foxy'),
(328, 'validate_invalid_number', 7, 'Please enter a valid number.', 'Foxy'),
(329, 'validate_invalid_email', 7, 'Please enter a valid email address.', 'Foxy'),
(330, 'validate_invalid_url', 7, 'Please enter a valid URL.', 'Foxy'),
(331, 'site_url', 7, 'Site URL', 'Foxy'),
(332, 'search', 7, 'Search', 'Foxy'),
(333, 'phrases', 7, 'Phrases', 'Foxy'),
(338, 'edit_block', 7, 'Edit Block', 'Foxy'),
(339, 'show_title', 7, 'Show Title', 'Foxy'),
(340, 'choose_block', 7, '[ Choose Block ]', 'Foxy'),
(341, 'content_bottom', 7, 'Content Bottom', 'Foxy'),
(342, 'content_top', 7, 'Content Top', 'Foxy'),
(343, 'center', 7, 'Center', 'Foxy'),
(344, 'left_menu', 7, 'Left Menu', 'Foxy'),
(345, 'right_menu', 7, 'Right Menu', 'Foxy'),
(346, 'footer', 7, 'Footer', 'Foxy'),
(347, 'header', 7, 'Header', 'Foxy'),
(348, 'no', 7, 'No', 'Foxy'),
(349, 'yes', 7, 'Yes', 'Foxy'),
(350, 'place', 7, 'Place', 'Foxy'),
(351, 'block_file', 7, 'Block File', 'Foxy'),
(352, 'html_code', 7, 'HTML Code', 'Foxy'),
(353, 'title', 7, 'Title', 'Foxy'),
(354, 'add_block', 7, 'Add Block', 'Foxy'),
(355, 'blocks', 7, 'Blocks', 'Foxy'),
(356, 'current_place', 7, 'Current Place:', 'Foxy'),
(358, 'upload', 7, 'Upload', 'Foxy'),
(359, 'designer_name', 7, 'Designer Name', 'Foxy'),
(360, 'designer_email', 7, 'Designer Email', 'Foxy'),
(386, 'mod_notes', 7, 'Notes', 'Foxy'),
(385, 'edit_link', 7, 'Edit Link', 'links'),
(384, 'order', 7, 'Order', 'links'),
(383, 'target', 7, 'Target', 'links'),
(382, 'menu', 7, 'Menu', 'links'),
(381, 'url', 7, 'URL', 'links'),
(378, 'links', 7, 'Links', 'links'),
(379, 'add_link', 7, 'Add Link', 'links'),
(380, 'show_links', 7, 'Show Links', 'links'),
(390, 'edit_phrases', 7, 'Edit Phrases', 'Foxy'),
(396, 'coded_by', 7, 'Coded By', 'Foxy'),
(397, 'do_with_selected', 7, 'Do with selected:', 'Foxy'),
(398, 'show_news', 7, 'Show News', 'news'),
(399, 'add_news', 7, 'Add News', 'news'),
(400, 'news', 7, 'News', 'news'),
(401, 'show_categories', 7, 'Show Categories', 'news'),
(402, 'add_category', 7, 'Add Category', 'news'),
(403, 'select_category', 7, 'Select Category', 'news'),
(404, 'show', 7, 'Show', 'news'),
(405, 'sub_title', 7, 'Sub Title', 'news'),
(406, 'image', 7, 'Image', 'news'),
(407, 'author', 7, 'Author', 'news'),
(408, 'category', 7, 'Category', 'news'),
(409, 'summary', 7, 'Summary', 'news'),
(410, 'time', 7, 'Time', 'news'),
(411, 'source', 7, 'Source', 'news'),
(412, 'allow_comments', 7, 'Allow Comments', 'news'),
(413, 'edit_news', 7, 'Edit News', 'news'),
(414, 'description', 7, 'Description', 'news'),
(415, 'parent_category', 7, 'Parent Category', 'news'),
(416, 'none', 7, 'None', 'news'),
(417, 'categories', 7, 'Categories', 'news'),
(418, 'edit_category', 7, 'Edit Category', 'news'),
(419, 'cancel', 7, 'Cancel', 'Foxy'),
(420, 'new_note', 7, 'New Note', 'Foxy'),
(421, 'arrange_blocks', 7, 'Arrange Blocks', 'Foxy'),
(422, 'server_os', 7, 'Server OS', 'Foxy'),
(423, 'php_version', 7, 'PHP Version', 'Foxy'),
(424, 'database', 7, 'Database', 'Foxy'),
(425, 'auth_failed', 7, 'Username or password is/are incorrect.', 'Foxy'),
(426, 'login', 7, 'Login', 'Foxy'),
(427, 'admin_welcome_msg', 7, 'Welcome', 'Foxy'),
(428, 'enable', 7, 'Enable', 'Foxy'),
(429, 'disable', 7, 'Disable', 'Foxy'),
(431, 'unban_user', 7, 'Unban', 'Foxy'),
(432, 'images_library', 7, 'Images Library', 'Foxy'),
(433, 'do_upload', 7, 'Upload', 'Foxy'),
(436, 'root_directory', 7, 'Root Directory', 'Foxy'),
(435, 'create_directory', 7, 'Create Directory', 'Foxy'),
(437, 'uploading', 7, 'Uploading ...', 'Foxy'),
(438, 'uploaded_successfully', 7, 'uploaded successfully', 'Foxy'),
(439, 'directory_contains_no_files', 7, 'This directory contains no files, you can upload one by clicking the upload button above.', 'Foxy'),
(440, 'select', 7, 'Select', 'Foxy'),
(441, 'move_to_folder', 7, 'Move', 'Foxy'),
(442, 'to', 7, 'To', 'Foxy'),
(443, 'upload_from_computer', 7, 'Upload File', 'Foxy'),
(444, 'upload_from_url', 7, 'Upload from URL', 'Foxy'),
(445, 'manage_fields', 7, 'Manage Fields', 'Foxy'),
(446, 'module', 7, 'Module', 'Foxy');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `language` int(11) NOT NULL,
  `control_ajax` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `style` int(11) NOT NULL,
  `site_title` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `site_url` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `control_max` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`language`, `control_ajax`, `style`, `site_title`, `site_url`, `control_max`) VALUES
(7, 'on', 1, 'foxycms', 'http://127.0.0.1/foxy/', 20);

-- --------------------------------------------------------

--
-- Table structure for table `styles`
--

CREATE TABLE IF NOT EXISTS `styles` (
  `id` int(11) NOT NULL,
  `style_code` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `style_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `css` longtext COLLATE utf8_unicode_ci NOT NULL,
  `version` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `designer_name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `designer_email` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `styles`
--

INSERT INTO `styles` (`id`, `style_code`, `style_name`, `status`, `css`, `version`, `designer_name`, `designer_email`) VALUES
(1, 'default', 'default', 1, 'body {\r\n	margin: 0;\r\n        font: 12px Tahoma;\r\n}\r\n#container {\r\n	width: 950px;\r\n        margin: 0 auto;\r\n        border: 3px solid #dddddd;\r\n        border-top: 0;\r\n        -webkit-border-bottom-right-radius: 10px;\r\n	-webkit-border-bottom-left-radius: 10px;\r\n	-moz-border-radius-bottomright: 10px;\r\n	-moz-border-radius-bottomleft: 10px;\r\n	border-bottom-right-radius: 10px;\r\n	border-bottom-left-radius: 10px;\r\n}\r\n#header {\r\n	height: 100px;\r\n        background: #f1f1f1;\r\n        text-align: center;\r\n}\r\n#footer {\r\n	background: #f1f1f1;\r\n        text-align: center;\r\n        font-size: 10px;\r\n        color: #999;\r\n        padding: 5px;\r\n	-webkit-border-bottom-right-radius: 10px;\r\n	-webkit-border-bottom-left-radius: 10px;\r\n	-moz-border-radius-bottomright: 10px;\r\n	-moz-border-radius-bottomleft: 10px;\r\n	border-bottom-right-radius: 10px;\r\n	border-bottom-left-radius: 10px;\r\n}\r\n\r\n.block_box {\r\n	margin: 10px;\r\n	border: 1px solid #dddddd;\r\n	-webkit-border-radius: 5px;\r\n	-moz-border-radius: 5px;\r\n	border-radius: 5px;\r\n}\r\n.block_title {\r\n	text-align: center;\r\n	color: #666;\r\n	background: #f1f1f1;\r\n	border-bottom: 1px solid #e1e1e1;\r\n	padding: 5px;\r\n	-webkit-border-top-left-radius: 5px;\r\n	-webkit-border-top-right-radius: 5px;\r\n	-moz-border-radius-topleft: 5px;\r\n	-moz-border-radius-topright: 5px;\r\n	border-top-left-radius: 5px;\r\n	border-top-right-radius: 5px;\r\n}\r\n.block_content {\r\n	padding: 7px;\r\n}', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL,
  `style_id` int(11) NOT NULL,
  `template` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `source` text COLLATE utf8_unicode_ci NOT NULL,
  `owner` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `style_id`, `template`, `source`, `owner`) VALUES
(49, 1, 'block', '<div class="block_box">\r\n  <!--title_start--><div class="block_title">#title#</div><!--title_end-->\r\n  <div class="block_content">\r\n    #content#\r\n  </div>\r\n</div>', 'Foxy'),
(50, 1, 'header', '<div id="container">\r\n  <div id="header">\r\n  {$settings.site_title}\r\n  </div>\r\n  <div id="content">\r\n    <div style="float:left;width:190px;">\r\n    {$blocks->get_blocks(4)}\r\n    </div>', 'AWCM'),
(51, 1, 'footer', '<div style="float:right;width:190px;">\r\n    		{$blocks->get_blocks(3)}\r\n    		</div>\r\n	<div style="clear:both;"></div>\r\n	</div>\r\n	<div id="footer">foxycms.org</div>\r\n</div>', 'Foxy');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `final_name` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `extention` text COLLATE utf8_unicode_ci NOT NULL,
  `dir` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `time`, `file_name`, `final_name`, `user_id`, `type`, `extention`, `dir`) VALUES
(45, 1321985643, 'usa_israel_gaza.jpg', 'image_132198564319.jpg', 9, 'image', 'jpg', 'root'),
(46, 1321985876, 'the_atlantic_ocean-1024x768.jpg', 'image_132198587639.jpg', 9, 'image', 'jpg', 'root'),
(47, 1322857786, 'تكميم الأفواه.jpg', 'image_132285778648.jpg', 9, 'image', 'jpg', 'root'),
(48, 1322857803, 'android_awesome.jpg', 'image_132285780330.jpg', 9, 'image', 'jpg', 'root'),
(51, 1322864732, 'Langelinie_Allé_by_SirPecanGum.jpg', 'image_132286473253.jpg', 9, 'image', 'jpg', 'root');

-- --------------------------------------------------------

--
-- Table structure for table `uploads_dir`
--

CREATE TABLE IF NOT EXISTS `uploads_dir` (
  `id` int(11) NOT NULL,
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `uploads_dir`
--

INSERT INTO `uploads_dir` (`id`, `name`) VALUES
(39, 'مجلد 5'),
(37, 'New Dir'),
(35, 'hello'),
(41, 'Test Dir'),
(42, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `username` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `password` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `email` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `user_group` int(11) NOT NULL,
  `test_text` text COLLATE utf8_unicode_ci NOT NULL,
  `test_select` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `level`, `username`, `password`, `status`, `email`, `user_group`, `test_text`, `test_select`) VALUES
(10, 0, 'foxy', '60470c3b4b6fed62c3baac064be65fac67c6e265', 1, 'info@foxycms.org', 3, '', 'value1');

-- --------------------------------------------------------

--
-- Table structure for table `users_fields`
--

CREATE TABLE IF NOT EXISTS `users_fields` (
  `id` int(11) NOT NULL,
  `field_name` tinytext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `field_type` tinytext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `field_value` text COLLATE utf8_unicode_ci NOT NULL,
  `phrase` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_fields`
--

INSERT INTO `users_fields` (`id`, `field_name`, `field_type`, `field_value`, `phrase`) VALUES
(10, 'test_select', 'select', 'value1,value2,value3,value4', 'test_select'),
(9, 'test_text', 'text', '', 'test_text');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL,
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `privileges` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `name`, `privileges`) VALUES
(4, 'Super User', '[MOD]|(module:53)(module:52)'),
(3, 'Admin', '[ADMIN]|');

-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--

CREATE TABLE IF NOT EXISTS `users_sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `useragent` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  `last_time` int(11) NOT NULL,
  `hash` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `remember` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_sessions`
--

INSERT INTO `users_sessions` (`id`, `user_id`, `ip`, `useragent`, `time`, `last_time`, `hash`, `remember`) VALUES
(40, 9, '127.0.0.1', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.30 (KHTML, like Gecko) Ubuntu/11.04 Chromium/12.0.742.112 Chrome/12.0.742.112 Safari/534.30', 1314294152, 1314294152, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(39, 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:6.0) Gecko/20100101 Firefox/6.0', 1314290926, 1314290926, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(51, 10, '127.0.0.1', 'Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20100101 Firefox/6.0', 1315510489, 1315510489, 'd48d1f4208069433221f23f441608b8b', 0),
(47, 9, '5.166.187.228', 'Mozilla/5.0 (Windows NT 5.1; rv:6.0.1) Gecko/20100101 Firefox/6.0.1', 1315015406, 1315015406, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(52, 9, '192.168.1.2', 'Mozilla/5.0 (Linux; U; Android 2.2.1; en-us; GT-S5570 Build/FROYO) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 1316077741, 1316077741, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(53, 9, '127.0.0.1', 'Mozilla/5.0 (X11; Linux i686; rv:6.0) Gecko/20100101 Firefox/6.0', 1316605417, 1316605417, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(54, 9, '127.0.0.1', 'Mozilla/5.0 (X11; Linux i686; rv:7.0.1) Gecko/20100101 Firefox/7.0.1', 1318877785, 1318877785, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(55, 9, '127.0.0.1', 'Mozilla/5.0 (Ubuntu; X11; Linux i686; rv:8.0) Gecko/20100101 Firefox/8.0', 1322857076, 1322857076, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(56, 9, '127.0.0.1', 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.120 Safari/535.2', 1323534821, 1323534821, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(57, 9, '127.0.0.1', 'Mozilla/5.0 (Ubuntu; X11; Linux i686; rv:9.0.1) Gecko/20100101 Firefox/9.0.1', 1327154312, 1327154312, 'e8e64e048d26fe1136b2fa48201a5702', 0),
(74, 10, '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0', 1441044464, 1441044464, '94dc1129038d591f9d3efc947e1e13ff', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cp_notes`
--
ALTER TABLE `cp_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_cats`
--
ALTER TABLE `news_cats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phrases`
--
ALTER TABLE `phrases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `styles`
--
ALTER TABLE `styles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads_dir`
--
ALTER TABLE `uploads_dir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_fields`
--
ALTER TABLE `users_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_sessions`
--
ALTER TABLE `users_sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `cp_notes`
--
ALTER TABLE `cp_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `news_cats`
--
ALTER TABLE `news_cats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `phrases`
--
ALTER TABLE `phrases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=448;
--
-- AUTO_INCREMENT for table `styles`
--
ALTER TABLE `styles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `uploads_dir`
--
ALTER TABLE `uploads_dir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users_fields`
--
ALTER TABLE `users_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users_sessions`
--
ALTER TABLE `users_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=75;
