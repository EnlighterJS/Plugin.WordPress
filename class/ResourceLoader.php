<?php
/**
	Resource Utility Loader Class
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


class Enlighter_ResourceLoader{
		
	// stores the plugin config
	private $_config;
	
	public function __construct($config){
		// store local plugin config
		$this->_config = $config;
	}
	
	// append metadata based config
	public function appendMetadataConfig(){
		// generates a config based metatag
		echo Enlighter_HtmlUtil::generateTag('meta', array(
				'name' => 'EnlighterJS',
				'content' => 'Advanced javascript based syntax highlighting',
				'data-language' => $this->_config['defaultLanguage'],
				'data-theme' => $this->_config['defaultTheme'],
				'data-indent' => $this->_config['indent'],
				'data-compiler' => $this->_config['compiler'],
				'data-altlines' => $this->_config['altLines'],
				'data-selector' =>  $this->_config['selector']
		));
		echo "\n";
	}
	
	// append javascript based config
	public function appendJavascriptConfig(){
		// generate a config based js tag
		echo '<script type="text/javascript">', "\n";
		echo 'window.addEvent(\'domready\', function(){', "\n";;
		echo 'new EnlighterJS.Helper($$(\'', $this->_config['selector'], "'), {\n";
		echo 'language: \'', $this->_config['defaultLanguage'], "',\n";
		echo 'theme: \'', $this->_config['defaultTheme'], "',\n";
		echo 'indent: ', $this->_config['indent'], ",\n";
		echo 'compiler: \'', $this->_config['compiler'], "',\n";
		echo 'altLines: \'', $this->_config['altLines'], "'\n";
		echo "});});</script>\n";
	}
	
	// append css
	public function appendCSS(){
		// only include css if enabled
		if ($this->_config['embedEnlighterCSS']){
			// include local css file
			wp_register_style('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.yui.css'));
			wp_enqueue_style('enlighter-local');
			
			// include generated css ?
			if ($this->_config['defaultTheme']=='wpcustom'){
				wp_register_style('enlighter-wpcustom', plugins_url('/enlighter/cache/EnlighterJS.custom.css'), array('enlighter-local'));
				wp_enqueue_style('enlighter-wpcustom');				
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
	
		// only include EnlighterJS js if enabled
		if ($this->_config['embedEnlighterJS']){
			// include local css file
			wp_register_script('enlighter-local', plugins_url('/enlighter/resources/EnlighterJS.yui.js'));
			wp_enqueue_script('enlighter-local');
		}
	}
	
	public function appendAdminCSS(){
		// colorpicker css
		wp_register_style('enlighter-jquery-colorpicker', plugins_url('/enlighter/extern/colorpicker/css/colorpicker.css'));
		wp_enqueue_style('enlighter-jquery-colorpicker');
		
		// settings css
		wp_register_style('enlighter-settings', plugins_url('/enlighter/views/admin/settings.css'));
		wp_enqueue_style('enlighter-settings');
	}
	
	public function appendAdminJS(){
		// colorpicker js
		wp_register_script('enlighter-jquery-colorpicker', plugins_url('/enlighter/extern/colorpicker/js/colorpicker.js'), array('jquery'));
		wp_enqueue_script('enlighter-jquery-colorpicker');
		
		// settings init script
		wp_register_script('enlighter-settings-init', plugins_url('/enlighter/views/admin/settings.js'), array('jquery'));
		wp_enqueue_script('enlighter-settings-init');
	}
	
	
}