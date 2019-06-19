<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2019 Andi Dittrich
// -- https://github.com/AndiDittrich/WP-Skeleton
// --
// ---------------------------------------------------------------------------------------------------------------
// --
// -- This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
// -- If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// ---------------------------------------------------------------------------------------------------------------

// Manages plugin related static resources

namespace Enlighter\skltn;

class ResourceManager{

    // generate the resource url for ALL plugin related public files
    public static function getResourceUrl($filename, $version=null){
        // strip whitespaces
        $filename = trim($filename);

        // apply resource filter
        $url = apply_filters('enlighter_resource_url', $filename, ENLIGHTER_PLUGIN_URL, $version);

        // filename not changed and relative url ? prepend plugin url, keep absolute path
        // regex will match URLs with protocol scheme like http:// ftp://
        // as well as protocol-less schemes like //fonts.example.org
        // and domain relative urls like /img/image1.png
        if ($filename == $url && preg_match('#^(?:/|[a-z]+:/).*$#i', $filename) === 0){
            // append version ?
            if (is_string($version)){
                $filename .= '?' . $version;
            }

            // cache file ?
            if (preg_match('#^cache/(.*)$#', $filename, $matches) === 1){
                // retrieve cache file url (add blog id)
                // add cache hash on request
                $url = CacheManager::getFileUrl($matches[1], ($version === true));

                // default: resource file
            }else{
                $url = ENLIGHTER_PLUGIN_URL . 'resources/' . $filename;
            }
        }

        return $url;
    }

    // highlevel enqueue wrapper
    public static function enqueueStyle($name, $filename, $dependencies = array(), $version = ENLIGHTER_VERSION){
        // get the resource url
        $url = self::getResourceUrl($filename);

        // trigger wordpress script loader
        if ($url) {
            wp_enqueue_style($name, $url, $dependencies, $version);
        }
    }

    // highlevel enqueue wrapper
    public static function enqueueScript($name, $filename, $dependencies = array(), $version = ENLIGHTER_VERSION, $inFooter = true){
        // get the resource url
        $url = self::getResourceUrl($filename);

        // trigger wordpress script loader
        if ($url){
            wp_enqueue_script($name, $url, $dependencies, $version, $inFooter);
        }
    }

    // enqueue dynamics scripts
    public static function enqueueDynamicScript($code, $dependencie=null){

        // use build-in wordpress hook
        wp_add_inline_script($dependencie, $code);
    }

    // cache
    private static $__dynamicStyleBuffer = false;
    
    // enqueue dynamics styles
    public static function enqueueDynamicStyle($style){
        // initialized ?
        if (self::$__dynamicStyleBuffer === false){
            // hook into header to print styles
            add_action('wp_head', function(){
                echo '<style type="text/css">', self::$__dynamicStyleBuffer ,'</style>';
            });

            // clear buffer
            self::$__dynamicStyleBuffer = '';
        }

        // append
        self::$__dynamicStyleBuffer .= $style;
    }
}