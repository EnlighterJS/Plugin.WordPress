<?php

namespace Enlighter\editor;

class ConfigGenerator{
    
    private $_config;
    
    public function __construct($settingsUtil){
        $this->_config = $settingsUtil->getOptions();
    }

    public function getEditorPluginConfig(){
        $c = 'Enlighter_EditorConfig = ';

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