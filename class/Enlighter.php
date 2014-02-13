<?php
/**
	Enlighter Class
	Version: 1.8
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://www.a3non.org/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2013-2014, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class Enlighter{
	// singleton instance
	private static $__instance;
	
	// shortcode handler instance
	private $_shortcodeHandler;
	
	// resource loader instamce
	private $_resourceLoader;
	
	// settings utility instance
	private $_settingsUtility;
	
	// enlighter config keys with default values
	private $_defaultConfig = array(
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
		'customLinenumberFontColor' => '#000000',
		'customLineHighlightColor' => '#f0f0ff',
		'customLineHoverColor' => '#f0f0ff',		
		'wpAutoPFilterPriority' => '12',
		'enableTranslation' => true,
		'enableTinyMceIntegration' => true	
	);
	
	// list of micro shortcodes (supported languages)
	private $_supportedLanguageKeys = array(
		'CSS (Cascading Style Sheets)' => 'css',
		'HTML (Hypertext Markup Language)' => 'html',
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
		'C++' => 'cpp',
		'NSIS' => 'nsis',
		'RAW' => 'raw'		
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
		'kw1', //'Keyword(Type1)Color', 'enlighter'),
		'kw2', //'Keyword(Type2)Color', 'enlighter'),
		'kw3', //'Keyword(Type3)Color', 'enlighter'),
		'kw4', //'Keyword(Type4)Color', 'enlighter'),
		'co1', //'Slash-StyleCommentsColor', 'enlighter'),
		'co2', //'Multiline-StyleCommentsColor', 'enlighter'),
		'st0', //'Strings(Type1)Color', 'enlighter'),
		'st1', //'Strings(Type2)Color', 'enlighter'),
		'st2', //'Strings(Type3)Color', 'enlighter'),
		'nu0', //'NumberColor', 'enlighter'),
		'me0', //'Method(Type1)Color', 'enlighter'),
		'me1', //'Method(Type2)Color', 'enlighter'),
		'br0', //'BracketColor', 'enlighter'),
		'sy0', //'SymbolColor', 'enlighter'),
		'es0', //'EscapeSymbolColor', 'enlighter'),
		're0', //'RegexColor', 'enlighter'),
		'de1', //'StartDelimiterColor', 'enlighter'),
		'de2'  //'StopDelimiterColor', 'enlighter')
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
		// generate theme customizers config keys
		foreach ($this->_customStyleKeys as $key){
			$this->_defaultConfig['custom-color-'.$key] = '';
			$this->_defaultConfig['custom-bgcolor-'.$key] = '';
			$this->_defaultConfig['custom-fontstyle-'.$key] = '';
			$this->_defaultConfig['custom-decoration-'.$key] = '';
		}
		
		// create new settings utility class
		$this->_settingsUtility = new Enlighter\SettingsUtil('enlighter-', $this->_defaultConfig);

		// load language files
		if ($this->_settingsUtility->getOption('enableTranslation')){
			load_plugin_textdomain('enlighter', null, 'enlighter/lang/');
		}
		
		// create new resource loader
		$this->_resourceLoader = new Enlighter\ResourceLoader($this->_settingsUtility);
		
		// frontend or admin area ?
		if (is_admin()){
			// add admin menu handler
			add_action('admin_menu', array($this, 'setupBackend'));
			
			// initialize TinyMCE modifications
			if ($this->_settingsUtility->getOption('enableTinyMceIntegration')){
				$editor = new Enlighter\TinyMCE($this->_settingsUtility, $this->_supportedLanguageKeys);

				// load tinyMCE styles
				add_filter('mce_css', array($this->_resourceLoader, 'appendTinyMceCSS'));
				
				// load tinyMCE enlighter plugin
				// add_filter('mce_external_plugins', array($this->_resourceLoader, 'appendTinyMceJS'));
			}			
		}else{
			// create new shortcode handler, register all used shortcodes
			$this->_shortcodeHandler = new Enlighter\ShortcodeHandler($this->_settingsUtility, array_merge($this->_supportedLanguageKeys, array('enlighter', 'codegroup')));
			
			// add shotcode handlers
			add_shortcode('enlighter', array($this->_shortcodeHandler, 'genericShortcodeHandler'));
			add_shortcode('codegroup', array($this->_shortcodeHandler, 'codegroupShortcodeHandler'));
			
			// enable language shortcodes ?
			if ($this->_settingsUtility->getOption('languageShortcode')=='enabled'){
				foreach ($this->_supportedLanguageKeys as $lang){
					add_shortcode($lang, array($this->_shortcodeHandler, 'microShortcodeHandler'));
				}
			}

			// load frontend css+js
			add_action('wp_enqueue_scripts', array($this->_resourceLoader, 'appendCSS'), 50);
			add_action('wp_enqueue_scripts', array($this->_resourceLoader, 'appendJS'), 50);
			
			// display frontend config (as javascript or metadata)
			if ($this->_settingsUtility->getOption('configType')=='meta'){
				add_action('wp_head', array($this->_resourceLoader, 'appendMetadataConfig'));
			}else{
				add_action('wp_head', array($this->_resourceLoader, 'appendJavascriptConfig'));
			}
			
			// change wpauto filter priority ?
			if ($this->_settingsUtility->getOption('wpAutoPFilterPriority')!='default'){
				remove_filter('the_content', 'wpautop');
				add_filter('the_content', 'wpautop' , 12);
			}
		}
	}
	
	public function setupBackend(){
		// add options page
		$optionsPage = add_options_page(__('Enlighter - Advanced javascript based syntax highlighting', 'enlighter'), 'Enlighter', 'administrator', __FILE__, array($this, 'settingsPage'));
		
		// load jquery stuff
		add_action('admin_print_scripts-'.$optionsPage, array($this->_resourceLoader, 'appendAdminJS'));
		add_action('admin_print_styles-'.$optionsPage, array($this->_resourceLoader, 'appendAdminCSS'));
		
		// call register settings function
		add_action('admin_init', array($this->_settingsUtility, 'registerSettings'));
	}
	
	// options page
	public function settingsPage(){
		// well...is there no action hook for updating settings in wp ?
		if (isset($_GET['settings-updated'])){
			Enlighter\ThemeGenerator::generateCSS($this->_settingsUtility, $this->_customStyleKeys);
		}
				
		// include admin page
		include(ENLIGHTER_PLUGIN_PATH.'/views/admin/Settings.phtml');
	}
	
}