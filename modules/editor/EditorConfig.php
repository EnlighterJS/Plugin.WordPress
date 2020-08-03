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
        // filtered languages 
        $languages = apply_filters('enlighter_editor_languages', $this->_languageManager->getLanguages());

        // filtered themes 
        $themes = apply_filters('enlighter_editor_themes', $this->_themeManager->getThemes());

        // retrieve enlighterjs default config
        $enlighterjsConfig = $this->_enlighterjs->getConfig();
        
        // create config object
        $config = array(
            'languages' => $languages,
            'themes' => $themes,
            'config' => $enlighterjsConfig['options'],
            'tinymce' => array(
                'tabIndentation' => $this->_config['tinymce-tabindentation'],
                'keyboardShortcuts' => $this->_config['tinymce-keyboardshortcuts']
            ),
            'text' => array(
                'quicktags' => $this->_config['quicktag-mode']
            )
        );
        
        // apply config
        $config = apply_filters('enlighter_editor_config', $config);

        // create config object
        return 'EnlighterJS_EditorConfig = ' . json_encode($config) . ';';
    }
}