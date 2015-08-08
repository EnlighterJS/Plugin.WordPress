<?php
/**
	Plugin Name: Enlighter - Customizable Syntax Highlighter
	Plugin URI: http://andidittrich.de/go/enlighterjs
	Description: Enlighter is a free, easy-to-use, syntax highlighting tool with a build-in theme editor.
	Version: 2.9
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	License: MIT X11-License
	
	Copyright (c) 2013-2015, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/*
*	BOOTSTRAP FILE
*/

define('ENLIGHTER_INIT', true);
define('ENLIGHTER_VERSION', '2.9');
define('ENLIGHTER_PLUGIN_PATH', dirname(__FILE__));

// check php version
if (version_compare(phpversion(), '5.3', '>=')){
	// load classes
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/Enlighter.php');	
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/HtmlUtil.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/SettingsUtil.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/ShortcodeHandler.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/ResourceLoader.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/SimpleTemplate.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/CacheManager.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/ThemeGenerator.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/ThemeManager.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/TinyMCE.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/ContextualHelp.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/ConfigGenerator.php');
	require_once(ENLIGHTER_PLUGIN_PATH.'/class/ObjectCache.php');
	
	// run enlighter
	Enlighter::run();
}else{
	// add admin menu handler
	add_action('admin_menu', 'Enlighter_SetupEnvironmentError');
}

// error options page setup
function Enlighter_SetupEnvironmentError(){
	// add options page
	add_options_page('Enlighter | Syntax Highlighter', 'Enlighter', 'administrator', __FILE__, 'Enlighter_EnvironmentError');
}
// options page
function Enlighter_EnvironmentError(){
	include(ENLIGHTER_PLUGIN_PATH.'/views/admin/EnvironmentError.phtml');
}
?>