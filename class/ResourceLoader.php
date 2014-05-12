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
		
	// stores the plugin config
	private $_config;
	
	public function __construct($settingssUtil){
		// store local plugin config
		$this->_config = $settingssUtil->getOptions();
	}
	
	// append metadata based config
	public function appendMetadataConfig(){
		// generates a config based metatag
		echo HtmlUtil::generateTag('meta', array(
				'name' => 'EnlighterJS',
				'content' => 'Advanced javascript based syntax highlighting',
				'data-language' => $this->_config['defaultLanguage'],
				'data-theme' => $this->_config['defaultTheme'],
				'data-indent' => $this->_config['indent'],
				'data-hover' => $this->_config['hoverClass'],
				'data-selector-block' =>  $this->_config['selector'],
				'data-selector-inline' =>  $this->_config['selectorInline'],
				'data-linenumbers' => $this->_config['linenumbers'],
				'data-rawcodebutton' => $this->_config['rawcodebutton']
		));
		echo "\n";
	}
	
	// append javascript based config
	public function appendJavascriptConfig(){
		// generate a config based js tag
		echo '<script type="text/javascript">';
		echo 'window.addEvent(\'domready\', function(){';
		echo 'EnlighterJS.Util.Helper(document.getElements(\'', $this->_config['selector'] ,'\'), ';
		echo json_encode(array(
			'renderer' => 'Block',	
			'language' => $this->_config['defaultLanguage'],
			'theme' => $this->_config['defaultTheme'],
			'indent' => intval($this->_config['indent']),
			'hover' => $this->_config['hoverClass'],
			'showLinenumbers' => ($this->_config['linenumbers']==='true'),
			'rawButton' => 	($this->_config['rawcodebutton'] !== 'false'),
			'grouping' => true
		));
		echo ');';
		echo 'EnlighterJS.Util.Helper(document.getElements(\'', $this->_config['selectorInline'] ,'\'), ';
		echo json_encode(array(
				'renderer' => 'Inline',
				'language' => $this->_config['defaultLanguage'],
				'theme' => $this->_config['defaultTheme'],
				'indent' => intval($this->_config['indent']),
				'hover' => $this->_config['hoverClass'],
				'showLinenumbers' => ($this->_config['linenumbers']==='true'),
				'rawButton' => 	($this->_config['rawcodebutton'] !== 'false'),
				'grouping' => false
		));
		echo ');';
		echo "});</script>\n";
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
				wp_register_style('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.yui.css'));
				wp_enqueue_style('enlighter-local');
			}
		}
	}
	
	// append js
	public function appendJS(){
		// include mootools from local source ?
		if ($this->_config['mootoolsSource'] == 'local'){
			// include local mootools
			wp_register_script('mootools-local', plugins_url('/enlighter/resources/mootools-core-1.4.5-full-nocompat-yc.js'));
			wp_enqueue_script('mootools-local');
		}
	
		// include mootools from google cdn ?
		if ($this->_config['mootoolsSource'] == 'google'){
			// include local mootools hosted by google's cdn
			wp_register_script('mootools-google-cdn', '//ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js');
			wp_enqueue_script('mootools-google-cdn');
		}
		
		// include mootools from cloudfare cdn ?
		if ($this->_config['mootoolsSource'] == 'cdnjs'){
			// include local mootools hosted by cloudfares's cdn
			wp_register_script('mootools-cloudfare-cdn', '//cdnjs.cloudflare.com/ajax/libs/mootools/1.4.5/mootools-core-full-nocompat-yc.js');
			wp_enqueue_script('mootools-cloudfare-cdn');
		}
	
		// only include EnlighterJS js if enabled
		if ($this->_config['embedEnlighterJS']){
			// include local css file
			wp_register_script('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.yui.js'));
			wp_enqueue_script('enlighter-local');
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
		
		// new UI ?
		if (version_compare(get_bloginfo('version'), '3.8', '>=')) {
			wp_register_style('enlighter-settings', plugins_url('/enlighter/resources/admin/settings38.css'));
		}else{
			wp_register_style('enlighter-settings', plugins_url('/enlighter/resources/admin/settings37.css'));
		}
		
		// settings css
		wp_enqueue_style('enlighter-settings');
	}
	
	public function appendAdminJS(){
		// load tooltipps
		//wp_enqueue_script('jquery-ui-tooltip', array('jquery'));
		
		// colorpicker js
		wp_register_script('enlighter-jquery-colorpicker', plugins_url('/enlighter/extern/colorpicker/js/colorpicker.js'), array('jquery'));
		wp_enqueue_script('enlighter-jquery-colorpicker');
		
		// theme data
		wp_register_script('enlighter-themes', plugins_url('/enlighter/resources/admin/ThemeStyles.js'));
		wp_enqueue_script('enlighter-themes');
		
		// settings init script
		wp_register_script('enlighter-settings-init', plugins_url('/enlighter/resources/admin/EnlighterSettings.js'), array('jquery', 'enlighter-themes'));
		wp_enqueue_script('enlighter-settings-init');
	}
	
	
}