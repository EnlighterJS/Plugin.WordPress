<?php

namespace Enlighter;

use \Enlighter\skltn\ResourceManager as ResourceManager;
use \Enlighter\editor\EditorConfig;
use \Enlighter\editor\Gutenberg as GutenbergEditor;
use \Enlighter\editor\TinyMCE as TinyMCEEditor;

class ResourceLoader{
    
    // available language keys
    private $_languageKeys;
    
    // stores the plugin config
    private $_config;
    
    // list of external themes
    private $_themeManager;

    // tinymce editor
    private $_tinymce;

    // update hash
    private $_uhash;

    // theme generator
    private $_themeGenerator;

    // enlighterjs resource manager
    private $_enlighterjs;

    // editor config
    private $_editorConfig;

    public function __construct($settingsUtil, $cacheManager, $themeManager, $languageKeys){
        // store local plugin config
        $this->_config = $settingsUtil->getOptions();

        // store language keys
        //$this->_languageKeys = $languageKeys;

        // local theme manager instance (required for external themes)
        //$this->_themeManager = $themeManager;

        // visual editor integration
        //$this->_tinymce = new TinyMCE($settingsUtil, $cacheManager, $languageKeys);

        // get last update hash
        //$this->_uhash = get_option('enlighter-settingsupdate-hash', '0A0B0C');

        // create new js config generator
        //$this->_jsConfigGenerator = new ConfigGenerator($settingsUtil, $cacheManager);

        // create new theme generator instance
        //$this->_themeGenerator = new ThemeGenerator($settingsUtil, $cacheManager);

        // enlighterjs resource initializtion
        $this->_enlighterjs = new EnlighterJS($this->_config);

        // editor config generator
        $this->_editorConfig = new EditorConfig($this->_config);
    }

    // Load the Frontend Editor Resources
    public function frontendEditor(){
        // Inline Editor Configuration (Themes...)
        //add_action('wp_head', array($this, 'appendInlineEditorConfig'));

        // apply editor modifications
        //if ($this->_config['enableFrontendTinyMceIntegration']) {
        //    $this->_tinymce->integrate();
        //}

        // load text editor ?
        //if ($this->_config['enableQuicktagFrontendIntegration']){
        //    add_action('wp_enqueue_scripts', array($this, 'appendTextEditorJS'));
        //}


    }

    // Load the Backend Editor Resources
    public function backendEditor(){
        // Inline Editor Configuration (Themes...)
        add_action('admin_print_scripts', array($this, 'appendInlineEditorConfig'));

        // apply editor modifications
        if ($this->_config['enableTinyMceIntegration']) {
            $this->_tinymce->integrate();
        }

        // load text editor ?
        if ($this->_config['enableQuicktagBackendIntegration']){
            add_action('admin_enqueue_scripts', array($this, 'appendTextEditorJS'));
        }

        // load Gutenberg editor plugin ?
        if ($this->_config['gutenbergSupport']){
            add_action('enqueue_block_editor_assets', array($this, 'loadGutenbergPlugin'));
        }
    }

    // Load the Backend Settings Resources
    public function backendSettings(){
        add_action('admin_enqueue_scripts', function(){
            // new UI styles
            ResourceManager::enqueueStyle('enlighter-settings', 'admin/skltn.css');

            // theme data
            //$this->enqueueScript('enlighter-themes', 'admin/ThemeStyles.js');

            // settings init script
            ResourceManager::enqueueScript('enlighter-settings', 'admin/skltn.js', array('jquery', 'wp-color-picker'));
        });
    }

    // append the Enlighter Editor/Settings Config
    public function appendInlineEditorConfig(){
        ResourceManager::enqueueDynamicScript($this->_jsConfigGenerator->getEditorPluginConfig());
    }

    public function appendTextEditorJS(){
        // text editor plugin
        $this->enqueueScript('enlighter-texteditor', 'editor/TextEditor.js', array('jquery'));
    }

    // initialzize the frontend
    public function frontendEnlighter(){

        // load enlighterjs resources
        add_action('wp_enqueue_scripts', array($this->_enlighterjs, 'enqueue'), 50);
    }

    // disable frontend scripts (in footer; resource optimization)
    public function disableFrontendScripts(){
        wp_dequeue_script('enlighter-local');
        wp_dequeue_script('enlighter-config');
        wp_dequeue_script('enlighter-jetpack-infinitescroll');
        remove_action('wp_footer', array($this, 'appendInlineEnlighterConfig'), 30);
    }  
}