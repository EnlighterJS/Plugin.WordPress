<?php

namespace Enlighter\customizer;

use Enlighter\EnlighterJS;
use Enlighter\skltn\ResourceManager;
use Enlighter\skltn\CssBuilder;

class ThemeCustomizer{

    // cache manage instance
    private $_cacheManager;

    // plugin config
    private $_config;

    public function __construct($settingsManager, $cacheManager){

        // store config
        $this->_config = $settingsManager->getOptions();

        // store cache manager
        $this->_cacheManager = $cacheManager;
   }

    // register settings
    public function registerSettings(){
        register_setting('enlighter-settings-group', 'enlighter-customizer', array($this, 'storeSettings'));
    }

    // process settings -> raw css
    public function storeSettings($settings){
        // type string ?
        if (!is_string($settings)){
            return $this->loadCustomizerConfig();
        }
        
        // strip whitespaces
        $settings = trim($settings);

        // content set ?
        if (strlen($settings) < 10){
            return $this->loadCustomizerConfig();
        }

        return $settings;
    }

    // load settings
    public function loadCustomizerConfig(){
        // try to load options
        $options = get_option('enlighter-customizer', null);

        // option not available
        if ($options === null){
            // create empty option with autoload=false
            add_option('enlighter-customizer', '.enlighter-default{}', '', false);

            // return empty string
            return '';
        }

        return $options;
    }

    // re-generate theme
    public function generateCSS(){

        // create new generator instance
        $cssGenerator = new CssBuilder();

        // toolbar styles (Appearance::Toolbar)
        Toolbar::customize($this->_config, $cssGenerator);

        // add enlighterjs base styles
        $cssGenerator->addFile(ENLIGHTER_PLUGIN_PATH . '/resources/enlighterjs/enlighterjs.min.css');

        // append customizer config
        $cssGenerator->addRaw($this->loadCustomizerConfig());

        // generate + store file
        $this->_cacheManager->writeFile(EnlighterJS::CSS_FILENAME, $cssGenerator->render());
    }

    // generate the EnlighterJS related config object
    public function getConfig(){
        return array(
            'themeURL' => ResourceManager::getResourceURL('enlighterjs/enlighterjs.min.css'),
            'themeName' => 'wpcustom',
            'defaults'=> '#enlighterjs-customizer-defaults',
            'settings'=> '#enlighterjs-customizer-settings',
            'lines' => '#enlighterjs-customizer-lines',
            'rawcode' => '#enlighterjs-customizer-rawcode',
            'buttons' => '#enlighterjs-customizer-buttons',
            'formExchange'=> '#enlighterjs-customizer-exchange',
            'tokens' => array(
                'comments' => '#enlighterjs-tokens-comments',
                'expressions'=> '#enlighterjs-tokens-expressions',
                'generic'=> '#enlighterjs-tokens-generic',
                'keywords'=> '#enlighterjs-tokens-keywords',
                'languages'=> '#enlighterjs-tokens-languages',
                'methods'=> '#enlighterjs-tokens-methods',
                'numbers'=> '#enlighterjs-tokens-numbers',
                'strings'=> '#enlighterjs-tokens-strings',
                'text'=> '#enlighterjs-tokens-text'
            )
        );
    }

    // generate EnlighterJS initilization code including wrapper
    public function getInitializationCode(){
        // @see resources/init/enlighterjs.init.js
        $wrapper = '!function(n,o){"undefined"!=typeof EnlighterJS_Customizer?(n.EnlighterJSCustomizerINIT=function(){';
        $wrapper .= 'EnlighterJS_Customizer.init('.
                        json_encode($this->getConfig()) .
                    ')';
        $wrapper .= '})():(o&&(o.error||o.log)||function(){})("Error: EnlighterJS Theme-Customizer resources not loaded yet!")}(window,console);';
        return $wrapper;
    }

    // enqueue resources
    public function enqueue(){
        // include customizer compoinent
        ResourceManager::enqueueScript('enlighterjs-customizer', 'customizer/enlighterjs.customizer.min.js', array(), ENLIGHTER_VERSION);

        // add initialization code ?
        ResourceManager::enqueueDynamicScript($this->getInitializationCode(), 'enlighterjs-customizer');
    }
}