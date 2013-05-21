<?php
/**
	Enlighter Class
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2013, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
if (!defined('ENLIGHTER_INIT')) die('DIRECT ACCESS PROHIBITED');


class Enlighter{
	// singleton instance
	private static $__instance;
	
	// shortcode handler instance
	private $_shortcodeHandler;
	
	// resource loader instamce
	private $_resourceLoader;
	
	// enlighter config keys with default values
	private $_config = array(
		'embedEnlighterCSS' => true,
		'embedEnlighterJS' => true,
		'mootoolsSource' => 'local',
		'configType' => 'meta',
		'defaultTheme' => 'standard',
		'defaultLanguage' => 'standard',
		'languageShortcode' => 'enabled',
		'indent' => -1,
		'compiler' => 'List',
		'altLines' => 'hover',
		'selector' => 'pre.EnlighterJSRAW',
		'customThemeBase' => 'standard',
		'customFontFamily' => 'Monaco, Courier, Monospace',
		'customFontSize' => '12px',
		'customLineHeight' => '16px',
		'customFontColor' => '#000000',
		'customLinenumberFontFamily' => 'Monaco, Courier, Monospace',
		'customLinenumberFontSize' => '10px',
		'customLinenumberLineHeight' => '15px',
		'customLinenumberFontColor' => '#000000'
	);
	
	// list of micro shortcodes (supported languages)
	private $_supportedLanguageKeys = array(
		'Cascading Style Sheets (CSS)' => 'css',
		'Hypertext Markup Language (HTML)' => 'html',
		'Java' => 'java',
		'Javascript' => 'js',
		'Markdown' => 'md',
		'PHP' => 'php',
		'Python' => 'python',
		'Ruby' => 'ruby',
		'Shell Script' => 'shell',
		'SQL' => 'sql',
		'XML' => 'xml',
		'C' => 'c',
		'C++' => 'cpp'
	);
	
	// list of supported themes
	private $_suppportedThemes = array(
		'Standard' => 'standard',
		'Custom' => 'wpcustom',
		'Git' => 'git',
		'Mocha' => 'mocha',
		'Panic' => 'panic',	
		'Tutti' => 'tutti',
		'Twilight' => 'twilight'					
	);
	
	// list of all customizable styles
	private $_customStyleKeys = array(
		'Keyword (Type1) Color' => 'kw1',
		'Keyword (Type2) Color' => 'kw2',
		'Keyword (Type3) Color' => 'kw3',
		'Keyword (Type4) Color' => 'kw4',
		'Slash-Style Comments Color' => 'co1',
		'Multiline-Style Comments Color' => 'co2',
		'Strings (Type1) Color' => 'st0',
		'Strings (Type2) Color' => 'st1',
		'Strings (Type3) Color' => 'st2',
		'Number Color' => 'nu0',
		'Method (Type1) Color' => 'me0',
		'Method (Type2) Color' => 'me1',
		'Bracket Color' => 'br0',
		'Symbol Color' => 'sy0',
		'Escape Symbol Color' => 'es0',
		'Regex Color' => 're0',
		'Start Delimiter Color' => 'de1',
		'Stop Delimiter Color' => 'de2'
	);
	
	// static entry/initialize singleton instance
	public static function run(){
		Enlighter::getInstance();
	}
	
	// get singelton instance
	public static function getInstance(){
		// check if singelton instance is avaible
		if (self::$__instance==null){
			// create new instance if not
			self::$__instance = new self();
		}
		return self::$__instance;
	}
	
	public function __construct(){
		
		// load enlighter config
		$this->loadConfig();
		
		// create new resource loader
		$this->_resourceLoader = new Enlighter_ResourceLoader($this->_config);
		
		// frontend or admin area ?
		if (is_admin()){
			// load language files
			load_plugin_textdomain('enlighter', null, basename(dirname(__FILE__)).'/lang');
			
			// add admin menu handler
			add_action('admin_menu', array($this, 'setupBackend'));
		}else{
			// create new shortcode handler, register all used shortcodes
			$this->_shortcodeHandler = new Enlighter_ShortcodeHandler($this->_config, array_merge($this->_supportedLanguageKeys, array('enlighter', 'codegroup')));
			
			// add shotcode handlers
			add_shortcode('enlighter', array($this->_shortcodeHandler, 'genericShortcodeHandler'));
			add_shortcode('codegroup', array($this->_shortcodeHandler, 'codegroupShortcodeHandler'));
			
			// enable language shortcodes ?
			if ($this->_config['languageShortcode']=='enabled'){
				foreach ($this->_supportedLanguageKeys as $lang){
					add_shortcode($lang, array($this->_shortcodeHandler, 'microShortcodeHandler'));
				}
			}

			// load frontend css+js
			add_action('wp_enqueue_scripts', array($this->_resourceLoader, 'appendCSS'));
			add_action('wp_enqueue_scripts', array($this->_resourceLoader, 'appendJS'));
			
			// display frontend config (as javascript or metadata)
			if ($this->_config['configType']=='meta'){
				add_action('wp_head', array($this->_resourceLoader, 'appendMetadataConfig'));
			}else{
				add_action('wp_head', array($this->_resourceLoader, 'appendJavascriptConfig'));
			}
		}
	}
	
	public function setupBackend(){
		// add options page
		$optionsPage = add_options_page(__('Enlighter - Advanced javascript based syntax highlighting'), __('Enlighter'), 'administrator', __FILE__, array($this, 'settingsPage'));
		
		// load jquery stuff
		add_action('admin_print_scripts-'.$optionsPage, array($this->_resourceLoader, 'appendAdminJS'));
		add_action('admin_print_styles-'.$optionsPage, array($this->_resourceLoader, 'appendAdminCSS'));
		
		// call register settings function
		add_action('admin_init', array($this, 'registerSettings'));
	}
	
	// options page
	public function settingsPage(){
		// well...is there no action hook for updating settings in wp ?
		if (isset($_GET['settings-updated'])){
			Enlighter_ThemeGenerator::updateCache($this->_config, $this->_customStyleKeys);
		}
		
		// create settings utility - based on local config
		$sUtil = new Enlighter_SettingsUtil($this->_config, 'enlighter-');
		
		// "extract" config arrays
		$langs = $this->_supportedLanguageKeys;
		$themes = $this->_suppportedThemes;
		$customStyleKeys = $this->_customStyleKeys;
		
		// include admin page
		include(ENLIGHTER_PLUGIN_PATH.'/views/admin/Settings.phtml');
	}
	
	// register settings
	public function registerSettings(){
		// register settings
		foreach ($this->_config as $key=>$value){
			register_setting('enlighter-settings-group', 'enlighter-'.$key);
		}
		
		// register custom style keys
		foreach ($this->_customStyleKeys as $key=>$value){
			register_setting('enlighter-settings-group', 'enlighter-custom-color-'.$value);
			register_setting('enlighter-settings-group', 'enlighter-custom-bgcolor-'.$value);
			register_setting('enlighter-settings-group', 'enlighter-custom-fontstyle-'.$value);
			register_setting('enlighter-settings-group', 'enlighter-custom-decoration-'.$value);
		}
	}
	
	// load options
	private function loadConfig(){
		foreach ($this->_config as $key=>$value){
			// get option by key
			$this->_config[$key] = get_option('enlighter-'.$key, $value);
		}
	}
	
}