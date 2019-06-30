<?php

namespace Enlighter;

use Enlighter\skltn\ResourceManager;

class EnlighterJS{

    private $_config;

    public function __construct($config){
        $this->_config = $config;
    }

    // generate the EnlighterJS related config object
    public function getConfig(){
        return array(
            'indent' =>             $this->_config['enlighterjs-indent'],
            'ampersandCleanup' =>   $this->_config['enlighterjs-ampersandcleanup'],
            'linehover' =>          $this->_config['enlighterjs-linehover'],
            'rawcodeDbclick' =>     $this->_config['enlighterjs-rawcodedbclick'],
            'textOverflow' =>       $this->_config['enlighterjs-textoverflow'],
            'linenumbers' =>        $this->_config['enlighterjs-linenumbers'],
            'theme' =>              $this->_config['enlighterjs-theme']
        );
    }

    // generate EnlighterJS initilization code including wrapper
    public function getInitializationCode(){
        // @see resources/init/enlighterjs.init.js
        $wrapper = '!function(n,o){"undefined"!=typeof EnlighterJS?(n.EnlighterJSINIT=function(){';
        $wrapper .= 'EnlighterJS.init('.
                        '"' . esc_attr($this->_config['enlighterjs-selector-block']) . '", ' .
                        '"' . esc_attr($this->_config['enlighterjs-selector-inline']) . '", ' .
                        json_encode($this->getConfig()) .
                    ')';
        $wrapper .= '})():(o&&(o.error||o.log)||function(){})("Error: EnlighterJS resources not loaded yet!")}(window,console);';
        return $wrapper;
    }

    // enqueue resources
    public function enqueue(){
        // dependencies
        $jsDependencies = array();
        
        // add EnlighterJS themes ?
        if ($this->_config['enlighterjs-assets-themes']){
            // include local css file
            ResourceManager::enqueueStyle('enlighterjs', 'enlighterjs/enlighterjs.min.css', array(), ENLIGHTER_VERSION);
        }

        // only include EnlighterJS js if enabled
        if ($this->_config['enlighterjs-assets-js']){
            // include local css file
            ResourceManager::enqueueScript('enlighterjs', 'enlighterjs/enlighterjs.min.js', array(), ENLIGHTER_VERSION);

            // Script required by other components
            $jsDependencies = array('enlighterjs');
        }

        // add initialization code ?
        if ($this->_config['enlighterjs-init'] === 'inline'){
            ResourceManager::enqueueDynamicScript($this->getInitializationCode(), 'enlighterjs');
        }
    }


    // append css
    public function appendEnlighterCSS(){
        // only include css if enabled
        if ($this->_config['enlighterjs-resources-themes']){
            // include generated css ?
            if ($this->_config['defaultTheme']=='wpcustom'){
                $this->enqueueStyle('enlighter-wpcustom', 'cache/EnlighterJS.custom.css', array(), $this->_uhash);

            }else{
                // include standard css file ?
                $this->enqueueStyle('enlighter-local', 'EnlighterJS.min.css');
            }
        }
        
        // load user themes ?
        if ($this->_config['embedExternalThemes']) {

            // embed available external themes
            foreach ($this->_themeManager->getUserThemes() as $theme => $sources) {
                $this->enqueueStyle('enlighter-external-' . strtolower($theme), $sources[1], array(), $this->_uhash);
            }
        }

    }
    
    // append js
    public function appendEnlighterJS(){

        // jetpack InfiniteScroll Extension enabled ?
        if ($this->_config['extJetpackInfiniteScroll']){
            // include local css file
            $this->enqueueScript('enlighter-jetpack-infinitescroll', 'plugin/JetpackInfiniteScroll.js', array('enlighterjs'), $this->_uhash);
        }
    }
}