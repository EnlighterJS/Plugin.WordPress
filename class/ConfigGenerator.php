<?php
/**
	Enlighter JS COnfig Generator
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

class ConfigGenerator{
	
	private $_config;
	private $_cacheFile = '';
	
	public function __construct($settingsUtil, $cacheManager){
		$this->_config = $settingsUtil->getOptions();
		$this->_cacheFile = $cacheManager->getCachePath().'EnlighterJS.init.js';
	}
	
	public function isCached(){
		return file_exists($this->_cacheFile);
	}
	
	// generate js based config
	public function getJSConfig(){
		$c = 'window.addEvent(\'domready\', function(){';
		$c .= 'if (typeof EnlighterJS == "undefined"){return;};';
		$c .= 'EnlighterJS.Util.Init(\''. $this->_config['selector'] .'\', \''. $this->_config['selectorInline'] .'\', ';
		$c .= json_encode(array(
				'language' => 			$this->_config['defaultLanguage'],
				'theme' => 				$this->_config['defaultTheme'],
				'indent' => 			intval($this->_config['indent']),
				'hover' => 				($this->_config['hoverClass'] ? 'hoverEnabled': 'NULL'),
				'showLinenumbers' => 	($this->_config['linenumbers'] ? true : false),
				'rawButton' => 			($this->_config['rawButton'] ? true : false),
				'infoButton' => 		($this->_config['infoButton'] ? true : false),
				'windowButton' => 		($this->_config['windowButton'] ? true : false),
				'rawcodeDoubleclick' => ($this->_config['rawcodeDoubleclick'] ? true : false),
				'grouping' => true,
                'cryptex' => array(
                        'enabled' => ($this->_config['cryptexEnabled'] ? true : false),
                        'email' =>  $this->_config['cryptexFallbackEmail']
                )
		));
		$c .= ');});';
		
		return $c;
	}
	
	// store generated config into cachefile
	public function generate(){
		file_put_contents($this->_cacheFile, $this->getJSConfig());
	}
}