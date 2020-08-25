<?php

namespace Enlighter;

use \Enlighter\skltn\ResourceManager as ResourceManager;
use Enlighter\skltn\CacheManager;
use Enlighter\EnlighterJS;

class DynamicResourceInvocation{
    
    // stores the plugin config
    private $_config;

    // store enlighterjs object
    private $_enlighterjs;

    public function __construct($config, $enlighterjs){
        // store local plugin config
        $this->_config = $config;

        // store enlighterjs instance
        $this->_enlighterjs = $enlighterjs;
    }

    // generate EnlighterJS initilization code including wrapper
    public function getInitializationCode(){

        // retrieve config object
        $enlighterjsConfig = $this->_enlighterjs->getConfig();

        // resources to load
        $resources = array();

        // load EnlighterJS themes ?
        if ($this->_config['enlighterjs-assets-themes']){
            // cache file exists ?
            $this->_enlighterjs->cacheCheckCSS();
            
            // include local css file - use cache hash!
            $resources[] = ResourceManager::getResourceUrl('cache/' . EnlighterJS::CSS_FILENAME, CacheManager::getCacheHash());
        }

        // load EnlighterJS library ?
        if ($this->_config['enlighterjs-assets-js']){

            // include plain js file
            $resources[] = ResourceManager::getResourceUrl('enlighterjs/enlighterjs.min.js');
        }

        // add resource links
        $enlighterjsConfig['resources'] = $resources;

        // @see resources/init/dri.init.js
        $wrapper = '!function(e,n){var r='. json_encode($enlighterjsConfig);
        $wrapper .= ',o=document.getElementsByTagName("head")[0],t=n&&(n.error||n.log)||function(){};e.EnlighterJSINIT=function(){!function(e,n){var r=0,l=null;function c(o){l=o,++r==e.length&&(!0,n(l))}e.forEach(function(e){switch(e.match(/\.([a-z]+)(?:[#?].*)?$/)[1]){case"js":var n=document.createElement("script");n.onload=function(){c(null)},n.onerror=c,n.src=e,n.async=!0,o.appendChild(n);break;case"css":var r=document.createElement("link");r.onload=function(){c(null)},r.onerror=c,r.rel="stylesheet",r.type="text/css",r.href=e,r.media="all",o.appendChild(r);break;default:t("Error: invalid file extension",e)}})}(r.resources,function(e){e?t("Error: failed to dynamically load EnlighterJS resources!",e):"undefined"!=typeof EnlighterJS?EnlighterJS.init(r.selectors.block,r.selectors.inline,r.options):t("Error: EnlighterJS resources not loaded yet!")})},(document.querySelector(r.selectors.block)||document.querySelector(r.selectors.inline))&&e.EnlighterJSINIT()}(window,console);';

        return $wrapper;
    }

    // enqueue resources
    public function enqueue(){
        ResourceManager::enqueueDynamicScript($this->getInitializationCode());
    }
}