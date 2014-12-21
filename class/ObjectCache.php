<?php
/**
	Object/Array Caching Utility - provides a significant performance enhancement using opcode-caching
	Version: 1.0
	Author: Andi Dittrich
	Author URI: http://andidittrich.de
	Plugin URI: http://andidittrich.de/go/enlighter
	License: MIT X11-License
	
	Copyright (c) 2014, Andi Dittrich
	
	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
namespace Enlighter;

class ObjectCache{
	
	// internal storage
	private $_storage = array();
	
	// modified flag
	private $_isModified = false;
	
	// name of the cache file
	private $_filename = null;
	
	// cache type (php and json are supported)
	private $_cacheType = 'php';
	
	// cahce data availabe ?
	private $_dataAvailable = false;
	
	public function __construct($filename, $loadOnInit = true, $options = array()){
		// store filename
		$this->_filename = $filename;
		
		// load cache file on startup ?
		if ($loadOnInit){
			$this->load();
		}
	}
	
	// store changes on destruct
	public function __destruct(){
		$this->store();
	}
	
	// cached data available ?
	public function isCachedDataAvailable(){
		return $this->_dataAvailable;
	}
	
	/**
	 * Load given file into internal storage
	 * @param unknown $filename
	 */
	public function load(){
		if (is_file($this->_filename) || is_readable($this->_filename)){
			$this->_storage = require($this->_filename);
			$this->_dataAvailable = true;
		}
	}
	
	/** 
	 * Store cache
	 */
	public function store(){
		if ($this->_filename !== null && $this->_isModified === true && is_writable(dirname($this->_filename))){
			file_put_contents($this->_filename, '<?php return '.var_export($this->_storage, true).'; ?>');
		}
	}
	
	public function setValue($key, $value){
		// update value
		$this->_storage[$key] = $value;
		
		// set update flag
		$this->_isModified = true;
		
		// new data available
		$this->_dataAvailable = true;
	}
	
	/**
	 * Get value by key
	 * @param unknown $key
	 * @return multitype:|NULL
	 */
	public function getValue($key){
		if (isset($this->_storage[$key])){
			return $this->_storage[$key];
		}else{
			return null;
		}
	}
	
	// fetch full dataset
	public function getData(){
		return $this->_storage;
	}
	
	// set storage data directly
	public function setData($data){
		$this->_storage = $data;
		
		// set update flag
		$this->_isModified = true;
		
		// new data available
		$this->_dataAvailable = true;
	}
	
	// clear cache
	public function clear(){
		if (is_file($this->_filename) || is_writable($this->_filename)){
			unlink($this->_filename);
		}
		
		// reset flags
		$this->_isModified = false;
		$this->_dataAvailable = false;
	}
}