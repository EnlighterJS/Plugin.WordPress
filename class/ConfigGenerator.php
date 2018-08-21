<?php

namespace Enlighter;

class ConfigGenerator{
    
    private $_config;
    private $_cacheFilename = 'EnlighterJS.init.js';
    private $_cacheManager = null;
    
    public function __construct($settingsUtil, $cacheManager){
        $this->_config = $settingsUtil->getOptions();
        $this->_cacheManager = $cacheManager;
    }
    
    public function isCached(){
        return $this->_cacheManager->fileExists($this->_cacheFilename);
    }

    public function getEnlighterJSConfig(){
        $c = 'EnlighterJS_Config = ' . json_encode(array(
            'selector' =>  array(
                'block' => $this->_config['selector'],
                'inline' => $this->_config['selectorInline']
            ),
            'language' =>             $this->_config['defaultLanguage'],
            'theme' =>                $this->_config['defaultTheme'],
            'indent' =>               intval($this->_config['indent']),
            'hover' =>                ($this->_config['hoverClass'] ? 'hoverEnabled': 'NULL'),
            'showLinenumbers' =>      ($this->_config['linenumbers'] ? true : false),
            'rawButton' =>            ($this->_config['rawButton'] ? true : false),
            'infoButton' =>           ($this->_config['infoButton'] ? true : false),
            'windowButton' =>         ($this->_config['windowButton'] ? true : false),
            'rawcodeDoubleclick' =>   ($this->_config['rawcodeDoubleclick'] ? true : false),
            'grouping' => true,
            'cryptex' => array(
                'enabled' =>          ($this->_config['cryptexEnabled'] ? true : false),
                'email' =>            $this->_config['cryptexFallbackEmail']
            )
        ));
        return $c . ';';
    }
    
    // generate js based config
    public function getInitializationConfig(){
        // global config
        $c = $this->getEnlighterJSConfig();

        // initialization code
        $c .= file_get_contents(ENLIGHTER_PLUGIN_PATH . '/resources/EnlighterJS.Startup.min.js');

        return $c;
    }
    
    // store generated config into cachefile
    public function generate(){
        $this->_cacheManager->writeFile($this->_cacheFilename, $this->getInitializationConfig());
    }

    public function getEditorPluginConfig(){
        $c = 'EnlighterJS_EditorConfig = ';

        // create config object
        $c .= json_encode(array(
            'languages' => \Enlighter::getAvailableLanguages(),
            'themes' => \Enlighter::getAvailableThemes(),
            'config' => array(
                'theme' => $this->_config['defaultTheme'],
                'language' => $this->_config['defaultLanguage'],
                'linenumbers' => ($this->_config['linenumbers'] ? true : false),
                'indent' => intval($this->_config['indent']),
                'tabIndentation' => ($this->_config['editorTabIndentation'] ? true : false),
                'quicktagMode' => $this->_config['editorQuicktagMode'],
                'languageShortcode' => ($this->_config['languageShortcode'] ? true : false)
            )
        ));

        return $c;
    }
}