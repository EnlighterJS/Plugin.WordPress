<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2020 Andi Dittrich
// -- https://github.com/AndiDittrich/WP-Skeleton
// --
// ---------------------------------------------------------------------------------------------------------------
// --
// -- This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
// -- If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// ---------------------------------------------------------------------------------------------------------------
 
// Cache Path/Url Management

namespace Enlighter\skltn;

class CacheManager{
    
    // local cache path
    private $_cachePath;
    
    // cache url (public accessible)
    private $_cacheUrl;

    // file prefix
    private $_prefix;

    // update hash to avoid caching of modified files
    private static $__cacheHash = '0A0B0C';

    // static url (provides simple access to getUrl())
    private static $__url;
    
    public function __construct(){

        // mu site ? generate prefix based on blog ID
        $this->_prefix = (is_multisite() ? 'X' . get_current_blog_id() . '_' : '');

        // default cache
        $this->_cachePath = ENLIGHTER_PLUGIN_PATH.'/cache/';
        $this->_cacheUrl = plugins_url('/enlighter/cache/');

        // generate static url
        self::$__url = $this->_cacheUrl . $this->_prefix;

        // get last update hash
        self::$__cacheHash = get_option('enlighter-cache-hash', '0A0B0C');
    }

    // custm cache path/url
    public function setCacheLocation($cachePath, $cacheUrl){
        if (self::isPathAccessible($cachePath)){
            $this->_cachePath = trailingslashit($cachePath);
            $this->_cacheUrl = trailingslashit($cacheUrl);

            // generate static url
            self::$__url = $this->_cacheUrl . $this->_prefix;
        }
    }

    // file_put_contents wrapper
    public function writeFile($filename, $content){
        // ensure that the cache is accessible
        if (!$this->isCacheAccessible()){
            return false;
        }

        // write file - prepend absolute cache path
        file_put_contents($this->_cachePath . $this->_prefix . $filename, $content);
    }

    // caches file available ?
    public function fileExists($filename){
        return file_exists($this->_cachePath . $filename);
    }

    // drop cache items
    public function clearCache($clearAll = false){
        // cache dir
        $this->dropCacheFiles($this->_cachePath, $clearAll);

        // store last settings update time (unique hash to avoid caching)
        self::$__cacheHash = Hash::base64(microtime(true) . uniqid(), 15);
        update_option('enlighter-cache-hash', self::$__cacheHash, true);
    }
    
    public function autosetPermissions(){
        // change permissions
        // owner +rwx
        // group +rwx
        // world +r
        chmod($this->_cachePath, 0774);
    }

    public function isCacheAccessible(){
        return self::isPathAccessible($this->_cachePath);
    }
    
    public function getCachePath(){
        return $this->_cachePath;
    }
    
    public function getCacheUrl(){
        return $this->_cacheUrl;
    }

    // instance-less access to pre-generated url
    public static function getFileUrl($filename, $useHash = false){
        if ($useHash){
            // append cache hash as query param
            return self::$__url . $filename . '?'  . self::$__cacheHash;
        }else{
            return self::$__url . $filename;
        }
    }

    // check path accessibility
    public static function isPathAccessible($path){
        // windows platforms
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
            return is_writeable($path);

        // linux
        }else{
            return is_writeable($path) && is_executable($path);
        }
    }

    // retrieve current cache hash (useful to enqueue resources)
    public static function getCacheHash(){
        return self::$__cacheHash;
    }

    // retrieve the number of cached files
    public function getNumFiles(){
        // remove cached files
        if (is_dir($this->_cachePath)){

            // file counter
            $counter = 0;

            // get file list
            $files = scandir($this->_cachePath);

            // process files
            foreach ($files as $file){

                // regular file ?
                if ($file !== '.' && $file !== '..' && is_file($this->_cachePath.$file)){
                    $counter++;
                }
            }

            return $counter;
        }

        // cannot retrieve files
        return -1;
    }

    // Remove all files within the given directory (non recursive)
    private function dropCacheFiles($dir, $clearAll){
        // remove cached files
        if (is_dir($dir)){
            // get file list
            $files = scandir($dir);

            // process files
            foreach ($files as $file){

                // regular file ?
                if ($file!='.' && $file!='..' && is_file($dir.$file)){
                    // MU prefix set ?
                    if ($clearAll === false && strlen($this->_prefix) > 0){
                        // file starts with prefix ?
                        if (substr($file, 0, strlen($this->_prefix)) == $this->_prefix){
                            unlink($dir.$file);
                        }
                    // drop all cache files
                    }else{
                        unlink($dir.$file);
                    }

                }
            }
        }
    }
}