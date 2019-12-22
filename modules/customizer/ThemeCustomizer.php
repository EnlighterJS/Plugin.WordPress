<?php

namespace Enlighter\customizer;

use Enlighter\skltn\CssBuilder;

class ThemeCustomizer{

    // css cache filename
    const CSS_FILENAME = 'enlighterjs.min.css';

    // css builder instance
    private $_cssGenerator;

    // cache manage instance
    private $_cacheManager;

    // plugin config
    private $_config;

    public function __construct($settingsManager, $cacheManager){

        // store config
        $this->_config = $settingsManager->getOptions();

        // store cache manager
        $this->_cacheManager = $cacheManager;

        // create new generator instance
        $this->_cssGenerator = new CssBuilder();

        // cache file exists ?
        if (!$cacheManager->fileExists(self::CSS_FILENAME)){
            $this->generate();
        }
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
    public function generate(){

        // toolbar styles (Appearance::Toolbar)
        Toolbar::customize($this->_config, $this->_cssGenerator);

        // add enlighterjs base styles
        $this->_cssGenerator->addFile(ENLIGHTER_PLUGIN_PATH . '/resources/enlighterjs/enlighterjs.min.css');

        // append customizer config
        $this->_cssGenerator->addRaw($this->loadCustomizerConfig());

        // generate + store file
        $this->_cacheManager->writeFile(self::CSS_FILENAME, $this->_cssGenerator->render());
    }
}