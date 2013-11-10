<?php
/**
	Plugin Name: Enlighter - Javascript based syntax highlighting
	Plugin URI: http://www.a3non.org/go/enlighterjs
	Description: Enlighter is a free, easy-to-use, syntax highlighting tool with a build-in theme editor.
	Version: 1.5
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	License: MIT X11-License
	
	Copyright (c) 2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/*
*	BOOTSTRAP FILE
*/

define('ENLIGHTER_INIT', true);
define('ENLIGHTER_VERSION', '1.5');
define('ENLIGHTER_PLUGIN_PATH', dirname(__FILE__));

// load classes
require_once(ENLIGHTER_PLUGIN_PATH.'/class/Enlighter.php');	
require_once(ENLIGHTER_PLUGIN_PATH.'/class/HtmlUtil.php');
require_once(ENLIGHTER_PLUGIN_PATH.'/class/SettingsUtil.php');
require_once(ENLIGHTER_PLUGIN_PATH.'/class/ShortcodeHandler.php');
require_once(ENLIGHTER_PLUGIN_PATH.'/class/ResourceLoader.php');
require_once(ENLIGHTER_PLUGIN_PATH.'/class/SimpleTemplate.php');
require_once(ENLIGHTER_PLUGIN_PATH.'/class/ThemeGenerator.php');

// run enlighter
Enlighter::run();

?>