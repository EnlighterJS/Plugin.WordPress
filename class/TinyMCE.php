<?php
/**
	TinyMCE Editor Addons
	Version: 1.2
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/enlighterjs
	License: MIT X11-License
	
	Copyright (c) 2014, Andi Dittrich

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Enlighter;

class TinyMCE{
	
	// stores the plugin config
	private $_config;
	
	// plugin supported languages
	private $_supportedLanguageKeys;
	
	public function __construct($config, $languageKeys){
		// store local plugin config
		$this->_config = $config;
		
		// store languages
		$this->_supportedLanguageKeys = $languageKeys;
		
		// add filter to enable the custom style menu - low priority to avoid conflicts with other plugins which try to overwrite the settings
		add_filter('mce_buttons_2', array($this, 'addButtons2'), 101);
		add_filter('mce_buttons', array($this, 'addButtons1'), 101);
		
		// TinyMCE 4 required !
		if (version_compare(get_bloginfo('version'), '3.9', '>=')) {
			// add filter to add custom formats (TinyMCE 4; requires WordPress 3.9) - low priority to avoid conflicts with other plugins which try to overwrite the settings
			add_filter('tiny_mce_before_init', array($this, 'insertFormats4'), 101);			
		}
	}
	
	// insert "code insert dialog button"
	public function addButtons1($buttons){
		// Enlighter insert already available ?
		if (!in_array('EnlighterInsert', $buttons)){
			$buttons[] = 'EnlighterInsert';
			$buttons[] = 'EnlighterEdit';
		}
		return $buttons;
	}
	
	// insert styleselect menu into the $buttons array
	public function addButtons2($buttons){
		// styleselect menu already enabled ?
		if (!in_array('styleselect', $buttons)){
			array_unshift($buttons, 'styleselect');
		}
		return $buttons;
	}
	
	// callback function to filter the MCE settings
	public function insertFormats4($tinyMceConfigData){
		// new style formats
		$styles = array();
		
		// style formats already defined ?
		if (isset($tinyMceConfigData['style_formats'])){
			$styles = json_decode($tinyMceConfigData['style_formats'], true);
		}
		
		// valid html tgas
		if (isset($tinyMceConfigData['valid_children'])){
			$tinyMceConfigData['valid_children'] .= '-code[code]';
		}else{
			$tinyMceConfigData['valid_children'] = '-code[code]';
		}
		
		// create new "Enlighter Codeblocks" item
		$blockstyles = array();
		
		// add all supported languages as Enlighter Style
		foreach ($this->_supportedLanguageKeys as $name => $lang){				
			// define new enlighter style formats
			$blockstyles[] = array(
					'title' => ''.$name,
					'block' => 'pre',
					'classes' => 'EnlighterJSRAW',
					'wrapper' => false,
					'attributes' => array(
						'data-enlighter-language' => $lang
					)
			);
		}
		
		// add block styles
		$styles[] = array(
			'title' => __('Enlighter Codeblocks', 'enlighter'),
			'items' => $blockstyles				
		);
		
		// inline highlighting enabled ?
		if ($this->_config['enableInlineHighlighting']){
			$inlinestyles = array();
			
			foreach ($this->_supportedLanguageKeys as $name => $lang){
				// define new enlighter inline style formats
				$inlinestyles[] =	array(
						'title' => ''.$name,
						'inline' => 'code',
						'classes' => 'EnlighterJSRAW',
						'wrapper' => false,
						'selector' => '',
						'attributes' => array(
								'data-enlighter-language' => $lang
						)
				);
			}
			
			// add inline styles
			$styles[] = array(
					'title' => __('Enlighter Inline', 'enlighter'),
					'items' => $inlinestyles
			);
		}
		
		// dont overwrite all settings
		$tinyMceConfigData['style_formats_merge'] = true;
		
		// apply modified style data
		$tinyMceConfigData['style_formats'] = json_encode($styles);
		
		// remove tabfocus plugin
		//$tinyMceConfigData['plugins'] = str_replace('tabfocus,', '', $tinyMceConfigData['plugins']);
		
		return $tinyMceConfigData;
	}	
}