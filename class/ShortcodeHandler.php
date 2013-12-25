<?php
/**
	Shortcode Handler Class
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
namespace Enlighter;

class ShortcodeHandler{
	
	// stores the plugin config
	private $_config;
	
	// store registered shortcodes
	private $_registeredShortcodes;
	
	// currently active codegroup
	private $_activeCodegroup;
	
	public function __construct($settingsUtil, $registeredShortcodes){
		// store local plugin config
		$this->_config = $settingsUtil->getOptions();
		
		// store registered shortcodes
		$this->_registeredShortcodes = $registeredShortcodes;
		
		// add texturize filter
		add_filter('no_texturize_shortcodes', array($this, 'texturizeHandler'));
	}
	
	// handle codegroups
	public function codegroupShortcodeHandler($shortcodeAttributes=NULL, $content='', $tagname=''){
		// default attribute settings
		$shortcodeAttributes = shortcode_atts(
				array(
					'theme' => $this->_config['defaultTheme']
				), $shortcodeAttributes);
	
		// html "pre"-tag attributes
		$htmlAttributes = array(
				'data-enlighter-theme' => $shortcodeAttributes['theme'],
				'class' => 'EnlighterJSRAW',
				'data-enlighter-group' => 'EnlighterCodegroup_'.uniqid()
		);
	
		// assign html attrivutes
		$this->_activeCodegroup = $htmlAttributes;
	
		// call micro shortcode handlers
		$content = do_shortcode($content);
		
		// remove automatic generated html editor tags (from wpautop())
		$content = $this->removeWpAutoP($content);
		
		// disable group flag
		$this->_activeCodegroup = NULL;
		
		// return parsed content
		return $content;
	}
	
	// handle micro shortcode [php,js ..] ...
	public function microShortcodeHandler($shortcodeAttributes=NULL, $content='', $tagname=''){
		// default attribute settings
		$shortcodeAttributes = shortcode_atts(
				array(
						'theme' => $this->_config['defaultTheme'],
						'lang' => $this->_config['defaultLanguage'],
						'group' => false,
						'tab' => false,
						'highlight' => ''
				), $shortcodeAttributes);
	
		// html "pre"-tag attributes
		$htmlAttributes = array(
				'data-enlighter-language' => $tagname,
				'data-enlighter-theme' => $shortcodeAttributes['theme'],
				'data-enlighter-title' => $shortcodeAttributes['tab'],
				'data-enlighter-highlight' => $shortcodeAttributes['highlight'],
				'class' => 'EnlighterJSRAW'
		);
	
		// grouping enabled ?
		if ($shortcodeAttributes['group']){
			$htmlAttributes['data-enlighter-group'] = $shortcodeAttributes['group'];
		}
		
		// codegroup active ?
		if ($this->_activeCodegroup != NULL){
			// overwrite settings
			$htmlAttributes['data-enlighter-theme'] = $this->_activeCodegroup['data-enlighter-theme'];
			$htmlAttributes['class'] = $this->_activeCodegroup['class'];
			$htmlAttributes['data-enlighter-group'] = $this->_activeCodegroup['data-enlighter-group'];
		}
	
		// generate html output
		return $this->generateCodeblock($htmlAttributes, $content);
	}
	
	// handle wp shortcode [enlighter ..] ... [/enlighter] - generic handling
	public function genericShortcodeHandler($shortcodeAttributes=NULL, $content='', $tagname=''){
		// default attribute settings
		$shortcodeAttributes = shortcode_atts(
				array(
						'theme' => $this->_config['defaultTheme'],
						'lang' => $this->_config['defaultLanguage'],
						'group' => false,
						'tab' => false,
						'highlight' => ''
				), $shortcodeAttributes);
	
		// html "pre"-tag attributes
		$htmlAttributes = array(
				'data-enlighter-language' => $shortcodeAttributes['lang'],
				'data-enlighter-theme' => $shortcodeAttributes['theme'],
				'data-enlighter-title' => $shortcodeAttributes['tab'],
				'data-enlighter-highlight' => $shortcodeAttributes['highlight'],
				'class' => 'EnlighterJSRAW'
		);
	
		// grouping enabled ?
		if ($shortcodeAttributes['group']){
			$htmlAttributes['data-enlighter-group'] = $shortcodeAttributes['group'];
		}
	
		// generate html output
		return $this->generateCodeblock($htmlAttributes, $content);
	}
	
	/**
	 * Generate HTML output (code within "pre"-tag including options)
	 * @param Array $attributes
	 * @param String $content
	 */
	private function generateCodeblock($attributes, $content){
		// generate "pre" wrapped html output
		$html = HtmlUtil::generateTag('pre', $attributes, false);
		
		// remove automatic generated html editor tags (from wpautop())
		$content = $this->removeWpAutoP($content);
		
		// strip specialchars
		$content = htmlspecialchars($content, ENT_COMPAT | ENT_XHTML, 'UTF-8', false);
				
		// add closing tag
		return $html.$content.'</pre>';
	}

	/**
	 * Removes wordpress auto-texturize handler from used shortcodes
	 * @param Array $shortcodes
	 */
	public function texturizeHandler($shortcodes) {
		return array_merge($shortcodes, $this->_registeredShortcodes);
	}
	
	/**
	 * Removes automatic generated html editor tags (from wpautop()) and restores linebreaks
	 * @param String $content
	 */
	private function removeWpAutoP($content){
		// wpautop priority changed ?
		if ($this->_config['wpAutoPFilterPriority']!='default'){
			// no modification needed
			return $content;
		}else{
			// fallback: remove added tags - will work on most cases
			return str_replace(array('<br />', '<p>', '</p>'), array('', '', "\n"), $content);
		}
	}
}