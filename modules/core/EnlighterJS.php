<?php

namespace Enlighter;

use Enlighter\skltn\ResourceManager;
use Enlighter\skltn\CacheManager;
use Enlighter\skltn\JsBuilder;

class EnlighterJS{


    // css cache filename
    const CSS_FILENAME = 'enlighterjs.min.css';
    
    // js cache filename
    const JS_FILENAME = 'enlighterjs.min.js';

    // plugin config
    private $_config;

    // cache manager instance
    private $_cacheManager;

    // theme customizer
    private $_themeCustomizer;

    public function __construct($config, $cacheManager, $themeCustomizer){
        // store plugin config
        $this->_config = $config;

        // store cache manager
        $this->_cacheManager = $cacheManager;

        // store theme customizer instance
        $this->_themeCustomizer = $themeCustomizer;
    }

    // generate the EnlighterJS related config object
    public function getConfig(){
        return array(
            'selectors' => array(
                'block' => $this->_config['enlighterjs-selector-block'],
                'inline' => $this->_config['enlighterjs-selector-inline']
            ),
            'options' => array(
                'indent' =>             $this->_config['enlighterjs-indent'],
                'ampersandCleanup' =>   $this->_config['enlighterjs-ampersandcleanup'],
                'linehover' =>          $this->_config['enlighterjs-linehover'],
                'rawcodeDbclick' =>     $this->_config['enlighterjs-rawcodedbclick'],
                'textOverflow' =>       $this->_config['enlighterjs-textoverflow'],
                'linenumbers' =>        $this->_config['enlighterjs-linenumbers'],
                'theme' =>              $this->_config['enlighterjs-theme'],
                'language' =>           $this->_config['enlighterjs-language'],
                'retainCssClasses' =>   $this->_config['enlighterjs-retaincss'],
                'collapse' => false,
                'toolbarOuter' => '',
                'toolbarTop' => '{BTN_RAW}{BTN_COPY}{BTN_WINDOW}{BTN_WEBSITE}',
                'toolbarBottom' => ''
            )
        );
    }

    // generate EnlighterJS initilization code including wrapper
    public function getInitializationCode(){
        // @see resources/init/enlighterjs.init.js
        $wrapper = '!function(e,n){if("undefined"!=typeof EnlighterJS){';
        $wrapper.= 'var o=' . json_encode($this->getConfig()) . ';';
        $wrapper .= '(e.EnlighterJSINIT=function(){EnlighterJS.init(o.selectors.block,o.selectors.inline,o.options)})()}else{(n&&(n.error||n.log)||function(){})("Error: EnlighterJS resources not loaded yet!")}}(window,console);';

        return $wrapper;
    }

    // enqueue resources
    public function enqueue(){

        // load EnlighterJS themes ?
        if ($this->_config['enlighterjs-assets-themes']){
            // cache file exists ? if not regenerate
            $this->cacheCheckCSS();
            
            // include local css file - use cache hash!
            ResourceManager::enqueueStyle('enlighterjs', 'cache/' . self::CSS_FILENAME, array(), CacheManager::getCacheHash());
        }

        // load EnlighterJS library ?
        if ($this->_config['enlighterjs-assets-js']){
            // cache file exists ? if not regenerate
            $this->cacheCheckJS();
            
            // include merged js file - use cache hash!
            ResourceManager::enqueueScript('enlighterjs', 'cache/' . self::JS_FILENAME, array(), CacheManager::getCacheHash());
        }

        // add inline initialization code ?
        if ($this->_config['enlighterjs-init'] === 'inline'){
            ResourceManager::enqueueDynamicScript($this->getInitializationCode(), 'enlighterjs');
        }
    }

    // generate js file
    public function cacheCheckJS(){
        // file exists ? skip file generation
        if ($this->_cacheManager->fileExists(self::JS_FILENAME)){
            return;
        }

        // initialize js generator
        $jsGenerator = new JsBuilder();

        // add enlighterjs library
        $jsGenerator->addFile(ENLIGHTER_PLUGIN_PATH . '/resources/enlighterjs/enlighterjs.min.js');

        // merged initialization code ?
        if ($this->_config['enlighterjs-init'] === 'merged'){
            // append init code
            $jsGenerator->addRaw($this->getInitializationCode());
        }
        
        // generate + store file
        $this->_cacheManager->writeFile(self::JS_FILENAME, $jsGenerator->render());
    }

    public function cacheCheckCSS(){
        // cache file exists ?
        if ($this->_cacheManager->fileExists(self::CSS_FILENAME)){
            return;
        }

        // generate css file
        $this->_themeCustomizer->generateCSS();
    }
}