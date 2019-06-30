<?php

namespace Enlighter\editor;

class EditorConfig{

    // plugin/editor config
    private $_config;

    private $_enlighterjs;

    // theme manager (build in + user themes)
    protected $_themeManager;

    // language manager (build-in)
    protected $_languageManager;
    
    public function __construct($config, $ejs, $languageManager, $themeManager){
        $this->_config = $config;
        $this->_enlighterjs = $ejs;

        // store language keys
        $this->_languageManager = $languageManager;

        // local theme manager instance (required for external themes)
        $this->_themeManager = $themeManager;
    }

    public function getEditorConfigCode(){
        // create config object
        return 'EnlighterJS_EditorConfig = ' . json_encode(array(
            'languages' => $this->_languageManager->getLanguages(),
            'themes' => $this->_themeManager->getThemes(),
            'config' => $this->_enlighterjs->getConfig(),
            'tinymce' => array(
                'tabIndentation' => $this->_config['tinymce-tabindentation'],
                'keyboardShortcuts' => $this->_config['tinymce-keyboardshortcuts']
            ),
            'text' => array(
                'quicktags' => $this->_config['quicktag-mode']
            )
        )) . ';';
    }
}