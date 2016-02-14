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

    // javascript position
    private $_jsInFooter = false;

    // tinymce editor
    private $_tinymce;

    // update hash
    private $_uhash;
	
	public function __construct($settingssUtil, $themeManager, $languageKeys, $tinymce){
		// store local plugin config
		$this->_config = $settingssUtil->getOptions();
		
		// store language keys
		$this->_languageKeys = $languageKeys;
		
		// local theme manager instance (required for external themes)
		$this->_themeManager = $themeManager;

        // store tinymce editor instance
        $this->_tinymce = $tinymce;

        // get javascript position
        $this->_jsInFooter = ($this->_config['jsPosition'] == 'footer');

        // get last update hash
        $this->_uhash = get_option('enlighter-settingsupdate-hash', '0A0B0C');
		
		// initialize cdn locations
		self::$cdnLocations['mootools-local'] = plugins_url('/enlighter/resources/mootools-core-yc.js');
		self::$cdnLocations['mootools-google'] = '//ajax.googleapis.com/ajax/libs/mootools/1.6.0/mootools.min.js';
		self::$cdnLocations['mootools-cdnjs'] = '//cdnjs.cloudflare.com/ajax/libs/mootools/1.6.0/mootools-core.min.js';
	}
	
	// initialzize the frontend
	public function frontend($configGenerator){
		$this->_jsConfigGenerator = $configGenerator;
		
		// frontend js + css
		add_action('wp_enqueue_scripts', array($this, 'appendCSS'), 50);
		add_action('wp_enqueue_scripts', array($this, 'appendJS'), 50);
			
		// display frontend config - backward compatible to v2.9
		if ($this->_config['jsType'] != 'external' && $this->_config['jsType'] != 'none'){

            // header or footer location ? set priority to 30 to print scripts after enqueued one (20)
            if ($this->_jsInFooter){
                add_action('wp_footer', array($this, 'appendJavascriptConfig'), 30);
            }else{
                add_action('wp_head', array($this, 'appendJavascriptConfig'), 30);
            }
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
        $this->_tinymce->integrate();
		
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
            $this->_tinymce->integrate();
		
			// load tinyMCE styles
			add_filter('mce_css', array($this, 'appendTinyMceCSS'));

			// load tinyMCE enlighter plugin
			add_filter('mce_external_plugins', array($this, 'appendTinyMceJS'));
				
			// load global EnlighterJS options
			add_action('admin_print_scripts', array($this, 'appendInlineTinyMCEConfig'));
		}

        add_action('admin_print_footer_scripts', array($this, 'appendInlineEditorConfig'));
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
                wp_enqueue_style('enlighter-wpcustom', plugins_url('/enlighter/cache/EnlighterJS.custom.css'), array(), $this->_uhash);
			}else{
				// include standard css file ?
                wp_enqueue_style('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.min.css'), array(), ENLIGHTER_VERSION);
			}
		}
		
		// load user themes ?
		if ($this->_config['embedExternalThemes']) {
            // embed available external themes
            foreach ($this->_themeManager->getUserThemes() as $theme => $sources) {
                wp_enqueue_style('enlighter-external-' . strtolower($theme), $sources[1], array(), ENLIGHTER_VERSION);
            }
        }

        // list of google webfonts to load
        $webfontList = array();

        // get all available webfonts
        $webfonts = GoogleWebfontResources::getMonospaceFonts();

        // load enabled fonts
        foreach ($webfonts as $name => $font){
            $fid = preg_replace('/[^A-Za-z0-9]/', '', $name);
            if ($this->_config['webfonts'.$fid]){
                $webfontList[] = $font;
            }
        }

		// load webfonts ?
		if (count($webfontList) > 0){
            wp_enqueue_style('enlighter-webfonts', '//fonts.googleapis.com/css?family=' . implode('|', $webfontList));
		}
	}
	
	// append js
	public function appendJS(){
		// include mootools from local source ?
		if ($this->_config['mootoolsSource'] == 'local'){
			// include local mootools
            wp_enqueue_script('mootools-local', self::$cdnLocations['mootools-local'], array(), false, $this->_jsInFooter);
		}
	
		// include mootools from google cdn ?
		if ($this->_config['mootoolsSource'] == 'google'){
			// include local mootools hosted by google's cdn
            wp_enqueue_script('mootools-google-cdn', self::$cdnLocations['mootools-google'], array(), false, $this->_jsInFooter);
		}
		
		// include mootools from cloudfare cdn ?
		if ($this->_config['mootoolsSource'] == 'cdnjs'){
			// include local mootools hosted by cloudfares's cdn
            wp_enqueue_script('mootools-cloudfare-cdn', self::$cdnLocations['mootools-cdnjs'], array(), false, $this->_jsInFooter);
		}
	
		// only include EnlighterJS js if enabled
		if ($this->_config['embedEnlighterJS']){
			// include local css file
            wp_enqueue_script('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.min.js'), array(), ENLIGHTER_VERSION, $this->_jsInFooter);
		}
		
		// only include EnlighterJS config if enabled
		if ($this->_config['jsType'] == 'external'){
			// include local css file
            wp_enqueue_script('enlighter-config', plugins_url('/enlighter/cache/EnlighterJS.init.js'), array('enlighter-local'), $this->_uhash, $this->_jsInFooter);
		}
	}
	
	public function appendTinyMceCSS($mce_css){
        // add hash from last settings update to force a cache update
        $url = plugins_url('/enlighter/cache/TinyMCE.css?' . $this->_uhash);

        // other styles loaded ?
		if (empty($mce_css)){
			return $url;

        // append custom TinyMCE styles to editor stylelist
        }else{
			return $mce_css . ','.$url;
		}
	}
	
	public function appendTinyMceJS($mce_plugins){
		// TinyMCE plugin js
		$mce_plugins['enlighter'] = plugins_url('/enlighter/resources/editor/TinyMCE.js');
		return $mce_plugins;
	}
	
	public function appendAdminCSS(){
		// colorpicker css
        wp_enqueue_style('enlighter-jquery-colorpicker', plugins_url('/enlighter/extern/colorpicker/css/colorpicker.css'), array(), ENLIGHTER_VERSION);

		// new UI styles
        wp_enqueue_style('enlighter-settings', plugins_url('/enlighter/resources/admin/EnlighterSettings.css'), array(), ENLIGHTER_VERSION);
	}
	
	public function appendAdminJS(){
		// colorpicker js
        wp_enqueue_script('enlighter-jquery-colorpicker', plugins_url('/enlighter/extern/colorpicker/js/colorpicker.js'), array('jquery'), ENLIGHTER_VERSION);

		// jquery cookies
        wp_enqueue_script('enlighter-jquery-cookies', plugins_url('/enlighter/extern/jquery.cookie/jquery.cookie.js'), array('jquery'), ENLIGHTER_VERSION);

		// theme data
        wp_enqueue_script('enlighter-themes', plugins_url('/enlighter/resources/admin/ThemeStyles.js'), array(), ENLIGHTER_VERSION);

		// settings init script
        wp_enqueue_script('enlighter-settings', plugins_url('/enlighter/resources/admin/EnlighterSettings.js'), array('jquery',  'enlighter-jquery-cookies', 'enlighter-jquery-colorpicker', 'enlighter-themes'), ENLIGHTER_VERSION);
	}
	
	public function appendInlineTinyMCEConfig(){
		// Global Admin Enlighter config
		echo '<script type="text/javascript">/* <![CDATA[ */var Enlighter = ', json_encode($this->_tinymce->getPluginConfig()), ';/* ]]> */</script>';
	}

    // quick-tags
    public function appendInlineEditorConfig(){
        $buffer = '';
        $num = 150;

        foreach (\Enlighter::getAvailableLanguages() as $name => $lang){
            $num++;
            $buffer .= "QTags.addButton( 'enlighter-$lang', '$lang', '<pre class=\"EnlighterJSRAW\" data-enlighter-language=\"$lang\">', '</pre>', null, '$name', $num);";
        }

        echo '<script type="text/javascript">/* <![CDATA[ */ if (typeof QTags != "undefined"){', $buffer, '};/* ]]> */</script>';
    }
	
	
}