<?php
/**
	Resource Utility Loader Class
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2013-2014, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Enlighter;

class ResourceLoader{
		
	// js config generator
	private $_jsConfigGenerator;
	
	// available language keys
	private $_languageKeys;
	
	// stores the plugin config
	private $_config;
	
	// CDN location list
	public static $cdnLocations = array();
	
	// list of external themes
	private $_themeManager;
	
	public function __construct($settingssUtil, $themeManager, $languageKeys){
		// store local plugin config
		$this->_config = $settingssUtil->getOptions();
		
		// store language keys
		$this->_languageKeys = $languageKeys;
		
		// local theme manager instance (required for external themes)
		$this->_themeManager = $themeManager;
		
		// initialize cdn locations
		self::$cdnLocations['mootools-local'] = plugins_url('/enlighter/resources/mootools-core-yc.js');
		self::$cdnLocations['mootools-google'] = '//ajax.googleapis.com/ajax/libs/mootools/1.5.1/mootools-yui-compressed.js';
		self::$cdnLocations['mootools-cdnjs'] = '//cdnjs.cloudflare.com/ajax/libs/mootools/1.5.1/mootools-core-full-nocompat.min.js';
	}
	
	// initialzize the frontend
	public function frontend($configGenerator){
		$this->_jsConfigGenerator = $configGenerator;
		
		// frontend js + css
		add_action('wp_enqueue_scripts', array($this, 'appendCSS'), 50);
		add_action('wp_enqueue_scripts', array($this, 'appendJS'), 50);
			
		// display frontend config
		if ($this->_config['jsType'] == 'inline-head'){
			add_action('wp_head', array($this, 'appendJavascriptConfig'));

		// footer location ?
		}else if ($this->_config['jsType'] == 'inline-footer'){			
			add_action('wp_footer', array($this, 'appendJavascriptConfig'));
		}
		
		// initialize Frontend TinyMCE modifications
		if ($this->_config['enableFrontendTinyMceIntegration'] && version_compare(get_bloginfo('version'), '3.9', '>=') ){
			add_action('init', array($this, 'frontendTinyMCE'));
		}
	}
	
	public function frontendTinyMCE(){
		// check frontend user priviliges
		$canEdit = is_user_logged_in() && current_user_can('edit_posts') && current_user_can('edit_pages');
		
		if (!$canEdit){
			return;
		}
		
		// apply editor modifications
		$editor = new TinyMCE($this->_config, $this->_languageKeys);
		
		// load tinyMCE styles
		add_filter('mce_css', array($this, 'appendTinyMceCSS'));
		
		// load tinyMCE enlighter plugin
		add_filter('mce_external_plugins', array($this, 'appendTinyMceJS'));
			
		// add frontend init script
		add_action('wp_head', array($this, 'appendInlineTinyMCEConfig'));
	}
	
	// initialize the backend
	public function backend(){
		// initialize TinyMCE modifications
		if ($this->_config['enableTinyMceIntegration'] && version_compare(get_bloginfo('version'), '3.9', '>=')){
			$editor = new TinyMCE($this->_config, $this->_languageKeys);
		
			// load tinyMCE styles
			add_filter('mce_css', array($this, 'appendTinyMceCSS'));

			// load tinyMCE enlighter plugin
			add_filter('mce_external_plugins', array($this, 'appendTinyMceJS'));
				
			// load global EnlighterJS options
			add_action('admin_print_scripts', array($this, 'appendInlineTinyMCEConfig'));
		}
	}
	
	// append javascript based config
	public function appendJavascriptConfig(){
		// generate a config based js tag
		echo '<script type="text/javascript">/* <![CDATA[ */', $this->_jsConfigGenerator->getJSConfig(), "/* ]]> */</script>\n";
	}
	
	// append css
	public function appendCSS(){
		// only include css if enabled
		if ($this->_config['embedEnlighterCSS']){
			// include generated css ?
			if ($this->_config['defaultTheme']=='wpcustom'){
				wp_register_style('enlighter-wpcustom', plugins_url('/enlighter/cache/EnlighterJS.custom.css'));
				wp_enqueue_style('enlighter-wpcustom');
			}else{
				// include standard css file ?
				wp_register_style('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.min.css'));
				wp_enqueue_style('enlighter-local');
			}
		}
		
		// load user themes ?
		if ($this->_config['embedExternalThemes']){
			// embed available external themes
			foreach ($this->_themeManager->getUserThemes() as $theme => $sources){
				wp_register_style('enlighter-external-'.strtolower($theme), $sources[1]);
				wp_enqueue_style('enlighter-external-'.strtolower($theme));
			}
		}

		// google webfonts "source code pro"
		if ($this->_config['webfontsSourceCodePro']){
			wp_register_style('webfonts-sourcecodepro', '//fonts.googleapis.com/css?family=Source+Code+Pro:400,700');
			wp_enqueue_style('webfonts-sourcecodepro');
		}
	}
	
	// append js
	public function appendJS(){
		// include mootools from local source ?
		if ($this->_config['mootoolsSource'] == 'local'){
			// include local mootools
			wp_register_script('mootools-local', self::$cdnLocations['mootools-local']);
			wp_enqueue_script('mootools-local');
		}
	
		// include mootools from google cdn ?
		if ($this->_config['mootoolsSource'] == 'google'){
			// include local mootools hosted by google's cdn
			wp_register_script('mootools-google-cdn', self::$cdnLocations['mootools-google']);
			wp_enqueue_script('mootools-google-cdn');
		}
		
		// include mootools from cloudfare cdn ?
		if ($this->_config['mootoolsSource'] == 'cdnjs'){
			// include local mootools hosted by cloudfares's cdn
			wp_register_script('mootools-cloudfare-cdn', self::$cdnLocations['mootools-cdnjs']);
			wp_enqueue_script('mootools-cloudfare-cdn');
		}
	
		// only include EnlighterJS js if enabled
		if ($this->_config['embedEnlighterJS']){
			// include local css file
			wp_register_script('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.min.js'));
			wp_enqueue_script('enlighter-local');
		}
		
		// only include EnlighterJS config if enabled
		if ($this->_config['jsType'] == 'external'){
			// include local css file
			wp_register_script('enlighter-config', plugins_url('/enlighter/cache/EnlighterJS.init.js'), array('enlighter-local'));
			wp_enqueue_script('enlighter-config');
		}
	}
	
	public function appendTinyMceCSS($mce_css){
		// append custom TinyMCE styles to editor stylelist
		if (empty($mce_css)){
			return plugins_url('/enlighter/resources/admin/TinyMCE.css');
		}else{
			return $mce_css .= ','.plugins_url('/enlighter/resources/admin/TinyMCE.css');
		}
	}
	
	public function appendTinyMceJS($mce_plugins){
		// TinyMCE plugin js
		$mce_plugins['enlighter'] = plugins_url('/enlighter/resources/admin/TinyMCE.js');
		return $mce_plugins;
	}
	
	public function appendAdminCSS(){
		// colorpicker css
		wp_register_style('enlighter-jquery-colorpicker', plugins_url('/enlighter/extern/colorpicker/css/colorpicker.css'));
		wp_enqueue_style('enlighter-jquery-colorpicker');
		
		// new UI styles
		wp_register_style('enlighter-settings', plugins_url('/enlighter/resources/admin/EnlighterSettings.css'));
		wp_enqueue_style('enlighter-settings');
	}
	
	public function appendAdminJS(){
		// load tooltipps
		//wp_enqueue_script('jquery-ui-tooltip', array('jquery'));
		
		// colorpicker js
		wp_register_script('enlighter-jquery-colorpicker', plugins_url('/enlighter/extern/colorpicker/js/colorpicker.js'), array('jquery'));
		wp_enqueue_script('enlighter-jquery-colorpicker');

		// jquery cookies
		wp_register_script('enlighter-jquery-cookies', plugins_url('/enlighter/extern/jquery.cookie/jquery.cookie.js'), array('jquery'));
		wp_enqueue_script('enlighter-jquery-cookies');
		
		// theme data
		wp_register_script('enlighter-themes', plugins_url('/enlighter/resources/admin/ThemeStyles.js'));
		wp_enqueue_script('enlighter-themes');
		
		// settings init script
		wp_register_script('enlighter-settings', plugins_url('/enlighter/resources/admin/EnlighterSettings.js'), array('jquery',  'enlighter-jquery-cookies', 'enlighter-jquery-colorpicker', 'enlighter-themes'));
		wp_enqueue_script('enlighter-settings');
	}
	
	public function appendInlineTinyMCEConfig(){
		// create config object
		$config = array(
			'languages' => \Enlighter::getAvailableLanguages(),
			'themes' => \Enlighter::getAvailableThemes(),
			'config' => array(
				'theme' => $this->_config['defaultTheme'],
				'language' => $this->_config['defaultLanguage'],
				'linenumbers' => ($this->_config['linenumbers'] ? true : false),
				'indent' => intval($this->_config['indent'])			
			)
		);
		
		// GLobal Admin Enlighter config
		echo '<script type="text/javascript">/* <![CDATA[ */';
		echo 'var Enlighter = ', json_encode($config), ';/* ]]> */</script>';
	}
	
	
}