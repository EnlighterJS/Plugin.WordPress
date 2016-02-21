<?php 
/**
	Cache Path/Url Management
	Version: 1.0
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

class CacheManager{
	
	// local cache path
	private $_cachePath;
	
	// cache url (public accessable)
	private $_cacheUrl;

    // update hash
    private $_uhash;
	
	public function __construct($settingsUtil){
		// default cache
		$this->_cachePath = ENLIGHTER_PLUGIN_PATH.'/cache/';
		$this->_cacheUrl = plugins_url('/enlighter/cache/');

        // get last update hash
        $this->_uhash = get_option('enlighter-settingsupdate-hash', '0A0B0C');
	}

    // file_put_contents wrapper
    public function writeFile($filename, $content){
        // cache directory available ?
        if (!is_dir($this->_cachePath)){
            mkdir($this->_cachePath);
        }

        // write file - prepend absolute cache path
        file_put_contents($this->_cachePath . $filename, $content);
    }

    // caches file available ?
    public function fileExists($filename){
        return file_exists($this->_cachePath . $filename);
    }

	/**
	 * drop cache items
	 */
	public function clearCache(){
		// cache dir
		$this->rmdir($this->_cachePath);

        // store last settings update time (unique hash to avoid caching)
        $hash = substr(sha1(microtime(true) . uniqid()), 0, 10);
        update_option('enlighter-settingsupdate-hash', $hash, true);
	}
	
	public function autosetPermissions(){
		// get directory permissions - use mask to ignore filesystem info
		$mode = fileperms($this->_cachePath) & 0x1FF;
	
		// add owner +w
		$mode |= 0x0080;
	
		// add group +w
		$mode |= 0x0010;
	
		// change permissions
		chmod($this->_cachePath, $mode);
	}

	public function isCacheAccessable(){
		return is_writeable($this->_cachePath);
	}
	
	public function getCachePath(){
		return $this->_cachePath;
	}
	
	public function getCacheUrl(){
		return $this->_cacheUrl;
	}

    // generate a url based on plugin_url with current cache hash
    public function getCacheFileUrl($filename){
        return plugins_url('/enlighter/cache/'. $filename .'?' . $this->_uhash);
    }
	
	/**
	 * Remove all files within the given directory (non recursive)
	 */ 
	private function rmdir($dir){
		// remove cached files
		if (is_dir($dir)){
			$files = scandir($dir);
			foreach ($files as $file){
				if ($file!='.' && $file!='..' && is_file($dir.$file)){
					unlink($dir.$file);
				}
			}
		}
	}

}