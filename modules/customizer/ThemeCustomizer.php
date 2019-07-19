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

    // re-generate theme
    public function generate(){

        // toolbar styles (Appearance::Toolbar)
        Toolbar::customize($this->_config, $this->_cssGenerator);

        // add enlighterjs base styles
        $this->_cssGenerator->addFile(ENLIGHTER_PLUGIN_PATH . '/resources/enlighterjs/enlighterjs.min.css');

        // generate + store file
        $this->_cacheManager->writeFile(self::CSS_FILENAME, $this->_cssGenerator->render());
    }
}